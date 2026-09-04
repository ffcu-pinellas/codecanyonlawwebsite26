<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        Parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // roles
    public function roleIndex()
    {
        try {
            return view('backend.pages.users.roles.index', [
                'title' => 'User Roles',
                'roles'=> Role::all()
            ]);
        }catch (\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function roleCreate()
    {
        try {
            return view('backend.pages.users.roles.form', [
                'title' => 'Create New Role',
                'role'=> null,
                'permissions' => Permission::all()
            ]);
        }catch (\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function roleStore(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:50']
        ],[
            'name.required' => 'This name field is required.',
            'name.max:50' => 'Role name can\'t more than 50 character.'
        ]);
        try {
            $role = Role::create([
                'name' => strtolower($request->name),
                'guard_name' => 'web'
            ]);
            $role->syncPermissions($request->permission);
            return $this->backWithSuccess('A new role created successfully');
        }catch (\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function roleEdit(Role $role)
    {
        if ($role->name == 'admin'){
            return $this->backWithWarning('Nobody can\'t edit Admin role.');
        }
        try {
            return view('backend.pages.users.roles.form', [
                'title' => 'Edit Role',
                'role'=> $role,
                'permissions' => Permission::all()
            ]);
        }catch (\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getRolePermission(Role $role)
    {
        try {
            $permissions = $role->permissions;
            $data = [];
            foreach ($permissions as $permission){
                $data[] = $permission->id;
            }
            return response()->json($data);
        }catch (\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function roleUpdate(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => ['required', 'max:50']
        ],[
            'name.required' => 'This name field is required.',
            'name.max:50' => 'Role name can\'t more than 50 character.'
        ]);
        try {
            $role->name = strtolower($request->name);
            $role->save();
            $role->syncPermissions($request->permission);
            return $this->backWithSuccess('Role assigned with permission.');
        }catch (\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function roleDestroy(Role $role)
    {
        if ($role->name == 'admin'){
            return $this->backWithWarning('Nobody can\'t delete Admin role.');
        }elseif ($role->name == 'attorney'){
            return $this->backWithWarning('Nobody can\'t delete Attorney role.');
        }
        try {
            $users = User::role($role->name)->count();
            if ($users > 0){
                return $this->backWithWarning('This Role already assigned with some users, please delete them and try again later...');
            }
            $role->syncPermissions([]);
            $role->delete();
            return $this->backWithSuccess('Role has been deleted successfully...');
        }catch (\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // clients
    public function userIndex(Request $request)
    {
        try {
            if ($request->ajax()) {
                $user = User::where(['id' => $request->id])->first();
                if (!$user) {
                    return response()->json(['error' => __('User not found')], 404);
                }
                $attorneys = User::role(['attorney', 'admin'])->get();
                $roles = Role::all();
                $userRoleName = $user->roles->isNotEmpty() ? $user->roles->pluck('name')[0] : '';

                $data = '
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="modal_name">' . __('Full Legal Name') . ' <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="modal_name" class="form-control" value="' . e($user->name) . '" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="modal_email">' . __('Email Address') . ' <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="modal_email" class="form-control" value="' . e($user->email) . '" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="modal_phone">' . __('Phone Number') . '</label>
                            <input type="text" name="phone" id="modal_phone" class="form-control" value="' . e($user->phone) . '">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="modal_currency">' . __('Preferred Currency') . '</label>
                            <select name="preferred_currency" id="modal_currency" class="form-control">
                                <option value="USD" ' . ($user->preferred_currency == 'USD' ? 'selected' : '') . '>USD ($)</option>
                                <option value="EUR" ' . ($user->preferred_currency == 'EUR' ? 'selected' : '') . '>EUR (€)</option>
                                <option value="GBP" ' . ($user->preferred_currency == 'GBP' ? 'selected' : '') . '>GBP (£)</option>
                                <option value="CAD" ' . ($user->preferred_currency == 'CAD' ? 'selected' : '') . '>CAD ($)</option>
                                <option value="AUD" ' . ($user->preferred_currency == 'AUD' ? 'selected' : '') . '>AUD ($)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="modal_address">' . __('Residential / Corporate Address') . '</label>
                        <input type="text" name="address" id="modal_address" class="form-control" value="' . e($user->address) . '">
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="modal_attorney">' . __('Assigned Attorney / CPA Counsel') . '</label>
                            <select name="assigned_attorney_id" id="modal_attorney" class="form-control">
                                <option value="">-- ' . __('Default Firm Lead Counsel') . ' --</option>';
                foreach ($attorneys as $atty) {
                    $selected = ($user->assigned_attorney_id == $atty->id) ? 'selected' : '';
                    $data .= '<option ' . $selected . ' value="' . $atty->id . '">' . e($atty->name) . ' (' . e($atty->email) . ')</option>';
                }
                $data .= '
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="modal_role">' . __('Account Role') . ' <span class="text-danger">*</span></label>
                            <select name="role" id="modal_role" class="form-control" required>';
                foreach ($roles as $role) {
                    $selected = ($role->name === $userRoleName) ? 'selected' : '';
                    $data .= '<option ' . $selected . ' value="' . e($role->name) . '">' . e(ucfirst($role->name)) . '</option>';
                }
                $data .= '
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="modal_password">' . __('New Password') . ' <small class="text-muted">(' . __('Leave blank to keep') . ')</small></label>
                            <input type="password" name="password" id="modal_password" class="form-control" placeholder="' . __('Enter new password') . '">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="modal_pin">' . __('4-Digit Security PIN') . ' <small class="text-muted">(' . __('Leave blank to keep') . ')</small></label>
                            <input type="text" name="pin" id="modal_pin" class="form-control" maxlength="4" placeholder="' . __('e.g. 1234') . '">
                        </div>
                    </div>
                    <input type="hidden" name="id" value="' . $user->id . '">
                ';

                return response()->json(['data' => $data]);
            }

            return view('backend.pages.users.index', [
                'title' => 'All Users',
                'users'=> User::role('admin')->get()
            ]);
        }catch (\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function userIndexSave(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'assigned_attorney_id' => 'nullable|exists:users,id',
            'preferred_currency' => 'nullable|string|in:USD,EUR,GBP,CAD,AUD',
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|string|min:6',
            'pin' => 'nullable|digits:4',
        ]);

        try {
            $user = User::findOrFail($request->id);
            
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone ?: '',
                'address' => $request->address ?: '',
                'assigned_attorney_id' => $request->assigned_attorney_id ?: null,
                'preferred_currency' => $request->preferred_currency ?: ($user->preferred_currency ?: 'USD'),
            ];

            if ($request->filled('password')) {
                $userData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
                $userData['is_temp_password'] = false;
            }

            if ($request->filled('pin')) {
                $userData['pin_hash'] = \Illuminate\Support\Facades\Hash::make($request->pin);
            }

            $user->update($userData);
            $user->syncRoles($request->role);

            \App\Models\SystemAuditLog::logAction('USER_UPDATED', "Staff updated profile data for user #{$user->id} ({$user->email}).", auth()->id(), 'admin');

            return $this->backWithSuccess(__('User profile and assigned counsel updated successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function userDestroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->id === auth()->id()) {
                return $this->backWithError(__('You cannot delete your own account.'));
            }

            $user->delete();

            return $this->backWithSuccess(__('User deleted successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    // clients
    public function clientIndex()
    {
        try {
            $clients = User::role('client')->with(['assignedAttorney', 'clientCases'])->orderBy('id', 'desc')->get();
            $attorneys = User::role('attorney')->get();

            // Aggregated Leads from Appointments, Contact Messages, and Relief/Consultation Requests
            $leadsList = collect();

            // 1. Appointments
            try {
                $appointments = \App\Models\Appointment::orderBy('id', 'desc')->limit(30)->get();
                foreach ($appointments as $apt) {
                    $leadsList->push((object)[
                        'id' => 'apt_' . $apt->id,
                        'source' => 'Appointment Booking',
                        'badge_class' => 'badge-info',
                        'name' => $apt->name,
                        'email' => $apt->email,
                        'phone' => $apt->phone ?? '',
                        'message' => $apt->message ?? ($apt->description ?? ''),
                        'created_at' => $apt->created_at,
                    ]);
                }
            } catch (\Throwable $e) {}

            // 2. Contact Inquiries
            try {
                $contacts = \App\Models\Contact::orderBy('id', 'desc')->limit(30)->get();
                foreach ($contacts as $cnt) {
                    $leadsList->push((object)[
                        'id' => 'cnt_' . $cnt->id,
                        'source' => 'Contact Form',
                        'badge_class' => 'badge-primary',
                        'name' => $cnt->name,
                        'email' => $cnt->email,
                        'phone' => $cnt->phone ?? '',
                        'message' => ($cnt->subject ? "[{$cnt->subject}] " : '') . ($cnt->message ?? ''),
                        'created_at' => $cnt->created_at,
                    ]);
                }
            } catch (\Throwable $e) {}

            // 3. Consultation / Relief Requests
            try {
                $reliefs = \App\Models\ReliefRequest::orderBy('id', 'desc')->limit(30)->get();
                foreach ($reliefs as $rlf) {
                    $leadsList->push((object)[
                        'id' => 'rlf_' . $rlf->id,
                        'source' => 'Consultation Request',
                        'badge_class' => 'badge-warning text-dark',
                        'name' => $rlf->name,
                        'email' => $rlf->email,
                        'phone' => $rlf->phone ?? '',
                        'message' => ($rlf->reason ? "[{$rlf->reason}] " : '') . ($rlf->details ?? ''),
                        'created_at' => $rlf->created_at,
                    ]);
                }
            } catch (\Throwable $e) {}

            // Sort all aggregated leads by latest created_at
            $recentLeads = $leadsList->sortByDesc(function ($item) {
                return $item->created_at ? $item->created_at->timestamp : 0;
            })->values();

            return view('backend.pages.users.client.index', [
                'title' => __('Client Management Directory'),
                'clients' => $clients,
                'attorneys' => $attorneys,
                'recentLeads' => $recentLeads,
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function clientStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'assigned_attorney_id' => 'nullable|exists:users,id',
            'preferred_currency' => 'nullable|string|in:USD,EUR,GBP,CAD,AUD',
        ]);

        try {
            $rawPassword = bin2hex(random_bytes(4));
            $defaultPin = '1234';

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone ?: '',
                'address' => $request->address ?: '',
                'password' => \Illuminate\Support\Facades\Hash::make($rawPassword),
                'pin_hash' => \Illuminate\Support\Facades\Hash::make($defaultPin),
                'is_temp_password' => true,
                'is_first_login' => true,
                'assigned_attorney_id' => $request->assigned_attorney_id,
                'preferred_currency' => $request->preferred_currency ?: 'USD',
            ]);

            $user->assignRole('client');

            \App\Models\SystemAuditLog::logAction('CLIENT_PROVISIONED', "Provisioned client #{$user->id} ({$user->email}) with temporary credentials.", auth()->id(), 'admin');

            // Telegram Notification
            try {
                $adminName = auth()->user() ? auth()->user()->name : 'Admin';
                $escapedName = htmlspecialchars($user->name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedEmail = htmlspecialchars($user->email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedPhone = htmlspecialchars($user->phone ?: 'N/A', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $telMsg = "👤 <b>New Legal/CPA Client Account Provisioned</b>\n\n"
                        . "👤 <b>Client:</b> {$escapedName}\n"
                        . "📧 <b>Email:</b> {$escapedEmail}\n"
                        . "📞 <b>Phone:</b> {$escapedPhone}\n"
                        . "🔑 <b>Temp Pwd:</b> <code>{$rawPassword}</code>\n"
                        . "🛡️ <b>Default PIN:</b> <code>{$defaultPin}</code>\n"
                        . "👔 <b>Provisioned By:</b> " . htmlspecialchars($adminName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "\n"
                        . "📅 <b>Time:</b> " . now()->format('Y-m-d H:i:s') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}

            return redirect()->route('admin.user.client.index')
                ->with('success', __('Client account created successfully.'))
                ->with('generated_credentials', [
                    'client_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'temp_password' => $rawPassword,
                    'default_pin' => $defaultPin,
                ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function generateCredentials($id)
    {
        try {
            $client = User::findOrFail($id);
            $rawPassword = bin2hex(random_bytes(4));
            $defaultPin = '1234';

            $client->password = \Illuminate\Support\Facades\Hash::make($rawPassword);
            $client->pin_hash = \Illuminate\Support\Facades\Hash::make($defaultPin);
            $client->is_temp_password = true;
            $client->is_first_login = true;
            $client->save();

            \App\Models\SystemAuditLog::logAction('GENERATE_CREDENTIALS', "Generated new temporary credentials for client #{$client->id} ({$client->email}) due to security reset.", auth()->id(), 'admin');

            // Dispatch Security Alert Email to Client
            try {
                $siteName = config('app.name', 'Your CPA Expert');
                $loginUrl = route('login');
                $clientRef = sprintf('#CLI-%05d', $client->id);
                $securitySubject = "Security Notification: Account Protection & Access Reset (Ref: {$clientRef})";

                $securityEmailBody = "
                <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #1e293b; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                    <div style='background: #0f172a; color: #ffffff; padding: 24px; text-align: center; border-bottom: 3px solid #f59e0b;'>
                        <h2 style='margin: 0; color: #ffffff; font-size: 20px;'>{$siteName}</h2>
                        <p style='margin: 4px 0 0 0; color: #94a3b8; font-size: 13px;'>Confidential Legal & CPA Security Alert</p>
                    </div>
                    <div style='padding: 24px;'>
                        <p style='font-size: 15px; margin-top: 0;'>Dear <strong>" . e($client->name) . "</strong>,</p>
                        
                        <div style='background: #fef2f2; border-left: 4px solid #ef4444; border-radius: 4px; padding: 14px 18px; margin: 18px 0; font-size: 14px; color: #991b1b;'>
                            <strong>Account Protection Notice:</strong><br>
                            Our Security System flagged suspicious sign-in attempts or unfamiliar device activity on your account. To safeguard your account, we have reset your credentials.
                        </div>

                        <p style='font-size: 14px;'>New temporary credentials have been generated to ensure uninterrupted and secure access to your account:</p>

                        <div style='background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #f59e0b; border-radius: 6px; padding: 18px 20px; margin: 20px 0;'>
                            <h4 style='margin: 0 0 12px 0; color: #0f172a; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;'>Updated Portal Access</h4>
                            <table style='width: 100%; border-collapse: collapse; font-size: 14px;'>
                                <tr>
                                    <td style='padding: 6px 0; color: #64748b; width: 160px;'><strong>Login Now</strong></td>
                                    <td style='padding: 6px 0;'><a href='{$loginUrl}' style='color: #2563eb;'>{$loginUrl}</a></td>
                                </tr>
                                <tr>
                                    <td style='padding: 6px 0; color: #64748b;'><strong>Username / Email:</strong></td>
                                    <td style='padding: 6px 0; color: #0f172a; font-weight: bold;'>" . e($client->email) . "</td>
                                </tr>
                                <tr>
                                    <td style='padding: 6px 0; color: #64748b;'><strong>New Temporary Password:</strong></td>
                                    <td style='padding: 6px 0;'><code style='background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 4px; font-weight: bold;'>{$rawPassword}</code></td>
                                </tr>
                                <tr>
                                    <td style='padding: 6px 0; color: #64748b;'><strong>Default Security PIN:</strong></td>
                                    <td style='padding: 6px 0;'><code style='background: #e2e8f0; color: #334155; padding: 3px 8px; border-radius: 4px; font-weight: bold;'>{$defaultPin}</code></td>
                                </tr>
                            </table>
                            <p style='margin: 12px 0 0 0; font-size: 12px; color: #64748b;'><em>You will be guided through a quick security setup on sign-in to establish your own permanent password and private PIN.</em></p>
                        </div>

                        <div style='text-align: center; margin: 28px 0 20px 0;'>
                            <a href='{$loginUrl}' style='background: #f59e0b; color: #0f172a; font-weight: bold; text-decoration: none; padding: 12px 28px; border-radius: 6px; display: inline-block; font-size: 15px;'>Sign In & Set New Password</a>
                        </div>

                        <p style='font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 16px; margin-bottom: 0;'>
                            If you did not request this or have questions, please reach out to your assigned Attorney & CPA immediately.
                        </p>
                    </div>
                </div>
                ";

                \Illuminate\Support\Facades\Mail::html($securityEmailBody, function ($msg) use ($client, $securitySubject) {
                    $msg->to($client->email, $client->name)
                        ->subject($securitySubject);
                });
            } catch (\Throwable $mailErr) {
                \Illuminate\Support\Facades\Log::warning("Security credentials reset email fallback: " . $mailErr->getMessage());
            }

            // Telegram Notification
            try {
                $adminName = auth()->user() ? auth()->user()->name : 'Admin';
                $escapedName = htmlspecialchars($client->name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedEmail = htmlspecialchars($client->email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $telMsg = "🔑 <b>Temporary Credentials Regenerated & Security Email Sent</b>\n\n"
                        . "👤 <b>Client:</b> {$escapedName}\n"
                        . "📧 <b>Email:</b> {$escapedEmail}\n"
                        . "🔑 <b>New Temp Pwd:</b> <code>{$rawPassword}</code>\n"
                        . "🛡️ <b>Default PIN:</b> <code>{$defaultPin}</code>\n"
                        . "📧 <b>Client Email:</b> Security alert dispatched\n"
                        . "👔 <b>Reset By:</b> " . htmlspecialchars($adminName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "\n"
                        . "📅 <b>Time:</b> " . now()->format('Y-m-d H:i:s') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}

            return redirect()->route('admin.user.client.index')
                ->with('success', __('Temporary login credentials regenerated and security alert email sent to ') . $client->name)
                ->with('generated_credentials', [
                    'client_id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'temp_password' => $rawPassword,
                    'default_pin' => $defaultPin,
                ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sendCustomWelcomeEmail(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:users,id',
            'email_subject' => 'required|string|max:255',
            'email_intro' => 'nullable|string',
            'custom_note' => 'nullable|string',
            'email_portal_msg' => 'nullable|string',
            'include_credentials' => 'nullable|boolean',
        ]);

        try {
            $client = User::findOrFail($request->client_id);
            $includeCredentials = $request->boolean('include_credentials', true);
            $rawPassword = '';
            $defaultPin = '1234';

            if ($includeCredentials) {
                $rawPassword = bin2hex(random_bytes(4));
                $client->password = \Illuminate\Support\Facades\Hash::make($rawPassword);
                $client->pin_hash = \Illuminate\Support\Facades\Hash::make($defaultPin);
                $client->is_temp_password = true;
                $client->is_first_login = true;
                $client->save();
            }

            $siteName = config('app.name', 'Your CPA Expert');
            $loginUrl = route('login');
            $subject = $request->email_subject;
            $clientRef = sprintf('#CLI-%05d', $client->id);
            $intro = $request->email_intro ?: "Your confidential legal & CPA file has been opened with our practice under Client Reference {$clientRef}.";
            $customNote = $request->custom_note;
            $portalMsg = $request->email_portal_msg ?: "You can access our 256-bit encrypted Client Portal 24/7 to review tax and case filings, inspect invoices, upload documents, and communicate directly with your assigned Attorney & CPA.";

            $htmlBody = "
            <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #1e293b; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                <div style='background: #0f172a; color: #ffffff; padding: 24px; text-align: center; border-bottom: 3px solid #f59e0b;'>
                    <h2 style='margin: 0; color: #ffffff; font-size: 20px;'>{$siteName}</h2>
                    <p style='margin: 4px 0 0 0; color: #94a3b8; font-size: 13px;'>Privileged Legal & CPA Client Portal Access</p>
                </div>
                <div style='padding: 24px;'>
                    <p style='font-size: 15px; margin-top: 0;'>Dear <strong>" . e($client->name) . "</strong>,</p>
                    <p style='font-size: 14px;'>" . nl2br(e($intro)) . "</p>
            ";

            if (!empty($customNote)) {
                $htmlBody .= "
                    <div style='background: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 4px; padding: 14px 18px; margin: 20px 0; font-size: 14px; color: #1e3a8a;'>
                        <strong>Attorney & CPA Case Briefing:</strong><br>
                        " . nl2br(e($customNote)) . "
                    </div>
                ";
            }

            $htmlBody .= "<p style='font-size: 14px;'>" . nl2br(e($portalMsg)) . "</p>";

            if ($includeCredentials) {
                $htmlBody .= "
                    <div style='background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #f59e0b; border-radius: 6px; padding: 18px 20px; margin: 24px 0;'>
                        <h4 style='margin: 0 0 12px 0; color: #0f172a; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;'>Your Portal Login Credentials</h4>
                        <table style='width: 100%; border-collapse: collapse; font-size: 14px;'>
                            <tr>
                                <td style='padding: 6px 0; color: #64748b; width: 150px;'><strong>Portal URL:</strong></td>
                                <td style='padding: 6px 0;'><a href='{$loginUrl}' style='color: #2563eb;'>{$loginUrl}</a></td>
                            </tr>
                            <tr>
                                <td style='padding: 6px 0; color: #64748b;'><strong>Username / Email:</strong></td>
                                <td style='padding: 6px 0; color: #0f172a; font-weight: bold;'>" . e($client->email) . "</td>
                            </tr>
                            <tr>
                                <td style='padding: 6px 0; color: #64748b;'><strong>Temporary Password:</strong></td>
                                <td style='padding: 6px 0;'><code style='background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 4px; font-weight: bold;'>{$rawPassword}</code></td>
                            </tr>
                            <tr>
                                <td style='padding: 6px 0; color: #64748b;'><strong>Default 4-Digit PIN:</strong></td>
                                <td style='padding: 6px 0;'><code style='background: #e2e8f0; color: #334155; padding: 3px 8px; border-radius: 4px; font-weight: bold;'>{$defaultPin}</code></td>
                            </tr>
                        </table>
                        <p style='margin: 12px 0 0 0; font-size: 12px; color: #64748b;'><em>Upon initial sign-in, you will be prompted to set your permanent password and confidential security PIN.</em></p>
                    </div>
                ";
            }

            $htmlBody .= "
                    <div style='text-align: center; margin: 30px 0 20px 0;'>
                        <a href='{$loginUrl}' style='background: #f59e0b; color: #0f172a; font-weight: bold; text-decoration: none; padding: 12px 28px; border-radius: 6px; display: inline-block; font-size: 15px;'>Access Client Portal</a>
                    </div>
                    <p style='font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 16px; margin-bottom: 0;'>
                        CONFIDENTIALITY NOTICE: This transmission is privileged and intended only for the designated recipient.
                    </p>
                </div>
            </div>
            ";

            // Attempt Email Dispatch with fallback logging
            try {
                \Illuminate\Support\Facades\Mail::html($htmlBody, function ($msg) use ($client, $subject) {
                    $msg->to($client->email, $client->name)
                        ->subject($subject);
                });
                $emailSent = true;
            } catch (\Throwable $mailErr) {
                \Illuminate\Support\Facades\Log::warning("Welcome email dispatch fallback: " . $mailErr->getMessage());
                $emailSent = false;
            }

            \App\Models\SystemAuditLog::logAction('WELCOME_EMAIL_SENT', "Sent custom welcome email to {$client->email}. Credentials included: " . ($includeCredentials ? 'Yes' : 'No'), auth()->id(), 'admin');

            // Telegram Notification
            try {
                $escapedName = htmlspecialchars($client->name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedEmail = htmlspecialchars($client->email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $escapedSubject = htmlspecialchars($subject, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $telMsg = "📧 <b>Custom Welcome & Onboarding Email Dispatched</b>\n\n"
                        . "👤 <b>Client:</b> {$escapedName}\n"
                        . "📧 <b>Email:</b> {$escapedEmail}\n"
                        . "📝 <b>Subject:</b> {$escapedSubject}\n"
                        . ($includeCredentials ? "🔑 <b>Temporary Credentials:</b> Included (PIN: 1234)\n" : "")
                        . (!empty($customNote) ? "💬 <b>Attorney/CPA Briefing:</b> " . htmlspecialchars(\Illuminate\Support\Str::limit($customNote, 120), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "\n" : "")
                        . "📅 <b>Time:</b> " . now()->format('Y-m-d H:i:s') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}

            $msg = $emailSent ? __('Welcome email successfully dispatched to client.') : __('Welcome email prepared & logged (check mail settings).');

            return redirect()->route('admin.user.client.index')
                ->with('success', $msg)
                ->with('generated_credentials', $includeCredentials ? [
                    'client_id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'temp_password' => $rawPassword,
                    'default_pin' => $defaultPin,
                ] : null);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function impersonateClient($id)
    {
        try {
            $user = User::findOrFail($id);

            $currentAdmin = Auth::user();

            // Store original admin session state if not already impersonating
            if (!session()->has('impersonator_admin')) {
                if (!$currentAdmin || ($currentAdmin->hasRole('client') && !$currentAdmin->hasAnyRole(['admin', 'attorney', 'staff']))) {
                    abort(403, 'Unauthorized action.');
                }

                session([
                    'impersonator_admin' => [
                        'id' => $currentAdmin->id,
                        'name' => $currentAdmin->name,
                        'email' => $currentAdmin->email,
                        'role' => $currentAdmin->roles->first()?->name ?? 'admin',
                    ]
                ]);
            }

            $adminId = session('impersonator_admin.id');
            $adminName = session('impersonator_admin.name');

            // Replicate frontfield-remodel bypass and impersonation keys
            session([
                'admin_login_as_bypass' => true,
                'impersonated_by' => $adminId,
                'two_factor_verified' => true,
            ]);
            session()->forget('session_locked');

            // Ensure client role is present if target is a client
            if (!$user->hasRole('client') && !$user->hasAnyRole(['admin', 'attorney'])) {
                $user->assignRole('client');
            }

            // Ensure email_verified_at is set so Laravel's verified middleware never blocks
            if (is_null($user->email_verified_at)) {
                $user->email_verified_at = now();
                $user->save();
            }

            \App\Models\SystemAuditLog::logAction('IMPERSONATE_CLIENT', "Staff member ({$adminName}) started viewing portal as user #{$user->id} ({$user->email}).", $adminId, 'admin');

            Auth::loginUsingId($user->id);

            if ($user->hasRole('staff') && !$user->hasRole('client')) {
                return redirect()->route('staff.dashboard')->with('success', __('You are now viewing the portal as ') . $user->name);
            }

            return redirect()->route('client.dashboard')->with('success', __('You are now viewing the portal as ') . $user->name);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function stopImpersonation()
    {
        try {
            $adminId = session('impersonator_admin.id') ?? session('impersonated_by');

            if ($adminId) {
                session()->forget(['impersonator_admin', 'impersonated_by', 'admin_login_as_bypass', 'session_locked']);

                Auth::loginUsingId($adminId);

                \App\Models\SystemAuditLog::logAction('EXIT_IMPERSONATION', "Staff exited impersonation session.", $adminId, 'admin');

                return redirect()->route('admin.user.client.index')->with('success', __('Exited impersonation session successfully. Returned to Admin Portal.'));
            }

            if (Auth::check() && !Auth::user()->hasRole('client')) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('admin.login');
        } catch (\Throwable $e) {
            return redirect()->route('admin.login');
        }
    }
}
