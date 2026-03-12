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
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ClientViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Parent::__construct();
    }

    public function dashboard()
    {
        $reliefRequestCount = 0;
        $latestRelief = null;
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

            try { $reliefRequestCount = Auth::user()->reliefRequests()->count(); } catch (\Throwable $e) {}
            try { $latestRelief = Auth::user()->reliefRequests()->latest()->first(); } catch (\Throwable $e) {}
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

        return view('frontend.theme1.auth-client.pages.dashboard', compact('title', 'page', 'pageContent', 'reliefRequestCount', 'latestRelief', 'recentAppointments', 'conversationCount', 'unreadMessageCount'));
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
            ])->validateWithBag('updateProfileInformation');

            if (isset($input['photo'])) {
                $user->updateProfilePhoto($input['photo']);
            }

            if ($input['email'] != null) {
                Validator::make($input, [
                    'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                ]);
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
            $title = 'Apply for Financial Relief';
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
            'file' => ['required'],
            'reason' => ['required', 'string'],
            'offer' => ['required', 'string'],
        ]);

        try {
            $filename = time() . $request->file->getClientOriginalName();
            $request->file->move(public_path('/upload/hardship-fils'), $filename);

            $relief = new ReliefRequest();
            $relief->user_id = Auth::user()->id;
            $relief->name = $request->name;
            $relief->phone = $request->phone;
            $relief->email = $request->email;
            $relief->address = $request->address;
            $relief->file_name = $request->file->getClientOriginalName();
            $relief->file = '/upload/hardship-fils/' . $filename;
            $relief->reason = $request->reason;
            $relief->details = $request->details;
            $relief->offer = $request->offer;
            $relief->save();
            return $this->backWithSuccess('Your financial relief request has been submitted successfully');
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
                $message->file_name = $request->file->getClientOriginalName();
                $fileName = time() . $request->file->getClientOriginalName();
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

            return back();
        } catch (\Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }
}
