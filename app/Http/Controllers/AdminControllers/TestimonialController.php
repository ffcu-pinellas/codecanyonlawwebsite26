<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class TestimonialController extends Controller
{
    public function __construct()
    {
        Parent::__construct();
    }
    public function index(){
        try {
            $title = __('Testimonial');
            $values = Testimonial::all();
            return view('backend.pages.testimonial.index', compact('title','values'));
        }catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function form(){
        try {
            $title = __('Testimonial Form');
            return view('backend.pages.testimonial.form', compact('title'));
        }catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function store(Request $request)
    {
       //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];
            if ($request->hasFile('image')) {
                foreach ($request->image as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        $notification = array(
                            'message' => 'Only jpeg, png, jpg and gif file is supported.',
                            'alert-type' => 'error'
                        );
                        return back()->with($notification);
                    }
                }
            }
            $data = new Testimonial();
            if ($request->hasFile('image')) {
                $images = $request->image;
                foreach ($images as $img){
                    //image name
                    $filename = time().$img->getClientOriginalName();
                    //upload image
                    $img->move(public_path('/upload/testimonial'), $filename);
                    $data->image = $filename;
                }
            }

            $data->name = $request->name;
            $data->designation = $request->designation;
            $data->testimonial = $request->testimonial;
            $data->status= $request->status=='on'?true:false;
            $data->save();
            $notification = array(
                'message' => 'Testimonial has been added successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function edit($id){
        try {
            $title = __('Edit Testimonial');
            $values = Testimonial::findOrFail($id);
            return view('backend.pages.testimonial.edit', compact('title','values'));
        }catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function update(Request $request,$id)
    {
       //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];
            if ($request->hasFile('image')) {
                foreach ($request->image as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        $notification = array(
                            'message' => 'Only jpeg, png, jpg and gif file is supported.',
                            'alert-type' => 'error'
                        );
                        return back()->with($notification);
                    }
                }
            }
            $data = Testimonial::findOrFail($id);
            if ($request->hasFile('image')) {
                $images = $request->image;
                foreach ($images as $img){
                    //image name
                    $filename = time().$img->getClientOriginalName();
                    //upload image
                    $img->move(public_path('/upload/testimonial'), $filename);
                    $data->image = $filename;
                }
            }

            $data->name = $request->name;
            $data->designation = $request->designation;
            $data->testimonial = $request->testimonial;
            $data->status= $request->status=='on'?true:false;
            $data->save();
            $notification = array(
                'message' => 'Testimonial has been updated successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function delete($id)
    {
       //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
        $testimonial = Testimonial::findOrfail($id);
            if ($testimonial->image != null) {
                // delete existing image
                $file = 'upload/testimonial/' . $testimonial->image;
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                }
            }
            $testimonial ->delete();
            $notification = [
                'message' =>  'Testimonial has been deleted successfully..',
                'alert-type' => 'success'
            ];
            return back()->with($notification);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
