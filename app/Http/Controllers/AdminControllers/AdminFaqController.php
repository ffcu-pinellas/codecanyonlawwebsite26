<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
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
            return view('backend.pages.faq.index', [
                'title' => 'Faq',
                'faq'=> Faq::all()
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
            return view('backend.pages.faq.form', [
                'title' => 'Add New faq',
                'faq'=>null
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
            'question' => 'required',
            'answer' => 'required',
        ], [
            'question' => 'Question title is required.',
            'answer' => 'Answer title is required.',
        ]);
        try {
            $faq = new Faq();
            $faq->question= $request->question;
            $faq->answer= $request->answer;
            $faq->save();
            return $this->backWithSuccess('Faq created successfully.');
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
            $faq = Faq::findOrfail($id);
            return view('backend.pages.faq.form', [
                'title' => 'Edit Faq',
                'faq'=>$faq
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
            $faq = Faq::findOrfail($id);
            $faq->question= $request->question;
            $faq->answer= $request->answer;
            $faq->save();
            return $this->backWithSuccess('Faq updated successfully.');
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
            $designation = Faq::findOrfail($id);
            $designation->delete();
            return $this->backWithSuccess('Faq has been deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
