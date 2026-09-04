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

            $title     = __('Edit Case #') . $case->case_number;
            $clients   = User::role('client')->orderBy('name', 'asc')->get();
            $attorneys = User::role(['admin', 'attorney'])->orderBy('name', 'asc')->get();
            $templates = \App\Models\DocumentTemplate::where('type', 'client')->where('status', true)->orderBy('title', 'asc')->get();

            return view('backend.pages.cases.form', compact('title', 'clients', 'attorneys', 'case', 'templates'));
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
            'title'             => 'nullable|string|max:255',
            'files'             => 'required|array',
            'files.*'           => 'file|mimes:pdf,png,jpg,jpeg,doc,docx,xlsx|max:20480',
            'document_type'     => 'nullable|string|max:100',
            'requires_signature'=> 'nullable|boolean',
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
                    'case_id'            => $case->id,
                    'user_id'            => Auth::id(),
                    'title'              => $fileTitle ?: 'Case Document',
                    'file_path'          => $newFilePath,
                    'file_type'          => $fileExtension,
                    'file_size'          => file_exists(public_path($newFilePath)) ? filesize(public_path($newFilePath)) : 0,
                    'is_client_uploaded' => false,
                    'document_type'      => $request->document_type ?: 'Standard / General Document',
                    'requires_signature' => (bool)$request->requires_signature,
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
            $title = __('Case Document Vault & Builder');
            $clients = User::role('client')->orderBy('name', 'asc')->get();
            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');
            $templates = \App\Models\DocumentTemplate::where('type', 'client')->where('status', true)->orderBy('title', 'asc')->get();

            $vaultedDocs = \App\Models\CaseDocument::with(['client', 'clientCase'])
                ->orderBy('id', 'desc')
                ->get();

            return view('backend.pages.cases.doc-generator', compact('title', 'clients', 'companyName', 'templates', 'vaultedDocs'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function generateDocument(Request $request)
    {
        try {
            // Handle Direct Vault File Upload (Tab 1)
            if ($request->get('action_type') === 'upload') {
                $request->validate([
                    'vault_file' => 'required|file|max:25600',
                    'client_id' => 'required|exists:users,id',
                    'doc_type' => 'nullable|string|max:100',
                ]);

                $file = $request->file('vault_file');
                $uploadPath = public_path('upload/case-documents');
                if (!\Illuminate\Support\Facades\File::exists($uploadPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($uploadPath, 0755, true);
                }

                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
                $file->move($uploadPath, $fileName);
                $filePath = 'upload/case-documents/' . $fileName;

                $clientCase = \App\Models\ClientCase::where('client_id', $request->client_id)->orderBy('id', 'desc')->first();

                \App\Models\CaseDocument::create([
                    'case_id' => $clientCase ? $clientCase->id : null,
                    'client_id' => $request->client_id,
                    'document_title' => $originalName,
                    'file_path' => $filePath,
                    'document_type' => $request->doc_type ?: 'Standard / General Document',
                    'requires_signature' => $request->has('requires_signature'),
                    'is_signed' => false,
                    'visibility' => 'client_visible',
                ]);

                ActivityLog::log('Document Uploaded to Vault', 'Uploaded ' . $originalName . ' for client #' . $request->client_id);
                return redirect()->route('admin.document-generator')->with('success', __('Document successfully uploaded and stored in the Case Document Vault.'));
            }

            // Handle Custom Document Composer (Tab 2)
            $request->validate([
                'client_id' => 'required|exists:users,id',
                'doc_title' => 'required|string|max:255',
                'document_content' => 'required|string',
            ]);

            $client = User::findOrFail($request->client_id);
            $title = $request->doc_title;
            $rawContent = $request->document_content;
            $docType = $request->doc_type ?: 'Service Agreement';

            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');

            $clientCase = \App\Models\ClientCase::where('client_id', $client->id)->orderBy('created_at', 'desc')->first();
            $caseNumber = $clientCase ? $clientCase->case_number : 'CASE-' . sprintf('%05d', $client->id);
            $dateStr = date('F d, Y');

            // Replace client templates placeholders
            $placeholders = [
                '{{client_name}}' => $client->name,
                '@{{client_name}}' => $client->name,
                '{{client_email}}' => $client->email,
                '@{{client_email}}' => $client->email,
                '{{client_phone}}' => $client->phone ?: 'N/A',
                '@{{client_phone}}' => $client->phone ?: 'N/A',
                '{{client_address}}' => $client->address ?: 'N/A',
                '@{{client_address}}' => $client->address ?: 'N/A',
                '{{company_name}}' => $companyName,
                '@{{company_name}}' => $companyName,
                '{{date}}' => $dateStr,
                '@{{date}}' => $dateStr,
                '{{attorney_name}}' => $companyName,
                '@{{attorney_name}}' => $companyName,
                '{{case_number}}' => $caseNumber,
                '@{{case_number}}' => $caseNumber,
            ];

            $content = str_replace(array_keys($placeholders), array_values($placeholders), $rawContent);

            // Vault in CaseDocument
            $vaultDoc = \App\Models\CaseDocument::create([
                'case_id' => $clientCase ? $clientCase->id : null,
                'client_id' => $client->id,
                'document_title' => $title,
                'file_path' => '',
                'document_type' => $docType,
                'custom_content' => $content,
                'requires_signature' => $request->has('requires_signature'),
                'is_signed' => false,
                'visibility' => 'client_visible',
            ]);

            ActivityLog::log('Custom Document Composed', 'Created and vaulted document: ' . $title . ' for client ' . $client->name);

            // Generate PDF with Ashish Master letterhead, signatures & stamp
            $isPdf = true;
            $attorneyName = ($client && $client->assignedAttorney) ? $client->assignedAttorney->name : (Auth::user() ? Auth::user()->name : 'Gerald W. Allen, Esq.');
            $attorneyTitle = 'Senior Lead Counsel & Practice Director';
            $caseNumber = $clientCase ? $clientCase->case_number : 'YCE-' . date('Y');
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.pages.cases.doc-print', compact('title', 'content', 'client', 'companyName', 'dateStr', 'isPdf', 'attorneyName', 'attorneyTitle', 'caseNumber'));

            $uploadPath = public_path('upload/case-documents');
            if (!\Illuminate\Support\Facades\File::exists($uploadPath)) {
                \Illuminate\Support\Facades\File::makeDirectory($uploadPath, 0755, true);
            }
            $pdfFileName = 'doc_' . time() . '_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $title) . '.pdf';
            $pdfFullPath = $uploadPath . '/' . $pdfFileName;
            $pdf->save($pdfFullPath);

            $vaultDoc->file_path = 'upload/case-documents/' . $pdfFileName;
            $vaultDoc->save();

            // Email dispatch if requested
            if ($request->has('send_email_copy')) {
                try {
                    $subject = "Legal Document Draft: " . $title;
                    $bodyText = "Hello " . $client->name . ",\n\n"
                        . "A legal document draft has been prepared and vaulted for your review by " . $companyName . ".\n\n"
                        . "Document: " . $title . "\n"
                        . "Type: " . $docType . "\n"
                        . "Date: " . $dateStr . "\n\n"
                        . "Please review the attached official document copy or access it via your secure client dashboard.\n\n"
                        . "Best regards,\n" . $companyName;

                    $this->sendEmailNotification($client->email, $subject, $bodyText, $pdfFullPath, $pdfFileName);
                } catch (\Throwable $emailErr) {}
            }

            return $pdf->stream($pdfFileName);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Generate and store a custom document directly into the case vault
     */
    public function generateDocumentForCase(Request $request, $id)
    {
        $request->validate([
            'doc_title'          => 'required|string|max:255',
            'doc_type_custom'    => 'nullable|string|max:100',
            'doc_content'        => 'required|string',
            'requires_signature' => 'nullable|boolean',
            'template_key'       => 'nullable|string|exists:document_templates,key',
        ]);

        try {
            $case   = ClientCase::with('client')->findOrFail($id);
            $client = $case->client;

            if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            $content = $request->doc_content;

            // Substitute client placeholders if template was used
            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');
            $clientCase = $case;
            $caseNumber = $clientCase->case_number;

            if ($client) {
                $placeholders = [
                    '{{client_name}}'    => $client->name,
                    '{{client_email}}'   => $client->email,
                    '{{client_phone}}'   => $client->phone ?: 'N/A',
                    '{{client_address}}' => $client->address ?: 'N/A',
                    '{{company_name}}'   => $companyName,
                    '{{date}}'           => now()->format('F d, Y'),
                    '{{case_number}}'    => $caseNumber,
                ];
                $content = str_replace(array_keys($placeholders), array_values($placeholders), $content);
            }

            // Save as a vault document (no physical file – custom_content stores HTML)
            $doc = CaseDocument::create([
                'case_id'            => $case->id,
                'user_id'            => Auth::id(),
                'title'              => $request->doc_title,
                'file_path'          => null,
                'file_type'          => 'custom',
                'file_size'          => 0,
                'is_client_uploaded' => false,
                'document_type'      => $request->doc_type_custom ?: 'Custom Document',
                'requires_signature' => (bool)$request->requires_signature,
                'custom_content'     => $content,
                'visibility'         => 'client_visible',
            ]);

            ActivityLog::log('Custom Document Created', 'Created custom document "' . $doc->title . '" for case ' . $case->case_number);

            return redirect()->back()->with('success', __('Custom document created and added to vault successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * View a custom-generated document (HTML) from the vault
     */
    public function viewCustomDocument($doc_id)
    {
        try {
            $document = CaseDocument::with('clientCase')->findOrFail($doc_id);
            $case = $document->clientCase;

            if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            if ($document->file_type !== 'custom' || empty($document->custom_content)) {
                abort(404, 'Custom document content not found.');
            }

            return response()->view('backend.pages.cases.view-custom-doc', [
                'document' => $document,
                'case'     => $case,
                'content'  => $document->custom_content,
            ]);
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
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'status'         => 'required|in:pending,active,completed',
            'milestone_date' => 'nullable|date',
            'visibility'     => 'nullable|in:client_visible,internal',
        ]);

        try {
            \App\Models\CaseMilestone::create([
                'case_id'        => $case->id,
                'title'          => $request->title,
                'description'    => $request->description,
                'status'         => $request->status,
                'milestone_date' => $request->milestone_date,
                'visibility'     => $request->visibility ?: 'client_visible',
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
