<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\Service;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class AdminCaseStudyController extends Controller
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
            return view('backend.pages.case-study.index', [
                'title' => 'Case Study',
                'caseStudies'=> CaseStudy::all()
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
            return view('backend.pages.case-study.form', [
                'title' => 'Add New CaseStudy',
                'caseStudy'=>null,
                'services'=> Service::where('status',true)->get()
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
            'title' => 'required|string|max:255',
            'description' => 'required',
            'problem_title' => 'required',
            'service_id' => 'required',

        ], [
            'title' => 'CaseStudy title is required.',
        ]);

        $service = Service::find($request->service_id);
        try {

            // for validation check image
            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];

            if ($request->hasFile('featured_image')) {
                foreach ($request->featured_image as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return $this->backWithSuccess('Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }

            $caseStudy = new CaseStudy();

            if ($request->hasFile('featured_image')) {

                // insert new file
                $images = $request->featured_image;
                foreach ($images as $image){
                    //image name
                    $filename = time().$image->getClientOriginalName();
                    //upload image
                    $image->move(public_path('/upload/case-study'), $filename);

                }

                $caseStudy->featured_image = $filename;
            }

            $caseStudy->service_id       =  $service->id;
            $caseStudy->service_title       =  $service->title;
            $caseStudy->title       =  $request->title;
            $caseStudy->description  =  $request->description;
            $caseStudy->problem_title  =  $request->problem_title;
            $caseStudy->problem_description  =  $request->problem_description;
            $caseStudy->solution_title  =  $request->solution_title;
            $caseStudy->solution_description  =  $request->solution_description;
            $caseStudy->result_title  =  $request->result_title;
            $caseStudy->result_description  =  $request->result_description;
            $caseStudy->save();

            return $this->backWithSuccess('CaseStudy created successfully.');


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
    public function edit(CaseStudy $casestudy)
    {
        try {
            return view('backend.pages.case-study.form', [
                'title' => 'Edit CaseStudy',
                'caseStudy'=>$casestudy,
                'services'=> Service::where('status',true)->get()
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
    public function update(Request $request, CaseStudy $casestudy)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'service_id' => 'required',

        ], [
            'title' => 'CaseStudy title is required.',
        ]);
        $service = Service::find($request->service_id);
        try {

            // for validation check image
            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];

            if ($request->hasFile('featured_image')) {
                foreach ($request->featured_image as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return $this->backWithSuccess('Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }




            if ($request->hasFile('featured_image')) {
                if ($casestudy->featured_image != null) {
                    // delete existing image
                    $file = '/upload/case-study/' . $casestudy->featured_image;
                    if (file_exists(public_path($file))) {
                        unlink(public_path($file));
                    }
                }
                // insert new file
                $images = $request->featured_image;
                foreach ($images as $image){
                    //image name
                    $filename = time().$image->getClientOriginalName();
                    //upload image
                    $image->move(public_path('/upload/case-study'), $filename);

                }

                $casestudy->featured_image = $filename;
            }




            $casestudy->service_id       =  $service->id;
            $casestudy->service_title       =  $service->title;
            $casestudy->title       =  $request->title;
            $casestudy->description  =  $request->description;
            $casestudy->problem_title  =  $request->problem_title;
            $casestudy->problem_description  =  $request->problem_description;
            $casestudy->solution_title  =  $request->solution_title;
            $casestudy->solution_description  =  $request->solution_description;
            $casestudy->result_title  =  $request->result_title;
            $casestudy->result_description  =  $request->result_description;

            $casestudy->save();

            return $this->backWithSuccess('CaseStudy Updated successfully.');


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
    public function destroy(CaseStudy $casestudy)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {

            if ($casestudy->featured_image != null) {
                // delete existing image
                $file = '/upload/case-study/' . $casestudy->featured_image;
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                }
            }

            $casestudy->delete();

            return $this->backWithSuccess('CaseStudy has been deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
