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
                $roles = Role::all();
                $userRoleName = $user->roles->isNotEmpty() ? $user->roles->pluck('name')[0] : '';

                $data = '
                    <div class="form-group">
                        <label for="modal_name">' . __('Full Name') . ' <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="modal_name" class="form-control" value="' . e($user->name) . '" required>
                    </div>
                    <div class="form-group">
                        <label for="modal_email">' . __('Email Address') . ' <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="modal_email" class="form-control" value="' . e($user->email) . '" required>
                    </div>
                    <div class="form-group">
                        <label for="modal_phone">' . __('Phone Number') . '</label>
                        <input type="text" name="phone" id="modal_phone" class="form-control" value="' . e($user->phone) . '">
                    </div>
                    <div class="form-group">
                        <label for="modal_address">' . __('Residential Address') . '</label>
                        <input type="text" name="address" id="modal_address" class="form-control" value="' . e($user->address) . '">
                    </div>
                    <div class="form-group">
                        <label for="modal_role">' . __('Role') . ' <span class="text-danger">*</span></label>
                        <select name="role" id="modal_role" class="form-control" required>
                            <option value="">' . __('Select') . '</option>';
                foreach ($roles as $role) {
                    $selected = ($role->name === $userRoleName) ? 'selected' : '';
                    $data .= '<option ' . $selected . ' value="' . e($role->name) . '">' . e($role->name) . '</option>';
                }
                $data .= '
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="modal_password">' . __('Password') . ' <small class="text-muted">(' . __('Leave blank to keep current') . ')</small></label>
                        <input type="password" name="password" id="modal_password" class="form-control" placeholder="' . __('Enter new password') . '">
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
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|string|min:8',
        ]);

        try {
            $user = User::findOrFail($request->id);
            
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone ?: '',
                'address' => $request->address ?: '',
            ];

            if ($request->filled('password')) {
                $userData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
            }

            $user->update($userData);
            $user->syncRoles($request->role);

            return $this->backWithSuccess(__('User details updated successfully.'));
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
            $recentLeads = \App\Models\Contact::orderBy('id', 'desc')->limit(30)->get();

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

            \App\Models\SystemAuditLog::logAction('GENERATE_CREDENTIALS', "Generated new temporary credentials for client #{$client->id} ({$client->email}).", auth()->id(), 'admin');

            return redirect()->route('admin.user.client.index')
                ->with('success', __('Temporary login credentials regenerated for ') . $client->name)
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
                                <td style='padding: 6px 0;'><span style='background: #0f172a; color: #f59e0b; font-family: monospace; font-size: 15px; font-weight: bold; padding: 4px 10px; border-radius: 4px; display: inline-block;'>{$rawPassword}</span></td>
                            </tr>
                        </table>
                    </div>

                    <div style='text-align: center; margin: 28px 0;'>
                        <a href='{$loginUrl}' style='background: #f59e0b; color: #0f172a; text-decoration: none; font-weight: bold; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; padding: 14px 30px; border-radius: 6px; display: inline-block;'>
                            ACCESS CLIENT PORTAL
                        </a>
                    </div>

                    <div style='background: #fffbeb; border: 1px solid #fef3c7; border-radius: 6px; padding: 12px 16px; margin: 20px 0; font-size: 12px; color: #92400e;'>
                        <strong>Security Notice:</strong> Upon your first login, a security setup wizard will guide you to set a permanent password and establish your private 4-digit Security PIN.
                    </div>
                ";
            }

            $htmlBody .= "
                    <p style='color: #64748b; font-size: 12px; margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 16px;'>
                        If you have any questions or require assistance, please reply directly to this email or reach our office directly.
                    </p>
                </div>
            </div>
            ";

            try {
                \Illuminate\Support\Facades\Mail::html($htmlBody, function ($message) use ($client, $subject, $siteName) {
                    $message->to($client->email, $client->name)
                        ->subject($subject);
                });
            } catch (\Throwable $mailEx) {
                \Log::error('Welcome email dispatch error: ' . $mailEx->getMessage());
            }

            \App\Models\SystemAuditLog::logAction('SEND_WELCOME_EMAIL', "Dispatched custom welcome email to client #{$client->id} ({$client->email}).", auth()->id(), 'admin');

            return redirect()->route('admin.user.client.index')
                ->with('success', __('Custom welcome email dispatched to ') . $client->email)
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
            $client = User::findOrFail($id);

            // Save admin session state
            session([
                'impersonator_admin' => [
                    'id' => auth()->id(),
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'role' => auth()->user()->roles->first()?->name ?? 'admin',
                ]
            ]);

            \App\Models\SystemAuditLog::logAction('IMPERSONATE_CLIENT', "Staff started impersonating client #{$client->id} ({$client->email}).", auth()->id(), 'admin');

            \Illuminate\Support\Facades\Auth::loginUsingId($client->id);

            return redirect()->route('client.dashboard')->with('success', __('You are now viewing the portal as ') . $client->name);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function stopImpersonation()
    {
        try {
            if (session()->has('impersonator_admin')) {
                $adminId = session('impersonator_admin.id');
                session()->forget('impersonator_admin');

                \Illuminate\Support\Facades\Auth::loginUsingId($adminId);

                \App\Models\SystemAuditLog::logAction('EXIT_IMPERSONATION', "Staff exited impersonation session.", $adminId, 'admin');

                return redirect()->route('admin.user.client.index')->with('success', __('Exited impersonation session successfully.'));
            }

            return redirect()->route('login');
        } catch (\Throwable $e) {
            return redirect()->route('login');
        }
    }
}
