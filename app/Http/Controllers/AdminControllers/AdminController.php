<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Attorney;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Conversation;
use App\Models\Designation;
use App\Models\ReliefRequest;
use App\Models\Message;
use App\Models\Service;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
        Parent::__construct();
    }

    public function index()
    {
        try {
            $appointment = Appointment::select('id', 'created_at')
                ->get()
                ->groupBy(function ($date) {
                    //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
                    return Carbon::parse($date->created_at)->format('m'); // grouping by months
                });

            $appointmentmcount = [];
            $appointmentMlist = [];

            foreach ($appointment as $key => $value) {
                $appointmentmcount[(int)$key] = count($value);
            }

            for ($i = 1; $i <= 12; $i++) {
                if (!empty($appointmentmcount[$i])) {
                    $appointmentMlist[$i] = $appointmentmcount[$i];
                } else {
                    $appointmentMlist[$i] = 0;
                }
            }
            $data['appointmentMlist'] = $appointmentMlist;

            // Monthly Revenue BI Calculation
            $monthlyRevenue = [];
            for ($m = 1; $m <= 12; $m++) {
                $monthlyRevenue[$m] = 0;
            }
            try {
                $paidInvoices = \App\Models\Invoice::where('status', 'paid')
                    ->whereYear('updated_at', date('Y'))
                    ->get()
                    ->groupBy(function ($inv) {
                        return (int)Carbon::parse($inv->updated_at)->format('n');
                    });
                foreach ($paidInvoices as $month => $group) {
                    $monthlyRevenue[$month] = $group->sum('amount');
                }
            } catch (\Throwable $e) {}
            $data['monthlyRevenue'] = $monthlyRevenue;

            $title = 'Executive Practice Dashboard';
            $AttorneyCount = 0; try { $AttorneyCount = Attorney::count(); } catch (\Throwable $e) {}
            $ServiceCount = 0; try { $ServiceCount = Service::count(); } catch (\Throwable $e) {}
            $BlogCount = 0; try { $BlogCount = Blog::count(); } catch (\Throwable $e) {}
            $AppointmentCount = 0; try { $AppointmentCount = Appointment::count(); } catch (\Throwable $e) {}
            $reliefRequestCount = 0; try { $reliefRequestCount = ReliefRequest::count(); } catch (\Throwable $e) {}

            $totalPaidRevenue = 0; try { $totalPaidRevenue = \App\Models\Invoice::where('status', 'paid')->sum('amount'); } catch (\Throwable $e) {}
            $pendingInvoicesCount = 0; try { $pendingInvoicesCount = \App\Models\Invoice::where('status', 'pending_proof')->count(); } catch (\Throwable $e) {}
            $activeCasesCount = 0; try { $activeCasesCount = \App\Models\ClientCase::where('status', 'active')->count(); } catch (\Throwable $e) {}
            $clientCount = 0; try { $clientCount = \App\Models\User::role('client')->count(); } catch (\Throwable $e) {}
            $unreadMessagesCount = 0; try { $unreadMessagesCount = \App\Models\Message::where('sender_type', 'client')->whereNull('read_at')->count(); } catch (\Throwable $e) {}

            return view('backend.pages.dashboard', compact(
                'title',
                'AttorneyCount',
                'ServiceCount',
                'BlogCount',
                'AppointmentCount',
                'data',
                'reliefRequestCount',
                'totalPaidRevenue',
                'pendingInvoicesCount',
                'activeCasesCount',
                'clientCount',
                'unreadMessagesCount'
            ));
        } catch (\Throwable $e) {
            return view('backend.pages.dashboard', ['error' => $e->getMessage(), 'title' => 'Dashboard (Limited Mode)', 'data' => ['appointmentMlist' => []]]);
        }
    }

    public function profile()
    {
        try {
            $user = User::where(['id'=>Auth::id()])->first();

            if(!$user->attorney) {
                $title = 'My profile';
                return view('backend.pages.profile.show', compact('title'));
            }
            else {
                $attorney = Attorney::where(['user_id'=>Auth::id()])->first();
                $designation = Designation::all();
                return view('backend.pages.attorney.form', [
                    'title' => 'My profile',
                    'page' => 'profile',
                    'attorney' => $attorney,
                    'designation' => $designation
                ]);
            }
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function securityPinView()
    {
        try {
            $title = 'Admin 2FA Security Gate PIN';
            return view('backend.pages.profile.show', compact('title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function profileUpdate(Request $request)
    {
        try {
            $input = $request->all();
            if ($request->hasFile('photo')) {
                $input['photo'] = $request->photo[0];
            }
            $user = Auth::guard('admin')->user() ?: Auth::user();
            if (!$user) {
                return redirect()->route('admin.login')->with('error', 'Session expired. Please log in again.');
            }


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

    public function updateSecurityPin(Request $request)
    {
        try {
            $request->validate([
                'pin' => 'required|numeric|digits:4|confirmed',
                'current_password' => 'nullable|string',
            ], [
                'pin.digits' => 'Security PIN must be exactly 4 digits.',
                'pin.numeric' => 'Security PIN must contain only numbers.',
                'pin.confirmed' => 'PIN confirmation does not match.',
            ]);

            $user = Auth::guard('admin')->user() ?: Auth::user();
            if (!$user) {
                return redirect()->route('admin.login')->with('error', 'Session expired. Please log in again.');
            }

            if ($request->filled('current_password') && !Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->with('error', 'Current password does not match our records.');
            }

            $user->pin_hash = Hash::make($request->pin);
            if ($request->has('enable_2fa')) {
                $user->two_factor_enabled = $request->boolean('enable_2fa') ? 1 : 0;
            } else {
                $user->two_factor_enabled = 1;
            }
            $user->save();

            \App\Models\GeneralSettings::sendTelegramNotification("🔒 <b>Admin 2FA Security PIN Updated</b>\nUser: " . htmlspecialchars($user->name) . " ({$user->email})\nTimestamp: " . now()->format('Y-m-d H:i:s'));

            return redirect()->back()->with('success', 'Your 4-digit Security Gate PIN has been updated successfully. You can now use it to instantly authenticate across 2FA security gates.');
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
            return $this->backWithSuccess($user->name . '\'s personal information has been updated successfully');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function adminDelete(Request $request)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $user = Auth::guard('admin')->user() ?: Auth::user();
            if (!$user) {
                return redirect()->route('admin.login')->with('error', 'Session expired. Please log in again.');
            }
            if (!isset($request->password) || !Hash::check($request->password, $user->password)) {
                $notification = array(
                    'message' => 'The provided password does not match your current password.',
                    'alert-type' => 'error'
                );
                return back()->with($notification);
            }
            $user->deleteProfilePhoto();
            $user->tokens->each->delete();
            $user->delete();
            return redirect()->back();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //=============== Get Contact Message ===============//
    public function getContactMessage()
    {
        try {
            $title = 'Contact Us';
            $contacts = Contact::all();
            return view('backend.pages.contacts.index', compact('contacts', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function viewContactMessage($id)
    {
        try {
            $title = 'Contact';
            $contact = Contact::findOrfail($id);
            $contact->status = 2;
            $contact->save();
            return view('backend.pages.contacts.show', compact('contact', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroyContactMessage($id)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $contact = Contact::findOrfail($id);
            $contact->delete();
            return $this->backWithSuccess('Contact deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //=============== Get Appointments ===============//
    public function getAppointment()
    {
        try {
            $title = 'Appointment';
            $appointments = Appointment::all();
            return view('backend.pages.appointments.index', compact('appointments', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function viewAppointment($id)
    {
        try {
            $title = 'Appointment';
            $appointment = Appointment::findOrfail($id);
            $appointment->status = 2;
            $appointment->save();
            return view('backend.pages.appointments.show', compact('appointment', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroyAppointment($id)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $appoinment = Appointment::findOrfail($id);
            $appoinment->delete();
            return $this->backWithSuccess('Appointment deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //=============== Get Financial Relief Requests ===============//
    public function getReliefRequests()
    {
        $hardships = [];
        $title = 'CPA / Legal Assistance Requests';
        try {
            $hardships = ReliefRequest::all();
        } catch (\Throwable $e) {
            // Log if needed
        }
        return view('backend.pages.relief-requests.index', compact('hardships', 'title'));
    }

    public function viewReliefRequest(ReliefRequest $hardship)
    {
        try {
            $title = 'CPA / Legal Assistance Request';
            $hardship->update(['viewed' => true]);
            return view('backend.pages.relief-requests.show', compact('hardship', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroyReliefRequest(ReliefRequest $hardship)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            if ($hardship->file) {
                if (file_exists(public_path($hardship->file))) {
                    unlink(public_path($hardship->file));
                }
            }
            $hardship->delete();
            return $this->backWithSuccess('Request deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //=============== Get chat history ===============//
    public function conversationIndex()
    {
        try {
            $title = 'Unified Communications Hub (Clients & Staff)';
            $chatSettings = \App\Services\ChatwootService::getSettings();
            
            // Admin panel must show ALL client conversations in the firm
            $allConvos = Conversation::with(['user', 'messages'])->latest('updated_at')->get();
            $conversations = $this->viewConversationsOnIndex($allConvos);
            $clients = User::role('client')->orderBy('name')->get();

            // Load all staff & practitioners with attendance and recent chat states
            $staffMembers = User::role('staff')->with(['staffDetail'])->orderBy('name')->get();
            foreach ($staffMembers as $staff) {
                $staff->is_clocked_in = \App\Models\StaffTimeLog::where('user_id', $staff->id)->whereNull('clocked_out_at')->exists();
                $staff->last_msg = \App\Models\StaffMessage::where('staff_user_id', $staff->id)->latest('id')->first();
                $staff->unread_count = \App\Models\StaffMessage::where('staff_user_id', $staff->id)->where('sender_id', '!=', Auth::id())->where('read', 0)->count();
            }

            return view('backend.pages.conversations.index', compact('conversations', 'title', 'chatSettings', 'allConvos', 'clients', 'staffMembers'));
        } catch (\Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function startConversationWithClient($clientId)
    {
        try {
            $client = User::findOrFail($clientId);
            $admin = Auth::user();

            $conversation = Conversation::whereHas('user', function($q) use ($client) {
                $q->where('users.id', $client->id);
            })->first();

            if (!$conversation) {
                $slug = 'convo_' . $client->id . '_' . $admin->id . '_' . time();
                $conversation = Conversation::create([
                    'name' => $client->name . ' - Legal & CPA Representation',
                    'slug' => $slug,
                ]);
                $conversation->user()->attach([$client->id, $admin->id]);
            }

            return redirect()->route('admin.conversation.get-conversation', $conversation->slug);
        } catch (\Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function viewConversationsOnIndex($conversations)
    {
        try {
            $row = [];
            foreach ($conversations as $key => $conversation) {
                // Find client
                $client = $conversation->user->first(function($u) {
                    return $u->id !== Auth::id();
                }) ?: $conversation->user->first();

                $clientName = $client ? htmlspecialchars($client->name) : ($conversation->name ?: 'Client #' . $conversation->id);
                $clientEmail = $client ? htmlspecialchars($client->email) : '';
                $clientAvatar = ($client && !empty($client->profile_photo_url)) ? $client->profile_photo_url : asset('backend/assets/img/profile/other.png');
                
                $lastMessage = $conversation->messages()->latest('id')->first() ?: ($conversation->messages ? $conversation->messages->last() : null);
                $lastText = $lastMessage ? htmlspecialchars($lastMessage->text ?: $lastMessage->message ?: 'Sent an attachment') : '<em class="text-muted">No messages yet</em>';
                $lastFileHtml = ($lastMessage && !empty($lastMessage->file)) ? '<p class="px-0 pb-1 my-0 text-warning"><i class="fas fa-file"></i> &nbsp;' . htmlspecialchars($lastMessage->file_name ?: 'Attachment') . '</p>' : '';
                
                $unreadCount = $conversation->unreadMessages()->count();
                $unreadBadge = $unreadCount > 0 ? '<span class="badge badge-warning ml-2 font-weight-bold">' . $unreadCount . ' Unread</span>' : '';
                $openUrl = route('admin.conversation.get-conversation', $conversation->slug);
                $clientId = $client ? $client->id : 0;
                $safeClientName = htmlspecialchars(addslashes($clientName), ENT_QUOTES);
                $openBtn = '<div class="d-flex align-items-center justify-content-center flex-wrap" style="gap: 5px;">
                                <a href="' . $openUrl . '" class="btn btn-sm btn-warning font-weight-bold text-dark px-2 shadow-sm" title="Open Conversation"><i class="fas fa-comment-dots mr-1"></i> Chat</a>
                                <button type="button" class="btn btn-sm btn-outline-warning px-2" onclick="event.stopPropagation(); launchDirectCall(' . $clientId . ', \'' . $safeClientName . '\', \'audio\', \'' . $conversation->slug . '\')" title="Direct Voice Call"><i class="fas fa-phone-alt"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-info px-2" onclick="event.stopPropagation(); launchDirectCall(' . $clientId . ', \'' . $safeClientName . '\', \'video\', \'' . $conversation->slug . '\')" title="Direct Video Call"><i class="fas fa-video"></i></button>
                            </div>';

                $row[] = '<tr style="cursor: pointer;" onclick="if (!event.target.closest(\'a\') && !event.target.closest(\'button\')) { window.location.href=\'' . $openUrl . '\'; }">
                        <td width="5%" class="align-middle text-center">' . ($key + 1) . '</td>
                        <td width="8%" class="align-middle text-center"><img src="' . $clientAvatar . '" alt="" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" onerror="this.onerror=null;this.src=\'' . asset('backend/assets/img/profile/other.png') . '\';"></td>
                        <td width="22%" class="align-middle"><strong>' . $clientName . '</strong>' . $unreadBadge . ($clientEmail ? '<br><small class="text-muted">' . $clientEmail . '</small>' : '') . '</td>
                        <td class="align-middle"><a href="' . $openUrl . '" class="text-decoration-none text-light d-block">
                        <p class="px-0 pb-1 my-0">' . $lastText . '</p>
                        ' . $lastFileHtml . '
                        </a></td>
                        <td width="14%" class="align-middle text-center">' . date('M d, Y', strtotime($conversation->updated_at)) . '<br><small class="text-muted">' . date('h:i A', strtotime($conversation->updated_at)) . '</small></td>
                        <td width="16%" class="align-middle text-center">' . $openBtn . '</td>
                    </tr>';
            }
            $output = implode(' ', $row);
            return $output;
        } catch (\Throwable $th) {
            return '<tr><td colspan="6" class="text-center text-danger py-4">' . $th->getMessage() . '</td></tr>';
        }
    }

    public function getMessage($slug)
    {
        try {
            $conversation = Conversation::where('slug', $slug)->firstOrFail();
            $client = $conversation->user->first(function($u) {
                return $u->id !== Auth::id();
            }) ?: $conversation->user->first();
            $title = $client ? $client->name : ($conversation->name ?: 'Client Conversation');

            $unreadMessage = $conversation->messages()
                ->where('read', false)
                ->where('user_id', '!=', Auth::user()->id)
                ->get();
            $unreadMessage->each->update(['read' => true]);

            $conversation->load(['messages.user', 'user']);

            return view('backend.pages.conversations.messages', compact('title', 'conversation', 'client'));
        } catch (\Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function sendMessage(Request $request, $slug)
    {
        try {
            $conversation = Conversation::where('slug', $slug)->firstOrFail();

            $rawText = trim($request->text ?? '');
            if (!$request->hasFile('file') && !empty($rawText)) {
                $dup = Message::where('conversation_id', $conversation->id)
                    ->where('user_id', Auth::id())
                    ->where(function($q) use ($rawText) {
                        $q->where('text', $rawText)->orWhere('message', $rawText);
                    })
                    ->where('created_at', '>=', now()->subSeconds(3))
                    ->first();
                if ($dup) {
                    if ($request->ajax()) {
                        return response()->json(['status' => 'duplicate', 'id' => $dup->id]);
                    }
                    return redirect()->back();
                }
            }

            $message = new Message();
            $message->conversation_id = $conversation->id;
            $message->user_id = Auth::user()->id;
            $message->text = $rawText;
            $message->message = $rawText;
            if ($request->hasFile('file')) {
                $request->validate([
                    'file' => ['file', 'mimes:pdf,docx,doc,jpeg,png,jpg,txt', 'max:20480']
                ]);
                $message->file_name = $request->file->getClientOriginalName();
                $fileName = time() . '_' . uniqid() . '.' . $request->file->getClientOriginalExtension();
                $request->file->move(public_path('/upload/message-files'), $fileName);
                $message->file = '/upload/message-files/' . $fileName;
                $message->save();
                $conversation->touch();
            } else {
                if ($message->text) {
                    $message->save();
                    $conversation->touch();
                }
            }

            // Notify participants
            if ($message->id) {
                \Illuminate\Support\Facades\Cache::forget("typing_draft_convo_{$conversation->id}");
                foreach ($conversation->user as $participant) {
                    if ($participant->id !== Auth::user()->id) {
                        $participant->notify(new \App\Notifications\MessageNotification($message));
                    }
                }
            }

            return back();
        } catch (\Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    /**
     * Admin Live State Polling (New Messages, Client Typing Draft, Active Call)
     */
    public function pollLiveState(Request $request, $slug)
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
                    'user_name' => $msg->user ? $msg->user->name : 'Client',
                    'text' => $msg->text ?: ($msg->message ?? ''),
                    'file' => $msg->file,
                    'file_name' => $msg->file_name,
                    'is_mine' => ($msg->user_id === Auth::id()),
                    'time' => $msg->created_at->format('M d, h:i A'),
                ];
            });

            $activeCall = \Illuminate\Support\Facades\Cache::get("active_call_convo_{$conversation->id}");
            $typingData = \Illuminate\Support\Facades\Cache::get("typing_draft_convo_{$conversation->id}");
            $clientTyping = null;
            if ($typingData && ($typingData['user_id'] ?? 0) !== Auth::id()) {
                $clientTyping = $typingData;
            }

            return response()->json([
                'messages' => $formatted,
                'active_call' => $activeCall,
                'client_typing' => $clientTyping,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['messages' => [], 'active_call' => null, 'client_typing' => null]);
        }
    }

    /**
     * Admin Typing Draft - Kept private; admin drafts are never sent to clients.
     */
    public function saveTypingDraft(Request $request, $slug)
    {
        return response()->json(['status' => 'ok']);
    }

    /**
     * Admin Initiates Video/Voice Call with Client
     */
    public function initiateCall(Request $request, $slug)
    {
        return app(\App\Http\Controllers\WebRtcCallController::class)->initiate($request);
    }

    /**
     * Admin Ends or Responds to Call
     */
    public function respondCall(Request $request, $slug)
    {
        return app(\App\Http\Controllers\WebRtcCallController::class)->respond($request);
    }

    public function approveAndCreateCase($relief_id)
    {
        try {
            $relief = ReliefRequest::findOrFail($relief_id);

            // Generate unique Case Number
            do {
                $caseNumber = 'CS-' . rand(100000, 999999);
            } while (\App\Models\ClientCase::where('case_number', $caseNumber)->exists());

            // Create case
            $case = \App\Models\ClientCase::create([
                'case_number' => $caseNumber,
                'title' => $relief->reason ?: 'CPA & Legal Representation Case',
                'description' => "Client initiated request:\n\n" . ($relief->details ?: '') . "\n\nProposed Resolution: " . ($relief->offer ?: ''),
                'client_id' => $relief->user_id,
                'status' => 'pending',
            ]);

            // If relief request has a file, copy/move it to case documents!
            if ($relief->file && file_exists(public_path($relief->file))) {
                $fileExtension = pathinfo($relief->file, PATHINFO_EXTENSION);
                $newFileName = time() . '_' . uniqid() . '.' . $fileExtension;
                $newFilePath = '/upload/case-documents/' . $newFileName;

                // Ensure directory exists
                $uploadPath = public_path('upload/case-documents');
                if (!\Illuminate\Support\Facades\File::exists($uploadPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($uploadPath, 0755, true);
                }

                // Copy the file
                copy(public_path($relief->file), public_path($newFilePath));

                // Create Document Vault entry
                \App\Models\CaseDocument::create([
                    'case_id' => $case->id,
                    'user_id' => $relief->user_id,
                    'title' => $relief->file_name ?: 'Initial Intake Case Document',
                    'file_path' => $newFilePath,
                    'file_type' => $fileExtension,
                    'file_size' => file_exists(public_path($newFilePath)) ? filesize(public_path($newFilePath)) : 0,
                    'is_client_uploaded' => true,
                ]);
            }

            \App\Models\ActivityLog::log('Case Request Approved', 'Approved assistance request #' . $relief->id . ' and opened Case #' . $case->case_number);
            $this->sendSlackNotification('📂 Case representation initialized successfully. Case Number: ' . $case->case_number);

            // Delete relief request so it's converted
            $relief->delete();

            return redirect()->route('admin.cases.edit', $case->id)->with('success', __('Case representation initialized successfully. Case Number: ') . $case->case_number);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
