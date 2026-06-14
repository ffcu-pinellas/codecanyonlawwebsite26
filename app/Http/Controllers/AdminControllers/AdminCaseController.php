<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\ClientCase;
use App\Models\CaseDocument;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminCaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|attorney']);
        parent::__construct();
    }

    public function index()
    {
        try {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                $cases = ClientCase::with(['client', 'attorney'])->orderBy('created_at', 'desc')->get();
            } else {
                $cases = ClientCase::with(['client', 'attorney'])
                    ->where('attorney_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            $title = __('Case Directory');
            return view('backend.pages.cases.index', compact('cases', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $title = __('Create New Case');
            $clients = User::role('client')->orderBy('name', 'asc')->get();
            $attorneys = User::role(['admin', 'attorney'])->orderBy('name', 'asc')->get();
            $case = null;

            return view('backend.pages.cases.form', compact('title', 'clients', 'attorneys', 'case'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'required|exists:users,id',
            'attorney_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,active,suspended,resolved',
            'court_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        try {
            // Generate unique Case Number
            do {
                $caseNumber = 'CS-' . rand(100000, 999999);
            } while (ClientCase::where('case_number', $caseNumber)->exists());

            $case = ClientCase::create([
                'case_number' => $caseNumber,
                'title' => $request->title,
                'description' => $request->description,
                'client_id' => $request->client_id,
                'attorney_id' => $request->attorney_id,
                'status' => $request->status,
                'court_date' => $request->court_date,
            ]);

            ActivityLog::log('Case Created', 'Created case ' . $case->case_number . ': "' . $case->title . '"');
            $this->sendSlackNotification('📂 New Legal Case Created: ' . $case->case_number . ' - "' . $case->title . '"');

            return redirect()->route('admin.cases.index')->with('success', __('Case created successfully. Case Number: ') . $case->case_number);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $case = ClientCase::with(['documents.uploader', 'milestones' => function($q) {
                $q->orderBy('milestone_date', 'asc')->orderBy('created_at', 'asc');
            }])->findOrFail($id);
            
            // Limit attorney access
            if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            $title = __('Edit Case #') . $case->case_number;
            $clients = User::role('client')->orderBy('name', 'asc')->get();
            $attorneys = User::role(['admin', 'attorney'])->orderBy('name', 'asc')->get();

            return view('backend.pages.cases.form', compact('title', 'clients', 'attorneys', 'case'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $case = ClientCase::findOrFail($id);

        if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'required|exists:users,id',
            'attorney_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,active,suspended,resolved',
            'court_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        try {
            $case->update([
                'title' => $request->title,
                'description' => $request->description,
                'client_id' => $request->client_id,
                'attorney_id' => $request->attorney_id,
                'status' => $request->status,
                'court_date' => $request->court_date,
            ]);

            ActivityLog::log('Case Updated', 'Updated case ' . $case->case_number);

            return redirect()->route('admin.cases.index')->with('success', __('Case details updated successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $case = ClientCase::findOrFail($id);

            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }

            ActivityLog::log('Case Deleted', 'Deleted case ' . $case->case_number);
            $case->delete();

            return redirect()->route('admin.cases.index')->with('success', __('Case deleted successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'files' => 'required|array',
            'files.*' => 'file|mimes:pdf,png,jpg,jpeg,doc,docx,xlsx|max:20480',
        ]);

        try {
            $case = ClientCase::findOrFail($id);

            if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            // Ensure target directory exists
            $uploadPath = public_path('upload/case-documents');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $uploadedFilesInfo = [];
            foreach ($request->file('files') as $file) {
                $fileExtension = $file->getClientOriginalExtension();
                $originalName = $file->getClientOriginalName();
                $newFileName = time() . '_' . uniqid() . '.' . $fileExtension;
                $file->move($uploadPath, $newFileName);
                $newFilePath = '/upload/case-documents/' . $newFileName;

                $fileTitle = $request->title ? ($request->title . ' - ' . pathinfo($originalName, PATHINFO_FILENAME)) : pathinfo($originalName, PATHINFO_FILENAME);
                if (!$request->title && count($request->file('files')) === 1) {
                    $fileTitle = pathinfo($originalName, PATHINFO_FILENAME);
                } elseif ($request->title && count($request->file('files')) === 1) {
                    $fileTitle = $request->title;
                }

                $document = CaseDocument::create([
                    'case_id' => $case->id,
                    'user_id' => Auth::id(),
                    'title' => $fileTitle ?: 'Case Document',
                    'file_path' => $newFilePath,
                    'file_type' => $fileExtension,
                    'file_size' => file_exists(public_path($newFilePath)) ? filesize(public_path($newFilePath)) : 0,
                    'is_client_uploaded' => false,
                ]);

                ActivityLog::log('Document Uploaded', 'Uploaded document "' . $document->title . '" for case ' . $case->case_number);

                $uploadedFilesInfo[] = $fileTitle . " (" . $fileExtension . ")";
            }

            // Telegram Notification
            try {
                $escapedName = htmlspecialchars(Auth::user()->name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                
                $filesStr = implode("\n", array_map(function($f) {
                    return "📄 " . htmlspecialchars($f, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                }, $uploadedFilesInfo));

                $telMsg = "📁 <b>New Case Documents Uploaded by Attorney/Admin</b>\n\n"
                        . "👤 <b>Uploader:</b> {$escapedName}\n"
                        . "🔢 <b>Case Number:</b> {$case->case_number}\n"
                        . "📄 <b>Documents:</b>\n{$filesStr}\n"
                        . "📅 <b>Time:</b> " . now()->format('Y-m-d H:i:s') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}

            return redirect()->back()->with('success', __('Documents uploaded successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroyDocument($doc_id)
    {
        try {
            $document = CaseDocument::findOrFail($doc_id);
            $case = $document->clientCase;

            if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            // Remove file from disk
            $filePath = public_path($document->file_path);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            ActivityLog::log('Document Deleted', 'Deleted document "' . $document->title . '" from case ' . $case->case_number);
            $document->delete();

            return redirect()->back()->with('success', __('Document deleted successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function previewDocument($doc_id)
    {
        try {
            $document = CaseDocument::findOrFail($doc_id);
            $case = $document->clientCase;

            // Restrict access
            if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            $filePath = public_path($document->file_path);
            if (!File::exists($filePath)) {
                abort(404, 'File not found on server.');
            }

            return response()->file($filePath);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function activityLogs()
    {
        try {
            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }

            $title = __('System Activity Logs');
            $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->paginate(50);

            return view('backend.pages.cases.logs', compact('title', 'logs'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }



    public function documentGenerator()
    {
        try {
            $title = __('Legal Document Builder');
            $clients = User::role('client')->orderBy('name', 'asc')->get();
            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');
            $templates = \App\Models\DocumentTemplate::where('type', 'client')->where('status', true)->orderBy('title', 'asc')->get();

            return view('backend.pages.cases.doc-generator', compact('title', 'clients', 'companyName', 'templates'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function generateDocument(Request $request)
    {
        $request->validate([
            'template_key' => 'required|string|exists:document_templates,key',
            'client_id' => 'required|exists:users,id',
            'custom_clauses' => 'nullable|string',
            'attorney_name' => 'nullable|string|max:255',
            'effective_date' => 'nullable|date',
            'send_email' => 'nullable|boolean',
        ]);

        try {
            $client = User::findOrFail($request->client_id);
            $template = \App\Models\DocumentTemplate::where('key', $request->template_key)->firstOrFail();
            
            $title = $template->title;
            $rawContent = $template->content;
            
            $dateStr = $request->effective_date ? \Carbon\Carbon::parse($request->effective_date)->format('F d, Y') : date('F d, Y');
            $attorneyName = $request->attorney_name ?: config('app.name', 'Your CPA Expert');
            
            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');

            // Find client case (if exists) for this client, to get case_number
            $clientCase = \App\Models\ClientCase::where('client_id', $client->id)->orderBy('created_at', 'desc')->first();
            $caseNumber = $clientCase ? $clientCase->case_number : 'N/A';

            // Replace client templates placeholders
            $placeholders = [
                '{{client_name}}' => $client->name,
                '{{client_email}}' => $client->email,
                '{{client_phone}}' => $client->phone ?: 'N/A',
                '{{client_address}}' => $client->address ?: 'N/A',
                '{{company_name}}' => $companyName,
                '{{date}}' => $dateStr,
                '{{attorney_name}}' => $attorneyName,
                '{{case_number}}' => $caseNumber,
            ];

            $content = str_replace(array_keys($placeholders), array_values($placeholders), $rawContent);

            if ($request->custom_clauses) {
                $content .= "
                    <div style='margin-top: 25px; border-top: 1px solid #ddd; padding-top: 15px;'>
                        <h4>" . __('Special Clauses & Custom Agreements:') . "</h4>
                        <p style='white-space: pre-line; background-color: #f8f9fa; padding: 12px; border-left: 3px solid #1e3c72; color: #333;'>" . e($request->custom_clauses) . "</p>
                    </div>
                ";
            }

            ActivityLog::log('Document Generated', 'Generated ' . $request->template_key . ' template for client ' . $client->name);

            // Optional email dispatch
            if ($request->send_email) {
                $subject = "Agreement Draft for Review: " . $title;
                
                $bodyText = "Hello " . $client->name . ",\n\n"
                    . "A legal document draft has been generated for your review by " . $companyName . ".\n\n"
                    . "Document Title: " . $title . "\n"
                    . "Effective Date: " . $dateStr . "\n"
                    . "Attorney/CPA: " . $attorneyName . "\n"
                    . "Client Name: " . $client->name . "\n"
                    . "Client Address: " . ($client->address ?: 'N/A') . "\n";
                if ($clientCase) {
                    $bodyText .= "Associated Case: Case #" . $clientCase->case_number . " - " . $clientCase->title . "\n";
                }
                $bodyText .= "\n"
                    . "--- CUSTOM AGREEMENT NOTE ---\n"
                    . ($request->custom_clauses ?: "No custom clauses added.") . "\n\n"
                    . "Please check the attached PDF for the full representation agreement details. "
                    . "You can view, print, or download this template directly inside your Client Dashboard. Please return a signed copy to us or upload it to your secure Document Vault.\n\n"
                    . "Best regards,\n"
                    . "Legal Operations Team\n"
                    . $companyName;

                $trackingToken = uniqid() . bin2hex(random_bytes(8));

                // Log document log
                \App\Models\DocumentLog::create([
                    'template_key' => $request->template_key,
                    'template_title' => $title,
                    'client_id' => $client->id,
                    'recipient_email' => $client->email,
                    'sent_by' => Auth::id(),
                    'sent_to_email' => true,
                    'status' => 'sent',
                    'tracking_token' => $trackingToken,
                ]);

                // Generate PDF representation of the document
                $pdfPath = null;
                try {
                    $isPdf = true;
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.pages.cases.doc-print', compact('title', 'content', 'client', 'companyName', 'dateStr', 'isPdf'));
                    $pdfPath = tempnam(sys_get_temp_dir(), 'doc_') . '.pdf';
                    $pdf->save($pdfPath);
                } catch (\Throwable $pdfError) {
                    // Fail silently
                }

                $attachmentName = str_replace(' ', '_', $title) . '.pdf';
                $this->sendEmailNotification($client->email, $subject, $bodyText, $pdfPath, $attachmentName, $trackingToken);

                if ($pdfPath && file_exists($pdfPath)) {
                    @unlink($pdfPath);
                }
                ActivityLog::log('Document Email Sent', 'Emailed generated ' . $request->template_key . ' agreement with PDF attachment to client ' . $client->name);
            } else {
                // Log generated document (but not sent to email)
                \App\Models\DocumentLog::create([
                    'template_key' => $request->template_key,
                    'template_title' => $title,
                    'client_id' => $client->id,
                    'recipient_email' => $client->email,
                    'sent_by' => Auth::id(),
                    'sent_to_email' => false,
                    'status' => 'generated',
                    'tracking_token' => uniqid() . bin2hex(random_bytes(8)),
                ]);
            }

            // Hide default print-view signatures since templates have custom ones
            $hideDefaultSignatures = true;
            return view('backend.pages.cases.doc-print', compact('title', 'content', 'client', 'companyName', 'dateStr', 'hideDefaultSignatures'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Add milestone to legal case
     */
    public function addMilestone(Request $request, $id)
    {
        $case = ClientCase::findOrFail($id);

        if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,active,completed',
            'milestone_date' => 'nullable|date',
        ]);

        try {
            \App\Models\CaseMilestone::create([
                'case_id' => $case->id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'milestone_date' => $request->milestone_date,
            ]);

            ActivityLog::log('Case Milestone Added', 'Added milestone "' . $request->title . '" to case ' . $case->case_number);

            return redirect()->back()->with('success', __('Case milestone added successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete milestone from legal case
     */
    public function destroyMilestone($milestone_id)
    {
        try {
            $milestone = \App\Models\CaseMilestone::findOrFail($milestone_id);
            $case = $milestone->clientCase;

            if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            ActivityLog::log('Case Milestone Deleted', 'Deleted milestone "' . $milestone->title . '" from case ' . $case->case_number);
            $milestone->delete();

            return redirect()->back()->with('success', __('Case milestone deleted successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
