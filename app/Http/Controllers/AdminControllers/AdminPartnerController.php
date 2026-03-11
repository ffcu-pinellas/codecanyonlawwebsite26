<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Partners;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class AdminPartnerController extends Controller
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
            return view('backend.pages.partners.index', [
                'title' => 'Partner',
                'partners'=> Partners::all()
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
            return view('backend.pages.partners.form', [
                'title' => 'Add New Partner',
                'partner'=>null
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
            'url' => 'required',
        ], [
            'name' => 'Slider title is required.',
            'url' => 'URL title is required.',
        ]);
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
            $partner = new Partners();
            if ($request->hasFile('image')) {
                // insert new file
                $images = $request->image;
                foreach ($images as $image){
                    //image name
                    $filename = time().$image->getClientOriginalName();
                    //upload image
                    $image->move(public_path('/upload/partners'), $filename);
                }

                $partner->image = $filename;
            }
            $partner->name= $request->name;
            $partner->url=  $request->url;
            $partner->status = $request->status=='on'?true:false;
            $partner->save();
            return $this->backWithSuccess('Partner created successfully.');
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
            $partner = Partners::findOrfail($id);
            return view('backend.pages.partners.form', [
                'title' => 'Edit Partner',
                'partner'=>$partner
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
            'name' => 'required|string|max:255',
            'url' => 'required',
        ], [
            'name' => 'Slider title is required.',
            'url' => 'URL title is required.',
        ]);
        try {
            $partner =Partners::findOrfail($id);
            // for validation check
            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];
            if ($request->hasFile('image')) {
                foreach ($request->image as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return $this->backWithSuccess('Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }
            if ($request->hasFile('image')) {

                // insert new file
                $images = $request->image;
                foreach ($images as $image){
                    //image name
                    $filename = time().$image->getClientOriginalName();
                    //upload image
                    $image->move(public_path('/upload/partners'), $filename);

                }

                $partner->image = $filename;
            }
            $partner->name= $request->name;
            $partner->url=  $request->url;
            $partner->status= $request->status=='on'?true:false;
            $partner->save();
            return $this->backWithSuccess('Partner created successfully.');
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
        $partners = Partners::findOrfail($id);
            if ($partners->image != null) {
                // delete existing image
                $file = 'upload/partners/' . $partners->image;
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                }
            }
            $partners->delete();
            return $this->backWithSuccess('Partner has been deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
