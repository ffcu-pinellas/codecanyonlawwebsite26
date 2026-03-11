<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;

class AdminDesignationController extends Controller
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
            return view('backend.pages.designation.index', [
                'title' => 'Designation',
                'designation'=> Designation::all()
            ]);
        }catch (\Throwable $e){
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
            return view('backend.pages.designation.form', [
                'title' => 'Add New Designation',
                'designation'=>null
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
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name' => 'Designation title is required.',
        ]);
        try {

            $designation = new Designation();
            $designation->name= $request->name;
            $designation->save();
            return $this->backWithSuccess('Designation created successfully.');
        }catch (\Exception $th){
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
            $designation = Designation::findOrfail($id);
            return view('backend.pages.designation.form', [
                'title' => 'Edit Designation',
                'designation'=>$designation
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
            $designation = Designation::findOrfail($id);
            $designation->name= $request->name;
            $designation->save();
            return $this->backWithSuccess('Designation created successfully.');
        }catch (\Exception $th){
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
            $designation = Designation::findOrfail($id);
            $designation->delete();
            return $this->backWithSuccess('Designation has been deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
