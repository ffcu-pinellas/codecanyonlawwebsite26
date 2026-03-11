<?php

namespace App\Http\Controllers\MenuSettings;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $title = 'Menu Settings';
            $menus = MenuCategory::all();
            return view('backend.pages.settings.menu-settings.menu-category.index', compact('title', 'menus'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255', 'unique:menu_categories']
        ]);
        try {
            MenuCategory::create([
                'name' => $request->name
            ]);
            return $this->backWithSuccess('Menu has been added successfully....');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuCategory $category)
    {
        try {
            $updateform = '<div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="' . route('admin.menu.category.update', $category->id) . '" method="post">
                        <div class="modal-body">
                        <input type="hidden" name="_token" value="' . csrf_token() . '" />' .
                method_field('PUT')
                . '<div class="form-group">
                                <p for="menuCategoryName" class="card-title">Name</p>
                                <input type="text" name="name" id="menuCategoryName" class="form-control" value="' . $category->name . '">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger rounded">Save changes</button>
                        </div>
                    </form>
                </div>';
            return response()->json($updateform);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenuCategory $category)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255', 'unique:menu_categories', Rule::unique('menu_categories')->ignore($category->id)]
        ]);
        try {
            $category->name = $request->name;
            $category->save();
            return $this->backWithSuccess('Menu has been updated successfully....');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MenuCategory $category)
    {
        try {
            $category->menus->each->delete();
            $category->delete();
            return $this->backWithSuccess($category->name . ' has been deleted successfully');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
