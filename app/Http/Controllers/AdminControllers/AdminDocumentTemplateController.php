<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminDocumentTemplateController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('backend.pages.document-templates.index', [
                'title' => 'Document Templates',
                'templates' => DocumentTemplate::orderBy('type', 'asc')->orderBy('title', 'asc')->get()
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('backend.pages.document-templates.form', [
                'title' => 'Add New Document Template',
                'template' => null
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = [
            'key' => ['required', 'string', 'max:255', 'unique:document_templates'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:client,staff'],
            'content' => ['required', 'string'],
        ];

        $validator = Validator::make($request->all(), $validate);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DocumentTemplate::create([
                'key' => strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $request->key)),
                'title' => $request->title,
                'type' => $request->type,
                'content' => $request->content,
                'status' => $request->has('status')
            ]);

            return redirect()->route('admin.document-templates.index')->with([
                'message' => 'Document Template created successfully.',
                'alert-type' => 'success'
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $template = DocumentTemplate::findOrFail($id);
            return view('backend.pages.document-templates.form', [
                'title' => 'Edit Document Template',
                'template' => $template
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $template = DocumentTemplate::findOrFail($id);

        $validate = [
            'key' => ['required', 'string', 'max:255', 'unique:document_templates,key,' . $template->id],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:client,staff'],
            'content' => ['required', 'string'],
        ];

        $validator = Validator::make($request->all(), $validate);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $template->update([
                'key' => strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $request->key)),
                'title' => $request->title,
                'type' => $request->type,
                'content' => $request->content,
                'status' => $request->has('status')
            ]);

            return redirect()->route('admin.document-templates.index')->with([
                'message' => 'Document Template updated successfully.',
                'alert-type' => 'success'
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $template = DocumentTemplate::findOrFail($id);
            $template->delete();

            return redirect()->route('admin.document-templates.index')->with([
                'message' => 'Document Template deleted successfully.',
                'alert-type' => 'success'
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display document sent and tracking history.
     */
    public function sentHistory()
    {
        try {
            return view('backend.pages.document-templates.history', [
                'title' => 'Document Sent & Tracking History',
                'logs' => \App\Models\DocumentLog::with(['client', 'staff', 'sender'])->orderBy('created_at', 'desc')->get()
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Preview a document template with sample placeholders.
     */
    public function preview($id)
    {
        try {
            $template = DocumentTemplate::findOrFail($id);
            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');

            $contactPage = \App\Models\PageSettings::where('name', 'contact')->first();
            $contactInfo = $contactPage ? $contactPage->sections()->where('name', 'contact_info')->first() : null;
            $emailInfo = $contactPage ? $contactPage->sections()->where('name', 'email')->first() : null;
            
            $companyAddress = env('COMPANY_ADDRESS') ?: ($contactInfo ? implode(', ', array_filter([$contactInfo->line_one, $contactInfo->line_two])) : '582 Professional Way, Financial District, DC');
            $companyPhone = env('COMPANY_PHONE') ?: ($contactInfo && $contactInfo->line_two && preg_match('/[0-9]/', $contactInfo->line_two) ? $contactInfo->line_two : '(216) 230-1837');
            $companyEmail = env('COMPANY_EMAIL') ?: ($emailInfo ? $emailInfo->line_one : 'support@yourcpaexpert.com');

            $placeholders = [
                '{{client_name}}' => 'John Doe (Sample Client)',
                '{{client_email}}' => 'john.doe@example.com',
                '{{client_phone}}' => '(555) 019-2834',
                '{{client_address}}' => '123 Prosperity Way, Suite 100, New York, NY 10001',
                '{{employee_name}}' => 'Sarah Jenkins (Sample Employee)',
                '{{employee_email}}' => 'sarah.jenkins@example.com',
                '{{employee_phone}}' => '(555) 014-9821',
                '{{employee_address}}' => '456 Synergy Blvd, Austin, TX 78701',
                '{{staff_id}}' => 'STF-88902',
                '{{company_name}}' => $companyName,
                '{{date}}' => date('F d, Y'),
                '{{attorney_name}}' => 'Founding Attorney Gerald W. Allen',
                '{{case_number}}' => 'CAS-2026-0042',
            ];

            $content = str_replace(array_keys($placeholders), array_values($placeholders), $template->content);

            // Fetch list of users/clients to populate the drop-down on the preview page
            $users = \App\Models\User::orderBy('name', 'asc')->get();

            return view('backend.pages.document-templates.preview', [
                'title' => 'Preview & Test Template: ' . $template->title,
                'template' => $template,
                'content' => $content,
                'users' => $users,
                'companyName' => $companyName,
                'companyAddress' => $companyAddress,
                'companyPhone' => $companyPhone,
                'companyEmail' => $companyEmail,
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Send a template document via email to a client/recipient.
     */
    public function sendTestEmail(Request $request, $id)
    {
        $request->validate([
            'recipient_email' => 'required|email',
            'user_id' => 'nullable|exists:users,id',
        ]);

        try {
            $template = DocumentTemplate::findOrFail($id);
            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');

            $user = null;
            if ($request->filled('user_id')) {
                $user = \App\Models\User::find($request->user_id);
            }

            // Populate recipient details based on selected user or form input
            $name = $user ? $user->name : 'Valued Member';
            $email = $user ? $user->email : $request->recipient_email;
            $phone = $user ? $user->phone : '(555) 019-2834';
            $address = $user ? $user->address : '123 Prosperity Way, Suite 100, New York, NY 10001';

            // Find client case (if exists) for this client, to get case_number and attorney
            $caseNumber = 'N/A';
            $attorneyName = 'Gerald W. Allen';
            if ($user) {
                $clientCase = \App\Models\ClientCase::where('client_id', $user->id)->orderBy('created_at', 'desc')->first();
                if ($clientCase) {
                    $caseNumber = $clientCase->case_number;
                    if ($clientCase->attorney) {
                        $attorneyName = $clientCase->attorney->name;
                    }
                }
            }

            $placeholders = [
                '{{client_name}}' => $name,
                '{{client_email}}' => $email,
                '{{client_phone}}' => $phone ?: 'N/A',
                '{{client_address}}' => $address ?: 'N/A',
                '{{employee_name}}' => $name,
                '{{employee_email}}' => $email,
                '{{employee_phone}}' => $phone ?: 'N/A',
                '{{employee_address}}' => $address ?: 'N/A',
                '{{staff_id}}' => ($user && $user->staffDetail) ? $user->staffDetail->staff_id : 'STF-88902',
                '{{company_name}}' => $companyName,
                '{{date}}' => date('F d, Y'),
                '{{attorney_name}}' => $attorneyName,
                '{{case_number}}' => $caseNumber,
            ];

            $content = str_replace(array_keys($placeholders), array_values($placeholders), $template->content);

            $subject = "Agreement Document: " . $template->title;

            $bodyText = "Hello " . $name . ",\n\n"
                . "We have generated the following document for your review:\n\n"
                . "Document Title: " . $template->title . "\n"
                . "Date: " . date('F d, Y') . "\n"
                . "Issuer: " . $companyName . "\n";
            if ($user) {
                $bodyText .= "Recipient Name: " . $name . "\n"
                    . "Recipient Email: " . $email . "\n";
            }
            $bodyText .= "\n"
                . "Please review the attached PDF for full details. "
                . "You can view, print, or download this template directly inside your Secure Dashboard.\n\n"
                . "Best regards,\n"
                . "Operations Team\n"
                . $companyName;

            $trackingToken = uniqid() . bin2hex(random_bytes(8));

            // Generate PDF representation of the document
            $pdfPath = null;
            $attachmentName = null;
            try {
                $pdfPath = storage_path('app/temp_' . uniqid() . '.pdf');
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.pages.cases.doc-print', [
                    'title' => $template->title,
                    'content' => $content,
                    'client' => $user ?: (object)['name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address],
                    'companyName' => $companyName,
                    'dateStr' => date('F d, Y'),
                    'hideDefaultSignatures' => true,
                    'isPdf' => true
                ]);
                $pdf->save($pdfPath);
                $attachmentName = str_replace(' ', '_', $template->title) . '.pdf';
            } catch (\Throwable $pdfError) {
                $pdfPath = null;
            }

            // Log document log
            \App\Models\DocumentLog::create([
                'template_key' => $template->key,
                'template_title' => $template->title,
                'client_id' => ($user && $template->type == 'client') ? $user->id : null,
                'staff_id' => ($user && $template->type == 'staff') ? $user->id : null,
                'recipient_email' => $email,
                'sent_by' => \Illuminate\Support\Facades\Auth::id(),
                'sent_to_email' => true,
                'status' => 'sent',
                'tracking_token' => $trackingToken,
            ]);

            $this->sendEmailNotification($email, $subject, $bodyText, $pdfPath, $attachmentName, $trackingToken);

            if ($pdfPath && file_exists($pdfPath)) {
                @unlink($pdfPath);
            }

            return redirect()->back()->with([
                'message' => 'Document template email successfully dispatched to ' . $email,
                'alert-type' => 'success'
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
