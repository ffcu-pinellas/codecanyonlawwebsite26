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
            return view('backend.pages.users.client.index', [
                'title' => 'All Clients',
                'clients'=> User::role('client')->get()
            ]);
        }catch (\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
