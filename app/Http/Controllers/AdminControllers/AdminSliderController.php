<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class AdminSliderController extends Controller
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
            return view('backend.pages.sliders.index', [
                'title' => 'Slider',
                'sliders'=> Slider::all()
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
            return view('backend.pages.sliders.form', [
                'title' => 'Add New Slider',
                'slider'=>null
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
            'sub_title' => 'required|string|max:255',
            'button_name' => 'required|string|max:255',
            'button_url' => 'required|string|max:255',
            'description' => 'required',
            'bg_image' => 'required',

        ], [
            'title' => 'Slider title is required.',
        ]);

        try {

            // for validation check
            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];
            if ($request->hasFile('bg_image')) {
                foreach ($request->bg_image as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return $this->backWithSuccess('Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }


            $slider = new Slider();

            if ($request->hasFile('bg_image')) {

                // insert new file
                $images = $request->bg_image;
                foreach ($images as $image){
                    //image name
                    $filename = time().$image->getClientOriginalName();
                    //upload image
                    $image->move(public_path('/upload/sliders'), $filename);

                }
                $slider->bg_image = $filename;
            }
            $url = \Mews\Purifier\Facades\Purifier::clean($request->button_url, function (HTMLPurifier_Config $config) {
                $uri = $config->getDefinition('URI');
                $uri->addFilter(new HTMLPurifier_URIFilter_NameOfFilter(), $config);
            });

            $slider->title       =  $request->title;
            $slider->sub_title    =  $request->sub_title;
            $slider->button_name =  $request->button_name;
            $slider->button_url  =  $url;
            $slider->description  =  $request->description;
            $slider->status      =  $request->status=='on'?true:false;

            $slider->save();

            return $this->backWithSuccess('Slider created successfully.');


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
    public function show(Slider $slider)
    {
        //return response()->json(['msg'=>'Create, update and delete is not allowed to demo version','status'=>null]);
        try {
            if ($slider->status) {
                $slider->status = false;
                $slider->save();
                $msg= 'Slider deactivation successfully.';
                $status= true;
            } else {
                $slider->status = true;
                $slider->save();
                $msg= 'Slider activation successfully.';
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
    public function edit($id)
    {

        try {
            $slider = Slider::findOrfail($id);
            return view('backend.pages.sliders.form', [
                'title' => 'Edit Slider',
                'slider'=>$slider
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
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'button_name' => 'required|string|max:255',
            'button_url' => 'required|string|max:255',
            'description' => 'required',
            'bg_image' => 'nullable',

        ], [
            'title' => 'Slider title is required.',
        ]);

        try {

            $slider = Slider::findOrfail($id);

            // for validation check
            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];
            if ($request->hasFile('bg_image')) {
                foreach ($request->bg_image as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return $this->backWithSuccess('Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }


            if ($request->hasFile('bg_image')) {

                // delete if file exist
                if ($slider->bg_image != null) {
                    // delete existing image
                    $file = '/upload/sliders/' . $slider->bg_image;
                    if (file_exists(public_path($file))) {
                        unlink(public_path($file));
                    }
                }
                // insert new file
                $images = $request->bg_image;
                foreach ($images as $image){
                    //image name
                    $filename = time().$image->getClientOriginalName();
                    //upload image
                    $image->move(public_path('/upload/sliders'), $filename);
                }
                $slider->bg_image = $filename;
            }

            $slider->title       =  $request->title;
            $slider->sub_title    =  $request->sub_title;
            $slider->button_name =  $request->button_name;
            $slider->button_url  =  $request->button_url;
            $slider->description  =  $request->description;
            $slider->status      =  $request->status=='on'?true:false;

            $slider->save();

            return $this->backWithSuccess('Slider updated successfully.');


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
    public function destroy(Slider $slider)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {

            if ($slider->bg_image != null) {
                // delete existing image
                $file = 'upload/sliders/' . $slider->bg_image;
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                }
            }
            $slider->delete();
            return $this->backWithSuccess('has been deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
