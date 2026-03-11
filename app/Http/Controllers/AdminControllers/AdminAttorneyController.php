<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Attorney;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class AdminAttorneyController extends Controller
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

    public function index()
    {
        try {
            return view('backend.pages.attorney.index', [
                'title' => 'Attorney',
                'attorneys' => Attorney::all()
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $designation = Designation::all();
            return view('backend.pages.attorney.form', [
                'title' => 'Add New Attorney',
                'attorney' => null,
                'designation' => $designation
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        $validate = [
            'designation_id' => 'required',
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'address' => ['required', 'string'],
            'password' => ['required', 'confirmed']
        ];
        $user_data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' =>  bcrypt($request->password)
        ];

        $validator = Validator::make($request->all(), $validate, [
            'designation_id.required' => 'Designation id is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // for validation check
            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];
            if ($request->hasFile('image')) {
                foreach ($request->image as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return $this->backWithSuccess('Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }

            $attorney_user = User::create($user_data);
            $attorney_user->assignRole('attorney');

            $attorney = new Attorney();
            if ($request->hasFile('image')) {

                // insert new file
                $images = $request->image;
                foreach ($images as $image) {
                    //image name
                    $filename = time() . $image->getClientOriginalName();
                    //upload image
                    $image->move(public_path('/upload/attorneys'), $filename);
                }
                $attorney->image = $filename;
            }
            $attorney->user_id = $attorney_user->id;
            $attorney->name = $request->name;
            $attorney->designation_id =  $request->designation_id;
            $attorney->phone =  $request->phone;
            $attorney->email =  $request->email;
            $attorney->fax =  $request->fax;
            $attorney->address =  $request->address;
            $attorney->description =  $request->description;
            $attorney->education =  $request->education;
            $attorney->professional_exp =  $request->professional_exp;
            $attorney->legal_exp =  $request->legal_exp;
            $attorney->status = $request->status == 'on' ? true : false;
            $attorney->save();
            return $this->backWithSuccess('Attorney  created successfully, & Attorney login default password is 123456789');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $attorney = Attorney::findOrfail($id);
            $designation = Designation::all();
            return view('backend.pages.attorney.form', [
                'title' => 'Edit Attorney',
                'attorney' => $attorney,
                'designation' => $designation
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {

            $attorney = Attorney::findOrfail($id);
            $validate = [
                'designation_id' => ['required'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email_address'],
                'phone' => ['required', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
                'address' => ['required', 'string'],
                'password' => ['confirmed']
            ];

            if ($attorney->user) {
                $validate['email'] = ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $attorney->user->id];
            }

            $validator = Validator::make($request->all(), $validate, [
                'designation_id.required' => 'Designation id is required.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            // for validation check
            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];
            if ($request->hasFile('image')) {
                foreach ($request->image as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return $this->backWithSuccess('Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }

            $user_data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address
            ];
            if (!empty($request->password)) {
                $user_data['password'] = bcrypt($request->password);
            }

            if (!$attorney->user) {
                $attorney_user = User::create($user_data);
                $attorney_user->assignRole('attorney');

                $attorney->user_id = $attorney_user->id;
            } else {
                User::where(['id' => $attorney->user_id])->update($user_data);
            }


            if ($request->hasFile('image')) {

                // insert new file
                $images = $request->image;
                foreach ($images as $image) {
                    //image name
                    $filename = time() . $image->getClientOriginalName();
                    //upload image
                    $image->move(public_path('/upload/attorneys'), $filename);
                }
                $attorney->image = $filename;
            }
            $attorney->name = $request->name;
            $attorney->designation_id =  $request->designation_id;
            $attorney->phone =  $request->phone;
            $attorney->email =  $request->email;
            $attorney->fax =  $request->fax;
            $attorney->address =  $request->address;
            $attorney->description =  $request->description;
            $attorney->education =  $request->education;
            $attorney->professional_exp =  $request->professional_exp;
            $attorney->legal_exp =  $request->legal_exp;
            $attorney->status = $request->status == 'on' ? true : false;
            $attorney->save();
            return $this->backWithSuccess('Attorney updated successfully.');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $attorney = Attorney::findOrfail($id);
            if ($attorney->image != null) {
                // delete existing image
                $file = 'upload/attorneys/' . $attorney->image;
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                }
            }
            $attorney->delete();
            return $this->backWithSuccess('Attorney has been deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
