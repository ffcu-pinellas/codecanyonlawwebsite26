<?php

namespace App\Http\Controllers\ClientViewControllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Attorney;
use App\Models\Conversation;
use App\Models\ReliefRequest;
use App\Models\Message;
use App\Models\PageSectionSettings;
use App\Models\PageSettings;
use App\Models\User;
use App\Models\ClientCase;
use App\Models\CaseDocument;
use App\Models\Invoice;
use App\Models\DocumentTemplate;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ClientViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Parent::__construct();
    }

    public function dashboard()
    {
        $casesCount = 0;
        $latestCases = [];
        $recentAppointments = [];
        $conversationCount = 0;
        $unreadMessageCount = 0;
        $title = __('Dashboard');
        $page = null;
        $pageContent = null;

        try {
            $page = PageSettings::where('name', 'client_dashboard')->first();
            if (!empty($page)) {
                $pageContent = PageSectionSettings::where('name', 'client_dashboard_breadcumb_bg_img')->first();
                if (!empty($pageContent)) {
                    $title = ucfirst(clean($pageContent->title));
                } else {
                    $title = ucfirst(clean($page->name));
                }
            }

            try { $casesCount = Auth::user()->clientCases()->count(); } catch (\Throwable $e) {}
            try { $latestCases = Auth::user()->clientCases()->latest()->take(5)->get(); } catch (\Throwable $e) {}
            try { $recentAppointments = Appointment::where('email', Auth::user()->email)->latest()->take(5)->get(); } catch (\Throwable $e) {}
            try { $conversationCount = Auth::user()->conversation()->count(); } catch (\Throwable $e) {}
            
            try {
                $conversations = Auth::user()->conversation;
                foreach ($conversations as $conversation) {
                    $count = $conversation->messages()
                        ->where('read', false)
                        ->where('user_id', '!=', Auth::user()->id)
                        ->count();
                    $unreadMessageCount += $count;
                }
            } catch (\Throwable $e) {}

        } catch (\Throwable $th) {
            // Log main errors if needed
        }

        return view('frontend.theme1.auth-client.pages.dashboard', compact('title', 'page', 'pageContent', 'casesCount', 'latestCases', 'recentAppointments', 'conversationCount', 'unreadMessageCount'));
    }

    public function profile()
    {
        try {
            $title = 'My profile';
            $user = Auth::user();
            $photo = $user->profile_photo_path;
            return view('frontend.theme1.auth-client.pages.profile.show', compact('title','user','photo'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function profileUpdate(Request $request)
    {
        try {
            $input = $request->all();
            if ($request->hasFile('photo')) {
                $input['photo'] = $request->photo;
            }
            $user = Auth::user();

            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
                'phone' => ['required', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
                'address' => ['required', 'string'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            ])->validateWithBag('updateProfileInformation');

            if (isset($input['photo'])) {
                $user->updateProfilePhoto($input['photo']);
            }

            if (
                $input['email'] !== $user->email &&
                $user instanceof MustVerifyEmail
            ) {
                $this->updateVerifiedUser($user, $input);
            } else {
                $user->forceFill([
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'phone' => $input['phone'],
                    'address' => $input['address'],
                ])->save();
            }


            return $this->backWithSuccess($user->name . '\'s personal information has been updated successfully');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    protected function updateVerifiedUser($user, array $input)
    {
        try {
            $user->forceFill([
                'name' => $input['name'],
                'phone' => $input['phone'],
                'email' => $input['email'],
                'email_verified_at' => null,
            ])->save();

            //            $user->sendEmailVerificationNotification();
            $notification = array(
                'message' => $user->name . '\'s personal information has been updated successfully',
                'alert-type' => 'success'
            );
            return back()->with($notification);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function createReliefRequest()
    {
        try {
            $title = 'Request CPA / Legal Assistance';
            return view('frontend.theme1.auth-client.pages.relief-requests.form', compact('title'));
        } catch (\Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function storeReliefRequest(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'files' => ['required', 'array'],
            'files.*' => ['file', 'mimes:pdf,docx,doc,jpeg,png,jpg,xlsx', 'max:20480'],
            'reason' => ['required', 'string'],
            'offer' => ['required', 'string'],
        ]);

        try {
            // Generate unique Case Number
            do {
                $caseNumber = 'CS-' . rand(100000, 999999);
            } while (ClientCase::where('case_number', $caseNumber)->exists());

            // Create case directly
            $case = ClientCase::create([
                'case_number' => $caseNumber,
                'title' => $request->reason,
                'description' => "Client Name: " . $request->name . "\nPhone: " . $request->phone . "\nEmail: " . $request->email . "\nAddress: " . $request->address . "\n\nProposed Resolution / Target Goal:\n" . $request->offer . "\n\nAdditional Background & Details:\n" . ($request->details ?: ''),
                'client_id' => Auth::user()->id,
                'status' => 'pending',
            ]);

            // Save files to secure vault
            $allUploadedFilesInfo = [];
            if ($request->hasFile('files')) {
                $uploadPath = public_path('upload/case-documents');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                foreach ($request->file('files') as $file) {
                    $fileExtension = $file->getClientOriginalExtension();
                    $newFileName = time() . '_' . uniqid() . '.' . $fileExtension;
                    $file->move($uploadPath, $newFileName);
                    $newFilePath = '/upload/case-documents/' . $newFileName;

                    $title = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                    CaseDocument::create([
                        'case_id' => $case->id,
                        'user_id' => Auth::user()->id,
                        'title' => $title ?: 'Intake Notice Document',
                        'file_path' => $newFilePath,
                        'file_type' => $fileExtension,
                        'file_size' => file_exists(public_path($newFilePath)) ? filesize(public_path($newFilePath)) : 0,
                        'is_client_uploaded' => true,
                    ]);

                    $allUploadedFilesInfo[] = $file->getClientOriginalName() . " (" . round(filesize(public_path($newFilePath)) / 1024, 2) . " KB)";
                }
            }

            \App\Models\ActivityLog::log('Case Created', 'Client ' . Auth::user()->name . ' submitted a new case #' . $case->case_number);
            
            // Try to notify Slack if the method exists on controller or helper
            try {
                $this->sendSlackNotification('📂 New case representation opened directly. Case Number: ' . $case->case_number);
            } catch (\Throwable $e) {}

            // Telegram Notification
            try {
                $escapedName = htmlspecialchars(Auth::user()->name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedEmail = htmlspecialchars(Auth::user()->email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedTitle = htmlspecialchars($case->title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                
                $filesStr = implode("\n", array_map(function($f) {
                    return "📄 " . htmlspecialchars($f, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                }, $allUploadedFilesInfo));

                $telMsg = "📂 <b>New Case Representation Opened</b>\n\n"
                        . "👤 <b>Client:</b> {$escapedName}\n"
                        . "📧 <b>Email:</b> {$escapedEmail}\n"
                        . "🔢 <b>Case Number:</b> {$case->case_number}\n"
                        . "💼 <b>Case Title:</b> {$escapedTitle}\n"
                        . "📁 <b>Uploaded Files:</b>\n{$filesStr}\n"
                        . "📅 <b>Time:</b> " . now()->format('Y-m-d H:i:s') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}

            return redirect()->route('client.cases.index')->with('success', __('Case representation initialized successfully. Case Number: ') . $case->case_number);
        } catch (\Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function getConversation($attorneys = [])
    {
        try {
            $title = 'conversations';
            $conversations = $this->conversationView(Auth::user()->conversation);

            $autoSuggestions = Attorney::all('name');
            return view('frontend.theme1.auth-client.pages.chat.index', compact('title', 'conversations', 'autoSuggestions', 'attorneys'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function searchAttorney(Request $request)
    {
        try {
            $attorneys = Attorney::where('name', 'LIKE', '%' . $request->search . '%')
                ->get();
            return $this->getConversation($attorneys);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function createConversation(User $user)
    {
        try {
            $host = Auth::user();
            $conversations = $host->conversation;
            if ($conversations->count() != 0) {
                foreach ($conversations as $conversation) {
                    if ($conversation->user->count() == 2) {
                        $matchCount = 0;
                        foreach ($conversation->user as $person) {
                            if ($person->id === $host->id) {
                                $matchCount += 1;
                            } elseif ($person->id === $user->id) {
                                $matchCount += 1;
                            }
                        }
                        if ($matchCount === 2) {
                            return $this->getMessage($conversation->slug);
                        }
                    }
                }
                //                return $this->backWithError('No conversation is not match....');
            }
            $conversation = new Conversation();
            $conversation->name = $host->name . ' vs ' . $user->name;
            $conversation->slug = time() . str_replace(' ', '_', $host->name) . 'vs' . str_replace(' ', '_', $user->name);
            $conversation->save();
            $conversation->user()->sync([$host->id, $user->id]);

            $persons = explode('vs', $conversation->name);
            $title = '';
            foreach ($persons as $key => $person) {
                if ($person != Auth::user()->name) {
                    $title = $title . $person;
                    if (count($persons) != ($key + 1)) {
                        $title = $title . ', ';
                    }
                }
            }

            return view('frontend.theme1.auth-client.pages.chat.messages', compact('title', 'conversation'));
        } catch (\Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function conversationView($conversations)
    {
        try {
            $row = [];
            foreach ($conversations as $conversation) {
                $agent = [];
                foreach ($conversation->user as $person) {
                    if ($person->id !== Auth::user()->id) {
                        $agent = $person;
                    }
                }
                $lastMessage = $conversation->messages()->orderBy('id', 'DESC')->first();
                $attorney = $agent->attorney;
                $row[] = '<a href="' . route('client.conversation.get-conversation', $conversation->slug) . '">
                        <div class="card mb-1">
                            <div class="card-header">
                                <img src="' . asset('upload/attorneys/' . $attorney->image) . '" alt="" class="img-fluid img-thumbnail chat-attorney-Search">
                                ' . $attorney->name . '
                                 <span class="float-right text-right">' . date('D-M-y', strtotime($conversation->updated_at)) . '</br>' . date('H:i', strtotime($conversation->updated_at)) . '</span>
                            </div>
                        </div>
                    </a>';
            }
            $output = implode(' ', $row);
            return $output;
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getMessage($slug)
    {
        try {
            $conversation = Conversation::where('slug', $slug)->first();
            $title = '';
            foreach ($conversation->user as $person) {
                if ($person->id !== Auth::user()->id) {
                    $title = $title . $person->name . ' ';
                }
            }

            $unreadMessage = $conversation->messages()
                ->where('read', false)
                ->where('user_id', '!=', Auth::user()->id)
                ->get();
            $unreadMessage->each->update(['read' => true]);

            return view('frontend.theme1.auth-client.pages.chat.messages', compact('title', 'conversation'));
        } catch (\Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function sendMessage(Request $request, $slug)
    {
        try {
            $conversation = Conversation::where('slug', $slug)->first();
            $message = new Message();
            $message->conversation_id = $conversation->id;
            $message->user_id = Auth::user()->id;
            $message->text = $request->text;
            if ($request->hasFile('file')) {
                $request->validate([
                    'file' => ['file', 'mimes:pdf,docx,doc,jpeg,png,jpg,txt', 'max:20480']
                ]);
                $message->file_name = $request->file->getClientOriginalName();
                $fileName = time() . '_' . uniqid() . '.' . $request->file->getClientOriginalExtension();
                $request->file->move(public_path('/upload/message-files'), $fileName);
                $message->file = '/upload/message-files/' . $fileName;
                $message->save();
                $conversation->save();
            } else {
                if ($message->text) {
                    $message->save();
                    $conversation->save();
                }
            }

            // Notify participants
            if ($message->id) {
                foreach ($conversation->user as $participant) {
                    if ($participant->id !== Auth::user()->id) {
                        $participant->notify(new \App\Notifications\MessageNotification($message));
                    }
                }

                // Telegram Notification
                try {
                    $escapedName = htmlspecialchars(Auth::user()->name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $messageText = $message->text ? \Illuminate\Support\Str::limit($message->text, 100) : '';
                    $escapedText = htmlspecialchars($messageText, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $attachmentInfo = $message->file_name ? "📎 " . htmlspecialchars($message->file_name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : 'None';
                    
                    $telMsg = "💬 <b>New Client Message Sent</b>\n\n"
                            . "👤 <b>Client:</b> {$escapedName}\n"
                            . "✉️ <b>Message:</b> " . ($escapedText ?: '<i>[Empty or Attachment only]</i>') . "\n"
                            . "📄 <b>Attachment:</b> {$attachmentInfo}\n"
                            . "📅 <b>Time:</b> " . now()->format('Y-m-d H:i:s') . "\n";
                    \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
                } catch (\Throwable $e) {}
            }

            return back();
        } catch (\Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function casesIndex()
    {
        try {
            $title = __('My Cases & Vault');
            $cases = Auth::user()->clientCases()->with(['attorney'])->orderBy('created_at', 'desc')->get();
            $pendingRequests = Auth::user()->reliefRequests()->orderBy('created_at', 'desc')->get();

            return view('frontend.theme1.auth-client.pages.cases.index', compact('title', 'cases', 'pendingRequests'));
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    public function caseDetails($id)
    {
        try {
            $case = ClientCase::with(['attorney', 'documents.uploader', 'milestones' => function($q) {
                $q->orderBy('milestone_date', 'asc')->orderBy('created_at', 'asc');
            }])->findOrFail($id);

            if ($case->client_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            $title = __('Case Details: #') . $case->case_number;
            return view('frontend.theme1.auth-client.pages.cases.details', compact('title', 'case'));
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    public function uploadCaseDocument(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'files' => 'required|array',
            'files.*' => 'file|mimes:pdf,png,jpg,jpeg,doc,docx,xlsx|max:20480',
        ]);

        try {
            $case = ClientCase::findOrFail($id);

            if ($case->client_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

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

                CaseDocument::create([
                    'case_id' => $case->id,
                    'user_id' => Auth::id(),
                    'title' => $fileTitle ?: 'Case Document',
                    'file_path' => $newFilePath,
                    'file_type' => $fileExtension,
                    'file_size' => file_exists(public_path($newFilePath)) ? filesize(public_path($newFilePath)) : 0,
                    'is_client_uploaded' => true,
                ]);

                \App\Models\ActivityLog::log('Client Document Uploaded', 'Client ' . Auth::user()->name . ' uploaded "' . $fileTitle . '" for case ' . $case->case_number);

                $uploadedFilesInfo[] = $fileTitle . " (" . $fileExtension . ")";
            }

            $this->sendSlackNotification('📁 New Documents Uploaded to Vault by Client ' . Auth::user()->name . ' for Case: ' . $case->case_number);

            // Telegram Notification
            try {
                $escapedName = htmlspecialchars(Auth::user()->name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                
                $filesStr = implode("\n", array_map(function($f) {
                    return "📄 " . htmlspecialchars($f, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                }, $uploadedFilesInfo));

                $telMsg = "📁 <b>New Document(s) Uploaded to Vault</b>\n\n"
                        . "👤 <b>Client:</b> {$escapedName}\n"
                        . "🔢 <b>Case Number:</b> {$case->case_number}\n"
                        . "📄 <b>Documents:</b>\n{$filesStr}\n"
                        . "📅 <b>Time:</b> " . now()->format('Y-m-d H:i:s') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}

            return redirect()->back()->with('success', __('Documents uploaded to secure vault successfully.'));
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    public function previewDocument($id)
    {
        try {
            $document = CaseDocument::findOrFail($id);
            $case = $document->clientCase;

            if ($case->client_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            $filePath = public_path($document->file_path);
            if (!File::exists($filePath)) {
                abort(404, 'File not found on server.');
            }

            return response()->file($filePath);
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    public function downloadDocument($id)
    {
        try {
            $document = CaseDocument::findOrFail($id);
            $case = $document->clientCase;

            if ($case->client_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            $filePath = public_path($document->file_path);
            if (!File::exists($filePath)) {
                abort(404, 'File not found on server.');
            }

            return response()->download($filePath);
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    public function invoicesIndex()
    {
        try {
            $title = __('My Invoices');
            $invoices = Auth::user()->invoices()->with(['clientCase'])->orderBy('created_at', 'desc')->get();

            return view('frontend.theme1.auth-client.pages.invoices.index', compact('title', 'invoices'));
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    public function invoiceShow($id)
    {
        try {
            $invoice = Invoice::with(['clientCase.attorney'])->findOrFail($id);

            if ($invoice->client_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            $title = __('Invoice #') . $invoice->invoice_number;
            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');

            // Find contact details for invoice header
            $contactPage = \App\Models\PageSettings::where('name', 'contact')->first();
            $contactInfo = $contactPage ? $contactPage->sections()->where('name', 'contact_info')->first() : null;
            $emailInfo = $contactPage ? $contactPage->sections()->where('name', 'email')->first() : null;

            $companyAddress = env('COMPANY_ADDRESS') ?: ($contactInfo ? implode(', ', array_filter([$contactInfo->line_one, $contactInfo->line_two])) : '582 Professional Way, Financial District, DC');
            $companyPhone = env('COMPANY_PHONE') ?: ($contactInfo && $contactInfo->line_two && preg_match('/[0-9]/', $contactInfo->line_two) ? $contactInfo->line_two : '(216) 230-1837');
            $companyEmail = env('COMPANY_EMAIL') ?: ($emailInfo ? $emailInfo->line_one : 'support@yourcpaexpert.com');

            return view('frontend.theme1.auth-client.pages.invoices.details', compact('title', 'invoice', 'companyName', 'companyAddress', 'companyPhone', 'companyEmail'));
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    public function submitPaymentProof(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|string|max:255',
            'payment_reference' => 'nullable|string|max:255',
            'payment_slip' => 'required|file|mimes:pdf,png,jpg,jpeg|max:10240',
            'payment_notes' => 'nullable|string',
        ]);

        try {
            $invoice = Invoice::findOrFail($id);

            if ($invoice->client_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            if ($invoice->status !== 'unpaid') {
                return redirect()->back()->with('error', __('Only unpaid invoices can accept payment proofs.'));
            }

            $fileName = time() . '_' . uniqid() . '.' . $request->payment_slip->getClientOriginalExtension();
            $uploadPath = public_path('upload/payment-slips');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $request->payment_slip->move($uploadPath, $fileName);

            $invoice->update([
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_reference' => $request->payment_reference,
                'payment_slip_path' => '/upload/payment-slips/' . $fileName,
                'payment_notes' => $request->payment_notes,
                'payment_submitted_at' => now(),
            ]);

            \App\Models\ActivityLog::log('Payment Proof Submitted', 'Client ' . Auth::user()->name . ' submitted payment proof for invoice ' . $invoice->invoice_number);
            $this->sendSlackNotification('💸 Offline Payment Proof Submitted by Client ' . Auth::user()->name . ' for Invoice: ' . $invoice->invoice_number);

            // Telegram Notification
            try {
                $escapedName = htmlspecialchars(Auth::user()->name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedMethod = htmlspecialchars($request->payment_method, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedRef = htmlspecialchars($request->payment_reference ?: 'N/A', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedNotes = htmlspecialchars($request->payment_notes ?: 'N/A', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                $telMsg = "💸 <b>Offline Payment Proof Submitted</b>\n\n"
                        . "👤 <b>Client:</b> {$escapedName}\n"
                        . "🧾 <b>Invoice:</b> {$invoice->invoice_number}\n"
                        . "💰 <b>Amount:</b> $" . number_format($invoice->amount, 2) . "\n"
                        . "💳 <b>Method:</b> {$escapedMethod}\n"
                        . "🆔 <b>Reference:</b> {$escapedRef}\n"
                        . "📝 <b>Notes:</b> {$escapedNotes}\n"
                        . "📅 <b>Time:</b> " . now()->format('Y-m-d H:i:s') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}

            return redirect()->back()->with('success', __('Payment proof submitted successfully and is awaiting review.'));
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    /**
     * Client Document Center
     */
    public function documentCenter()
    {
        try {
            $title = __('Client Document Center');
            $user = Auth::user();
            $documents = \App\Models\DocumentLog::where('client_id', $user->id)
                ->whereNotNull('content')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('frontend.theme1.auth-client.pages.documents.index', compact('title', 'documents'));
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    /**
     * Preview and print client documents
     */
    public function viewDocument($id)
    {
        try {
            $user = Auth::user();
            $document = \App\Models\DocumentLog::where('client_id', $user->id)
                ->where('id', $id)
                ->firstOrFail();

            $title = $document->template_title;
            $content = $document->content;

            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');

            // Log document view status if it was sent/not yet viewed
            if ($document->status === 'sent') {
                $document->update([
                    'status' => 'viewed',
                    'opened_at' => now(),
                ]);
            }

            return view('frontend.theme1.auth-client.pages.documents.print', compact('title', 'content', 'user', 'companyName'));
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    /**
     * Approve document log
     */
    public function approveDocument(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $document = \App\Models\DocumentLog::where('client_id', $user->id)
                ->where('id', $id)
                ->firstOrFail();

            if ($document->action_required !== 'approve') {
                return redirect()->back()->with([
                    'message' => 'No approval required for this document.',
                    'alert-type' => 'warning'
                ]);
            }

            $recipientNotes = $request->recipient_notes;

            $document->update([
                'status' => 'approved',
                'recipient_notes' => $recipientNotes
            ]);

            // Notify Admin via Email and Telegram
            $this->notifyAdminOfDocumentAction($document, 'approved', $recipientNotes);

            return redirect()->back()->with([
                'message' => 'Document approved successfully.',
                'alert-type' => 'success'
            ]);
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    /**
     * Reject document log
     */
    public function rejectDocument(Request $request, $id)
    {
        $request->validate([
            'recipient_notes' => 'required|string|max:1000'
        ], [
            'recipient_notes.required' => 'Please provide a reason/note for rejecting the document.'
        ]);

        try {
            $user = Auth::user();
            $document = \App\Models\DocumentLog::where('client_id', $user->id)
                ->where('id', $id)
                ->firstOrFail();

            $recipientNotes = $request->recipient_notes;

            $document->update([
                'status' => 'rejected',
                'recipient_notes' => $recipientNotes
            ]);

            // Notify Admin via Email and Telegram
            $this->notifyAdminOfDocumentAction($document, 'rejected', $recipientNotes);

            return redirect()->back()->with([
                'message' => 'Document has been rejected. The administrator has been notified.',
                'alert-type' => 'info'
            ]);
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    /**
     * Upload signed copy of document
     */
    public function uploadSignedDocument(Request $request, $id)
    {
        $request->validate([
            'signed_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'recipient_notes' => 'nullable|string|max:1000'
        ]);

        try {
            $user = Auth::user();
            $document = \App\Models\DocumentLog::where('client_id', $user->id)
                ->where('id', $id)
                ->firstOrFail();

            if ($document->action_required !== 'sign_upload') {
                return redirect()->back()->with([
                    'message' => 'No signature upload required for this document.',
                    'alert-type' => 'warning'
                ]);
            }

            if ($request->hasFile('signed_file')) {
                $file = $request->file('signed_file');
                $uploadPath = public_path('upload/signed-documents');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                $fileExtension = $file->getClientOriginalExtension();
                $newFileName = time() . '_' . uniqid() . '.' . $fileExtension;
                $file->move($uploadPath, $newFileName);
                $newFilePath = '/upload/signed-documents/' . $newFileName;

                // Delete old file if exists
                if ($document->signed_path && File::exists(public_path($document->signed_path))) {
                    @unlink(public_path($document->signed_path));
                }

                $recipientNotes = $request->recipient_notes;

                $document->update([
                    'signed_path' => $newFilePath,
                    'status' => 'signed',
                    'recipient_notes' => $recipientNotes
                ]);

                // Notify Admin via Email and Telegram
                $this->notifyAdminOfDocumentAction($document, 'signed & uploaded', $recipientNotes);

                return redirect()->back()->with([
                    'message' => 'Signed document uploaded successfully.',
                    'alert-type' => 'success'
                ]);
            }

            return redirect()->back()->with([
                'message' => 'No file was uploaded.',
                'alert-type' => 'error'
            ]);
        } catch (\Throwable $e) {
            return $this->backWithError($e->getMessage());
        }
    }

    /**
     * Legal & CPA Due Diligence & Financial Documents (KYC)
     */
    public function kycIndex()
    {
        try {
            $title = __('Client Financial & Legal Document Intake');
            $user = Auth::user();
            $documents = \App\Models\ClientKycDocument::where('client_id', $user->id)->orderBy('id', 'desc')->get();
            $cases = $user->clientCases()->get();

            return view('frontend.theme1.auth-client.pages.kyc.index', compact('title', 'user', 'documents', 'cases'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function kycUpload(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|max:100',
            'file_title' => 'required|string|max:255',
            'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx|max:15360',
            'case_id' => 'nullable|exists:client_cases,id',
        ]);

        try {
            $user = Auth::user();
            $file = $request->file('document_file');
            $uploadPath = public_path('upload/client-kyc-docs');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $extension = $file->getClientOriginalExtension();
            $newFileName = time() . '_' . uniqid() . '.' . $extension;
            $file->move($uploadPath, $newFileName);
            $filePath = '/upload/client-kyc-docs/' . $newFileName;
            $fileSize = file_exists(public_path($filePath)) ? round(filesize(public_path($filePath)) / 1024, 1) . ' KB' : 'N/A';

            \App\Models\ClientKycDocument::create([
                'client_id' => $user->id,
                'case_id' => $request->case_id,
                'document_type' => $request->document_type,
                'file_title' => $request->file_title,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'status' => 'Pending Review',
            ]);

            \App\Models\SystemAuditLog::logAction('KYC_DOCUMENT_UPLOADED', "Client uploaded KYC/Tax document: {$request->file_title} ({$request->document_type})", $user->id, 'client');

            // Telegram Notification
            try {
                $escapedName = htmlspecialchars($user->name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedTitle = htmlspecialchars($request->file_title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedType = htmlspecialchars($request->document_type, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $telMsg = "📁 <b>New Client Financial / KYC Document Uploaded</b>\n\n"
                        . "👤 <b>Client:</b> {$escapedName}\n"
                        . "📄 <b>Document:</b> {$escapedTitle}\n"
                        . "🏷️ <b>Category:</b> {$escapedType}\n"
                        . "💾 <b>Size:</b> {$fileSize}\n"
                        . "📅 <b>Time:</b> " . now()->format('Y-m-d H:i:s') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}

            return redirect()->back()->with('success', __('Document uploaded successfully and queued for Attorney & CPA review.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Retainer & Trust Settlement Payout Digital Confirmation
     */
    public function confirmSettlement(Request $request, $id)
    {
        $request->validate([
            'payout_method' => 'required|string|max:100',
            'payout_destination_details' => 'required|string|max:1000',
            'pin' => 'required|numeric|digits:4',
        ]);

        try {
            $user = Auth::user();
            $settlement = \App\Models\CaseSettlement::where('case_id', $id)
                ->where('client_id', $user->id)
                ->firstOrFail();

            // Verify PIN
            if ($user->pin_hash && !\Illuminate\Support\Facades\Hash::check($request->pin, $user->pin_hash)) {
                return redirect()->back()->with('error', __('Invalid 4-digit Security PIN. Please verify your PIN and try again.'));
            }

            $signatureHash = hash('sha256', $user->id . '_' . $settlement->id . '_' . time() . '_' . $request->ip());

            $settlement->update([
                'payout_method' => $request->payout_method,
                'payout_destination_details' => $request->payout_destination_details,
                'client_confirmed_at' => now(),
                'client_signature_hash' => $signatureHash,
                'status' => 'Client Confirmed - Pending Disbursement',
            ]);

            \App\Models\SystemAuditLog::logAction('SETTLEMENT_CONFIRMED', "Client confirmed settlement disbursement instructions for Case #{$id}. Hash: {$signatureHash}", $user->id, 'client');

            // Telegram Notification
            try {
                $escapedName = htmlspecialchars($user->name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedMethod = htmlspecialchars($request->payout_method, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $payoutAmount = number_format($settlement->net_client_payout ?: $settlement->gross_amount, 2);
                $telMsg = "⚖️ <b>Settlement Disbursement Authorized by Client</b>\n\n"
                        . "👤 <b>Client:</b> {$escapedName}\n"
                        . "💼 <b>Case ID:</b> #{$id}\n"
                        . "💵 <b>Net Payout:</b> $" . $payoutAmount . "\n"
                        . "🏦 <b>Method:</b> {$escapedMethod}\n"
                        . "🛡️ <b>Authorization:</b> 4-Digit Security PIN Verified\n"
                        . "📅 <b>Time:</b> " . now()->format('Y-m-d H:i:s') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}

            return redirect()->back()->with('success', __('Settlement disbursement instructions confirmed successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
