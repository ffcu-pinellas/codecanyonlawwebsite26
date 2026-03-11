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
                $roles = Role::all();

                $data = '
                    <p>' . __('Name: ') . $user->name . '</p>
                    <p>' . __('Email: ') . $user->email . '</p>
                    <p>' . __('Phone: ') . $user->phone . '</p>
                    <p>' . __('Address: ') . $user->address . '</p>
                    <div class="form-group">
                        <label for="">' . __('Role') . '</label>   
                        <select name="role" class="form-control" required>
                        <option value="">' . __('Select') . '</option>
                        ';
                foreach ($roles as $role) {
                    $data .= '<option ' . ($role->name == $user->roles->pluck('name')[0] ? 'selected' : '') . ' value="' . $role->name . '">' . $role->name . '</option>';
                }

                $data .= '</select>
                <input type="hidden" name="id" value="' . $user->id . '">
                
                    </div>
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
        try {
            User::where(['id' => $request->id])->assignRole($request->role);

            return $this->backWithSuccess(__('User update successfully.'));
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
                'clients'=> User::role('admin')->get()
            ]);
        }catch (\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
