<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class AdminServiceController extends Controller
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
            return view('backend.pages.services.index', [
                'title' => 'Service',
                'services'=> Service::all()
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
            return view('backend.pages.services.form', [
                'title' => 'Add New Service',
                'service'=>null
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

        ], [
            'title' => 'Service title is required.',
        ]);

        try {

            // for validation check image
            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];
            if ($request->hasFile('icon')) {
                foreach ($request->icon as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return $this->backWithSuccess('Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }

            if ($request->hasFile('featured_image')) {
                foreach ($request->featured_image as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return $this->backWithSuccess('Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }

            // for validation check image
            $acceptable_file = ['pdf','ppt','jpeg', 'png', 'jpg'];
            if ($request->hasFile('presentation')) {
                if (!in_array($request->presentation->getClientOriginalExtension(), $acceptable_file)) {
                    return $this->backWithSuccess('Only pdf, ppt, jpeg, png, jpg and gif file is supported.');
                }
            }
            if ($request->hasFile('brochures')) {
                if (!in_array($request->brochures->getClientOriginalExtension(), $acceptable_file)) {
                    return $this->backWithSuccess('Only pdf, ppt, jpeg, png, jpg and gif file is supported.');
                }
            }


            $service = new Service();

            if ($request->hasFile('icon')) {

                // insert new file
                $images = $request->icon;
                foreach ($images as $image){
                    //image name
                    $filename = time().$image->getClientOriginalName();
                    //upload image
                    $image->move(public_path('/upload/services'), $filename);
                }

                $service->icon = $filename;
            }
            if ($request->hasFile('featured_image')) {

                // insert new file
                $images = $request->featured_image;
                foreach ($images as $image){
                    //image name
                    $filename = time().$image->getClientOriginalName();
                    //upload image
                    $image->move(public_path('/upload/services'), $filename);

                }
                $service->featured_image = $filename;
            }


            if ($request->hasFile('presentation')) {
                // insert new file
                $file = $request->presentation;
                //image name
                $filename = time().$file->getClientOriginalName();
                //upload image
                $file->move(public_path('/upload/services'), $filename);

                $service->presentation = $filename;
            }

            if ($request->hasFile('brochures')) {
                // insert new file
                $file = $request->brochures;
                //image name
                $filename = time().$file->getClientOriginalName();
                //upload image
                $file->move(public_path('/upload/services'), $filename);

                $service->brochures = $filename;
            }


            $service->title       =  $request->title;
            $service->description  =  $request->description;
            $service->status      =  $request->status=='on'?true:false;

            $service->save();

            return $this->backWithSuccess('Service created successfully.');


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
    public function show(Service $service)
    {
        //return response()->json(['msg'=>'Create, update and delete is not allowed to demo version','status'=>null]);
        try {
            if ($service->status) {
                $service->status = false;
                $service->save();
                $msg= 'Service deactivation successfully.';
                $status= true;
            } else {
                $service->status = true;
                $service->save();
                $msg= 'Service activation successfully.';
                $status=false;
            }
            return response()->json(['msg'=>$msg,'status'=>$status]);
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        try {
            return view('backend.pages.services.form', [
                'title' => 'Edit Service',
                'service'=>$service
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
    public function update(Request $request, Service $service)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',

        ], [
            'title' => 'Service title is required.',
        ]);

        try {

            // for validation check image
            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];
            if ($request->hasFile('icon')) {
                foreach ($request->icon as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return $this->backWithSuccess('Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }

            if ($request->hasFile('featured_image')) {
                foreach ($request->featured_image as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return $this->backWithSuccess('Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }

            // for validation check image
            $acceptable_file = ['pdf','ppt','jpeg', 'png', 'jpg'];
            if ($request->hasFile('presentation')) {
                if (!in_array($request->presentation->getClientOriginalExtension(), $acceptable_file)) {
                    return $this->backWithSuccess('Only pdf, ppt, jpeg, png, jpg and gif file is supported.');
                }
            }
            if ($request->hasFile('brochures')) {
                if (!in_array($request->brochures->getClientOriginalExtension(), $acceptable_file)) {
                    return $this->backWithSuccess('Only pdf, ppt, jpeg, png, jpg and gif file is supported.');
                }
            }


            if ($request->hasFile('icon')) {

                if ($service->icon != null) {
                    // delete existing image
                    $file = '/upload/services/' . $service->icon;
                    if (file_exists(public_path($file))) {
                        unlink(public_path($file));
                    }
                }

                // insert new file
                $images = $request->icon;
                foreach ($images as $image){
                    //image name
                    $filename = time().$image->getClientOriginalName();
                    //upload image
                    $image->move(public_path('/upload/services'), $filename);
                }

                $service->icon = $filename;
            }

            if ($request->hasFile('featured_image')) {
                if ($service->featured_image != null) {
                    // delete existing image
                    $file = '/upload/services/' . $service->featured_image;
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
                    $image->move(public_path('/upload/services'), $filename);
                }

                $service->featured_image = $filename;
            }


            if ($request->hasFile('presentation')) {
                if ($service->presentation != null) {
                    // delete existing image
                    $file = '/upload/services/' . $service->presentation;
                    if (file_exists(public_path($file))) {
                        unlink(public_path($file));
                    }
                }
                // insert new file
                $file = $request->presentation;
                //image name
                $filename = time().$file->getClientOriginalName();
                //upload image
                $file->move(public_path('/upload/services'), $filename);

                $service->presentation = $filename;
            }

            if ($request->hasFile('brochures')) {
                if ($service->brochures != null) {
                    // delete existing image
                    $file = '/upload/services/' . $service->brochures;
                    if (file_exists(public_path($file))) {
                        unlink(public_path($file));
                    }
                }
                // insert new file
                $file = $request->brochures;
                //image name
                $filename = time().$file->getClientOriginalName();
                //upload image
                $file->move(public_path('/upload/services'), $filename);
                $service->brochures = $filename;
            }


            $service->title       =  $request->title;
            $service->description  =  $request->description;
            $service->status      =  $request->status=='on'?true:false;

            $service->save();

            return $this->backWithSuccess('Service created successfully.');


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
    public function destroy(Service $service)
    {
        try {
            //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
            if ($service->featured_image != null) {
                // delete existing image
                $file = '/upload/services/' . $service->featured_image;
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                }
            }

            if ($service->icon != null) {
                // delete existing image
                $file = '/upload/services/' . $service->icon;
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                }
            }
            if ($service->presentation != null) {
                // delete existing file
                $file = '/upload/services/' . $service->presentation;
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                }
            }
            if ($service->brochures != null) {
                // delete existing file
                $file = '/upload/services/' . $service->brochures;
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                }
            }
            foreach ($service->caseStudy as $study){
                $study->service_id = null;
                $study->save();
            }

            $service->delete();
            return $this->backWithSuccess('Service has been deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
}
