<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class AdminTagController extends Controller
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
            return view('backend.pages.blogs.tag', [
                'title' => 'Tag',
                'tags' => Tag::all(),
            ]);
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        try {
            $this->validate($request, [
                'name' => 'required|string|max:255'
            ]);
            $tag = Tag::create([
                'name' => $request->input('name')
            ]);
            return $this->backWithSuccess($tag->name . ' has been created successfully');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        try {
            $output = '<div class="modal-header">
                    <h5 class="modal-title card-title" id="blogTagModalLongTitle">' . __('Edit ' . htmlspecialchars($tag->name) . ' tag') . '</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="' . route('admin.blog.tag.update', $tag->id) . '" method="post" id="blogTagForm">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="_token" value="' . @csrf_token() . '">
                <div class="modal-body">
                        <p class="card-title"><label for="name">' . __('Tag Name') . '</label></p>
                        <div class="form-group">
                            <input type="text" name="name" id="name" class="form-control" required value="' . htmlspecialchars($tag->name) . '">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">' . __('Save') . '</button>
                    </div>
                </form>';
            return response()->json($output);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $this->validate($request, [
                'name' => 'required|string|max:255'
            ]);
            $tag->name = $request->name;
            $tag->save();
            return $this->backWithSuccess( $tag->name . ' tag has been updated successfully');
        } catch (\Exception $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $tag->delete();
            return $this->backWithSuccess($tag->name . ' tag has been deleted successfully');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
