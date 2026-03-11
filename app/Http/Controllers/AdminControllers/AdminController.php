<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Attorney;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Conversation;
use App\Models\Designation;
use App\Models\Hardship;
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

            $title = 'My Dashboard';
            $AttorneyCount = Attorney::count();
            $ServiceCount = Service::count();
            $BlogCount = Blog::count();
            $AppointmentCount = Appointment::count();
            return view('backend.pages.dashboard', compact('title', 'AttorneyCount', 'ServiceCount', 'BlogCount', 'AppointmentCount', 'data'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
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

    public function profileUpdate(Request $request)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $input = $request->all();
            if ($request->hasFile('photo')) {
                $input['photo'] = $request->photo[0];
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
            return $this->backWithSuccess($user->name . '\'s personal information has been updated successfully');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function adminDelete(Request $request)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $user = Auth::user();
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

    //=============== Get Appointments ===============//
    public function getHardship()
    {
        try {
            $title = 'Hardships';
            $hardships = Hardship::all();
            return view('backend.pages.hardships.index', compact('hardships', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function viewHardship(Hardship $hardship)
    {
        try {
            $title = 'Hardship';
            $hardship->update(['viewed' => true]);
            return view('backend.pages.hardships.show', compact('hardship', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroyHardship(Hardship $hardship)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            if ($hardship->file) {
                if (file_exists(public_path($hardship->file))) {
                    unlink(public_path($hardship->file));
                }
            }
            $hardship->delete();
            return $this->backWithSuccess('Hardship deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //=============== Get chat history ===============//
    public function conversationIndex()
    {
        try {
            $title = 'Conversations';
            $conversations = $this->viewConversationsOnIndex(Auth::user()->conversation);
            return view('backend.pages.conversations.index', compact('conversations', 'title'));
        } catch (\Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function viewConversationsOnIndex($conversations)
    {
        try {
            $row = [];
            foreach ($conversations as $key => $conversation) {
                $client = [];
                foreach ($conversation->user as $person) {
                    if ($person->id !== Auth::user()->id) {
                        $client = $person;
                    }
                }
                $lastMessage = $conversation->messages()->orderBy('id', 'DESC')->first();
                $row[] = '<tr>
                        <td width="5%">' . ($key + 1) . '</td>
                        <td width="10%"><img src="' . asset($client->profile_photo_url) . '" alt="" class="img-fluid img-thumbnail rounded-circle w-50"></td>
                        <td width="15%">' . $client->name . '</td>
                        <td><a href="' . route('admin.conversation.get-conversation', $conversation->slug) . '">
                        <p class="px-0 pb-1 my-0">' . $lastMessage->text . '</p>
                        ' . ($lastMessage->file ? '<p class="px-0 pb-1 my-0"><i class="fas fa-file"></i> &nbsp;' . $lastMessage->file_name . '</p>' : '') . '
                        <p class="px-0 pb-1 my-0 text-right">' . ($lastMessage->read ? '<small>seen</small>' : '<small>unseen</small>') . '</p>
                        </a></td>
                        <td width="10%" class="text-right">' . date('D-M-y', strtotime($conversation->updated_at)) . '</br>' . date('H:i', strtotime($conversation->updated_at)) . '</td>
                    </tr>';
            }
            $output = implode(' ', $row);
            return $output;
        } catch (\Throwable $th) {
            return $this->backWithError($th->getMessage());
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
            return view('backend.pages.conversations.messages', compact('title', 'conversation'));
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
