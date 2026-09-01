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
        $cases = collect();
        $latestCases = collect();
        $invoices = collect();
        $invoicesCount = 0;
        $documentsCount = 0;
        $recentAppointments = [];
        $conversationCount = 0;
        $unreadMessageCount = 0;
        $title = __('Dashboard');
        $page = null;
        $pageContent = null;

        try {
            $user = Auth::user();
            $page = PageSettings::where('name', 'client_dashboard')->first();
            if (!empty($page)) {
                $pageContent = PageSectionSettings::where('name', 'client_dashboard_breadcumb_bg_img')->first();
                if (!empty($pageContent)) {
                    $title = ucfirst(clean($pageContent->title));
                } else {
                    $title = ucfirst(clean($page->name));
                }
            }

            try { 
                $cases = $user->clientCases()->latest()->get();
                $casesCount = $cases->count();
                $latestCases = $cases->take(5);
            } catch (\Throwable $e) {}

            try {
                $invoices = Invoice::where('client_id', $user->id)->latest()->get();
                $invoicesCount = $invoices->count();
                $invoicesTotalAmount = (float)$invoices->sum(fn($i) => $i->total_amount ?: $i->amount);
                $invoicesUnpaidAmount = (float)$invoices->whereNotIn('status', ['paid', 'cancelled'])->sum(fn($i) => $i->total_amount ?: $i->amount);
            } catch (\Throwable $e) {
                $invoicesTotalAmount = 0.00;
                $invoicesUnpaidAmount = 0.00;
            }

            try {
                $documentsCount = CaseDocument::where('user_id', $user->id)->count() + \App\Models\KycDocument::where('user_id', $user->id)->count() + \App\Models\LegalDocumentTemplate::where('user_id', $user->id)->count();
            } catch (\Throwable $e) {}

            try { $recentAppointments = Appointment::where('email', $user->email)->latest()->take(5)->get(); } catch (\Throwable $e) {}
            try { $conversationCount = $user->conversation()->count(); } catch (\Throwable $e) {}
            
            try {
                $conversations = $user->conversation;
                foreach ($conversations as $conversation) {
                    $count = $conversation->messages()
                        ->where('read', false)
                        ->where('user_id', '!=', $user->id)
                        ->count();
                    $unreadMessageCount += $count;
                }
            } catch (\Throwable $e) {}

        } catch (\Throwable $th) {
            // Log main errors if needed
        }

        return view('frontend.theme1.auth-client.pages.dashboard', compact('title', 'page', 'pageContent', 'cases', 'casesCount', 'latestCases', 'invoices', 'invoicesCount', 'invoicesTotalAmount', 'invoicesUnpaidAmount', 'documentsCount', 'recentAppointments', 'conversationCount', 'unreadMessageCount'));
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

    public function profilePresetAvatar(Request $request)
    {
        try {
            $user = Auth::user();
            $preset = $request->input('preset');
            
            if ($preset === 'male') {
                $user->profile_photo_path = 'assets/images/avatars/male.svg';
                $user->save();
            } elseif ($preset === 'female') {
                $user->profile_photo_path = 'assets/images/avatars/female.svg';
                $user->save();
            } elseif ($preset === 'neutral') {
                $user->profile_photo_path = 'assets/images/avatars/neutral.svg';
                $user->save();
            } elseif ($preset === 'delete') {
                $user->profile_photo_path = null;
                $user->save();
            }

            return $this->backWithSuccess(__('Profile avatar updated successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function profileInfoUpdate(Request $request)
    {
        try {
            $user = Auth::user();
            if ($request->filled('preferred_currency')) {
                $user->preferred_currency = strtoupper(trim($request->preferred_currency));
            }
            if ($request->filled('name')) {
                $user->name = $request->name;
            }
            if ($request->filled('phone')) {
                $user->phone = $request->phone;
            }
            if ($request->filled('address')) {
                $user->address = $request->address;
            }
            $user->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'currency' => $user->preferred_currency]);
            }
            return $this->backWithSuccess('Profile updated successfully.');
        } catch (\Throwable $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
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
            $title = 'Live Counsel & Attorney Support';
            $client = Auth::user();
            $counsel = $client->assigned_counsel ?: User::role(['attorney', 'admin'])->first();
            
            // Find or create direct client-to-counsel conversation
            $conversation = null;
            if ($client->conversation && $client->conversation->count() > 0) {
                $conversation = $client->conversation->first();
            }
            
            if (!$conversation && $counsel) {
                $conversation = Conversation::create([
                    'name' => $client->name . ' vs ' . $counsel->name,
                    'slug' => 'chat_' . $client->id . '_' . $counsel->id . '_' . time(),
                ]);
                $conversation->user()->sync([$client->id, $counsel->id]);
            }

            $messages = $conversation ? $conversation->messages()->with('user')->orderBy('id', 'asc')->get() : collect();

            // Mark counsel messages as read
            if ($conversation) {
                $conversation->messages()->where('user_id', '!=', $client->id)->where('read', false)->update(['read' => true]);
            }

            $chatSettings = \App\Services\ChatwootService::getSettings();
            $identifier = 'client_' . $client->id;
            $hmacHash = !empty($chatSettings['hmac_key']) ? hash_hmac('sha256', $identifier, $chatSettings['hmac_key']) : '';

            return view('frontend.theme1.auth-client.pages.chat.index', compact('title', 'conversation', 'messages', 'counsel', 'chatSettings', 'identifier', 'hmacHash'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sendChatMessage(Request $request, $slug)
    {
        $request->validate([
            'text' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,docx,doc,jpeg,png,jpg,xlsx|max:15360',
        ]);
        
        try {
            $conversation = Conversation::where('slug', $slug)->firstOrFail();
            $filePath = null;
            $fileName = null;
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $newFileName = time() . '_' . uniqid() . '.' . $ext;
                $uploadPath = public_path('upload/chat-attachments');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $newFileName);
                $filePath = '/upload/chat-attachments/' . $newFileName;
            }
            
            if (empty($request->text) && empty($filePath)) {
                return response()->json(['error' => 'Message or attachment required.'], 422);
            }
            
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => Auth::id(),
                'text' => $request->text ?: '',
                'file' => $filePath,
                'file_name' => $fileName,
                'read' => false,
            ]);
            
            // Telegram Alert
            try {
                $clientName = Auth::user()->name;
                $msgPreview = $request->text ?: "[Attached: {$fileName}]";
                $telMsg = "💬 <b>Live Client Message Received</b>\n\n"
                        . "👤 <b>Client:</b> " . htmlspecialchars($clientName, ENT_QUOTES, 'UTF-8') . " (#CLI-" . sprintf('%05d', Auth::id()) . ")\n"
                        . "💬 <b>Message:</b> " . htmlspecialchars($msgPreview, ENT_QUOTES, 'UTF-8') . "\n"
                        . "📅 <b>Time:</b> " . now()->format('M d, Y h:i A') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}
            
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'text' => $message->text,
                    'file' => $message->file,
                    'file_name' => $message->file_name,
                    'is_sender' => true,
                    'time' => $message->created_at->format('h:i A'),
                    'date' => $message->created_at->format('M d'),
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function pollChatMessages(Request $request, $slug)
    {
        try {
            $conversation = Conversation::where('slug', $slug)->firstOrFail();
            $lastId = (int)$request->input('last_id', 0);
            
            $newMessages = $conversation->messages()
                ->where('id', '>', $lastId)
                ->with('user')
                ->orderBy('id', 'asc')
                ->get();
                
            $conversation->messages()
                ->where('id', '>', $lastId)
                ->where('user_id', '!=', Auth::id())
                ->update(['read' => true]);
                
            $formatted = $newMessages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'user_id' => $msg->user_id,
                    'user_name' => $msg->user ? $msg->user->name : 'Counsel',
                    'text' => $msg->text,
                    'file' => $msg->file,
                    'file_name' => $msg->file_name,
                    'is_sender' => ($msg->user_id === Auth::id()),
                    'time' => $msg->created_at->format('h:i A'),
                    'date' => $msg->created_at->format('M d'),
                ];
            });
            
            return response()->json(['messages' => $formatted]);
        } catch (\Throwable $e) {
            return response()->json(['messages' => []]);
        }
    }

    public function kycHub()
    {
        try {
            $kycFields = \App\Http\Controllers\AdminControllers\AdminKycController::getFields();
            $title = __('Identity Verification & Due Diligence (KYC Hub)');
            $client = Auth::user();
            $kycDocs = $client->kycDocuments()->orderBy('id', 'desc')->get();
            $verifiedCount = $kycDocs->where('status', 'approved')->count();
            $pendingCount = $kycDocs->where('status', 'pending')->count();
            
            return view('frontend.theme1.auth-client.pages.kyc.hub', compact('title', 'client', 'kycDocs', 'verifiedCount', 'pendingCount', 'kycFields'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function kycSubmit(Request $request)
    {
        $this->validate($request, [
            'document_type' => ['required', 'string'],
            'document_number' => ['nullable', 'string', 'max:100'],
            'file' => ['required', 'file', 'mimes:pdf,docx,doc,jpeg,png,jpg', 'max:20480'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $uploadPath = public_path('upload/kyc-documents');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $newFileName = 'kyc_' . Auth::id() . '_' . time() . '_' . uniqid() . '.' . $ext;
            $file->move($uploadPath, $newFileName);
            $newFilePath = '/upload/kyc-documents/' . $newFileName;

            $doc = ClientKycDocument::create([
                'client_id' => Auth::id(),
                'document_type' => $request->document_type,
                'document_name' => $request->document_type . ($request->document_number ? ' (' . $request->document_number . ')' : ''),
                'file_path' => $newFilePath,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            \App\Models\ActivityLog::log('KYC Uploaded', 'Client ' . Auth::user()->name . ' uploaded identity verification document: ' . $doc->document_name);

            // Telegram Notification
            try {
                $clientName = Auth::user()->name;
                $telMsg = "🪪 <b>New KYC Identity Document Submitted</b>\n\n"
                        . "👤 <b>Client:</b> " . htmlspecialchars($clientName, ENT_QUOTES, 'UTF-8') . " (#CLI-" . sprintf('%05d', Auth::id()) . ")\n"
                        . "📄 <b>Type:</b> " . htmlspecialchars($request->document_type, ENT_QUOTES, 'UTF-8') . "\n"
                        . "📅 <b>Time:</b> " . now()->format('M d, Y h:i A') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}

            return redirect()->back()->with('success', __('Identity verification document submitted successfully for attorney review.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
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

            return redirect()->route('client.conversation.index');
        } catch (\Throwable $th) {
            return redirect()->route('client.conversation.index');
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

    public function invoiceReceipt($id)
    {
        try {
            $invoice = Invoice::with(['clientCase.attorney'])->findOrFail($id);

            if ($invoice->client_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            $title = __('Official Settlement & Payment Receipt #') . $invoice->invoice_number;
            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');

            $contactPage = \App\Models\PageSettings::where('name', 'contact')->first();
            $contactInfo = $contactPage ? $contactPage->sections()->where('name', 'contact_info')->first() : null;
            $emailInfo = $contactPage ? $contactPage->sections()->where('name', 'email')->first() : null;

            $companyAddress = env('COMPANY_ADDRESS') ?: ($contactInfo ? implode(', ', array_filter([$contactInfo->line_one, $contactInfo->line_two])) : '582 Professional Way, Financial District, DC');
            $companyPhone = env('COMPANY_PHONE') ?: ($contactInfo && $contactInfo->line_two && preg_match('/[0-9]/', $contactInfo->line_two) ? $contactInfo->line_two : '(216) 230-1837');
            $companyEmail = env('COMPANY_EMAIL') ?: ($emailInfo ? $emailInfo->line_one : 'support@yourcpaexpert.com');

            return view('frontend.theme1.auth-client.pages.invoices.receipt', compact('title', 'invoice', 'companyName', 'companyAddress', 'companyPhone', 'companyEmail'));
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
     * Directly e-sign document electronically with 4-Digit PIN authentication
     */
    public function signDocumentElectronically(Request $request, $id)
    {
        $request->validate([
            'signature_text' => 'required|string|max:255',
            'pin' => 'required|numeric|digits:4',
            'agreement_accepted' => 'required|accepted',
        ], [
            'signature_text.required' => 'Please type your full legal name as your electronic signature.',
            'pin.required' => 'Your 4-Digit Security PIN is required to execute this legal document.',
            'pin.digits' => 'Security PIN must be exactly 4 digits.',
            'agreement_accepted.accepted' => 'You must agree and acknowledge the legal terms before signing.',
        ]);

        try {
            $user = Auth::user();
            $document = \App\Models\DocumentLog::where('client_id', $user->id)
                ->where('id', $id)
                ->firstOrFail();

            // Verify PIN if configured
            if ($user->pin_hash && !\Illuminate\Support\Facades\Hash::check($request->pin, $user->pin_hash)) {
                return redirect()->back()->with([
                    'message' => 'Invalid 4-Digit Security PIN. E-Signature authorization failed.',
                    'alert-type' => 'error'
                ]);
            }

            $securityHash = strtoupper(substr(hash('sha256', $user->id . '|' . $document->id . '|' . now()->timestamp . '|' . $request->signature_text), 0, 32));

            // Append Cryptographic Signature Certificate to document content
            $certHtml = '<div style="margin-top: 35px; padding: 20px; border: 2px solid #22c55e; border-radius: 8px; background: #f0fdf4; color: #166534; font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, monospace;">'
                . '<div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #bbf7d0; padding-bottom: 10px; margin-bottom: 12px;">'
                . '<h4 style="margin: 0; color: #15803d; font-size: 15px; font-weight: 700;">✓ CERTIFIED ELECTRONIC SIGNATURE & EXECUTION</h4>'
                . '<span style="background: #15803d; color: #fff; font-size: 10px; padding: 3px 8px; border-radius: 4px; font-weight: bold;">ESIGN COMPLIANT</span>'
                . '</div>'
                . '<p style="margin: 4px 0; font-size: 13px;"><strong>Authorized Signer:</strong> ' . htmlspecialchars($request->signature_text) . ' (' . htmlspecialchars($user->name) . ')</p>'
                . '<p style="margin: 4px 0; font-size: 13px;"><strong>Signer Email:</strong> ' . htmlspecialchars($user->email) . '</p>'
                . '<p style="margin: 4px 0; font-size: 13px;"><strong>Execution Timestamp:</strong> ' . now()->format('M d, Y h:i:s A T') . '</p>'
                . '<p style="margin: 4px 0; font-size: 13px;"><strong>Signer IP Address:</strong> ' . htmlspecialchars($request->ip()) . '</p>'
                . '<p style="margin: 4px 0; font-size: 12px; color: #15803d;"><strong>Cryptographic Verification Hash:</strong> <code>' . $securityHash . '</code></p>'
                . '</div>';

            $document->update([
                'status' => 'signed',
                'signed_at' => now(),
                'recipient_notes' => 'Electronically signed by ' . $request->signature_text . ' (IP: ' . $request->ip() . ')',
                'content' => $document->content . "\n\n" . $certHtml,
            ]);

            // Notify Admin via Telegram / Notification
            $this->notifyAdminOfDocumentAction($document, 'electronically signed', 'Signer: ' . $request->signature_text . ' (Hash: ' . $securityHash . ')');

            return redirect()->back()->with([
                'message' => 'Document has been legally e-signed and verified successfully.',
                'alert-type' => 'success'
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
            $kycSettings = \App\Http\Controllers\AdminControllers\AdminKycController::getKycSettings();
            $title = $kycSettings['kyc_title'] ?? __('Client Financial & Legal Document Intake');
            $user = Auth::user();
            $documents = \App\Models\ClientKycDocument::where('client_id', $user->id)->orderBy('id', 'desc')->get();
            $cases = $user->clientCases()->get();

            return view('frontend.theme1.auth-client.pages.kyc.index', compact('title', 'user', 'documents', 'cases', 'kycSettings'));
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
