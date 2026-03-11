<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\DynamicPage;
use App\Models\Href;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class DynamicPageController extends Controller
{
    public function index($slug = null){
        try {
            $admin_user = Auth::user();
            $page = DynamicPage::where('slug', $slug)->first();
            $title = $page ? ucwords(str_replace('-', ' ', $page->name)) : 'Add New Page';

            return view('backend.pages.dynamic-page.dynamic_page_from', compact('page', 'title', 'admin_user'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function store(Request $request, $slug = null)
    {
       //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $request->validate([
                'page_title' => 'required'
            ], [
                'page_title.required' => 'Page title is required.'
            ]);
            $pageContent = $request->page_content;

            $page = DynamicPage::where('slug', $slug)->first();

            $haveSlug = DynamicPage::where('slug', $request->slug)->first() ? DynamicPage::where('slug', $request->slug)->first() : (object)['id' => 0];

            if ($page) {
                if ($haveSlug->id != $page->id) {
                    if ($haveSlug->id > 0) {
                        return redirect()->back()->with('error', 'This Slug is already exist, please choose another text...');
                    } else {
                        $inputSlug = str_replace('?', '', $request->slug);
                        $inputSlug = str_replace(',', '', $inputSlug);
                        $inputSlug = str_replace(' ', '', $inputSlug);
                        $inputSlug = str_replace('#', '', $inputSlug);
                        $inputSlug = str_replace('\'', '', $inputSlug);
                        $inputSlug = str_replace('/', '', $inputSlug);
                    }
                } else {
                    $inputSlug = $request->slug;
                }
            } else {
                if ($haveSlug->id > 0) {
                    return redirect()->back()->with('error', 'This Slug is already exist, please choose another text...');
                } else {
                    $inputSlug = str_replace('?', '', $request->slug);
                    $inputSlug = str_replace(',', '', $inputSlug);
                    $inputSlug = str_replace(' ', '', $inputSlug);
                    $inputSlug = str_replace('#', '', $inputSlug);
                    $inputSlug = str_replace('\'', '', $inputSlug);
                    $inputSlug = str_replace('/', '', $inputSlug);
                }
            }

            if ($request->slug == null) {
                $inputSlug = strtolower(str_replace(' ', '-', $request->page_title));
                $slugExist = DynamicPage::where('slug', $inputSlug)->first();
                if(!empty($slugExist)){
                    return redirect()->back()->with('error', 'This Slug is already exist, please choose another text...');
                }
            }
            if ($page == null) {
                $page = new DynamicPage();
                $page->name = strtolower(str_replace(' ', '-', $request->page_title));
                if ($request->hasFile('breadcrumb_bg')) {
                    foreach ($request->breadcrumb_bg as $img){
                        //image name
                        $filename = time().$img->getClientOriginalName();
                        //upload image
                        $img->move(public_path('/upload/bg-image-dynamic-page'), $filename);
                        //save to database
                        $path = "/upload/bg-image-dynamic-page/".$filename;
                        $page->breadcrumb_bg=$path;
                    }
                }
                // create page url for frontend dynamic menu
                $href = new Href();
                $href->page_name = $request->page_title;
                $href->href = 'pages/'.$inputSlug;
                $href->save();

            }elseif ($page != null){
                if ($request->hasFile('breadcrumb_bg')){
                    if(!empty($page->breadcrumb_bg)){
                        unlink(public_path($page->breadcrumb_bg));
                    }
                    foreach ($request->breadcrumb_bg as $img){
                        //image name
                        $filename = time().$img->getClientOriginalName();
                        //upload image
                        $img->move(public_path('/upload/bg-image-dynamic-page'), $filename);
                        //save to database
                        $path = "/upload/bg-image-dynamic-page/".$filename;
                        $page->breadcrumb_bg=$path;
                    }
                }
                // update page url for frontend dynamic menu
                $href = Href::where('href', 'pages/'.$page->slug)->first();
                $href->page_name = $request->page_title;
                $href->href = 'pages/'.$inputSlug;
                $href->save();

            }

            $page->title = $request->page_title;
            $page->sub_title = $request->sub_title;
            $page->page_content = $pageContent;
            $page->meta_keyword = $request->meta_keyword;
            $page->meta_description = $request->meta_description;
            $page->slug = $inputSlug;

            if ($request->has('status')) {
                $page->status = true;
            } else {
                $page->status = false;
            }
            $page->save();

            return redirect()->back()->with('success', 'Page has been created successfully');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function pageDestroy($slug)
    {
       //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $page = DynamicPage::where('slug', $slug)->first();
            if (!empty($page) && !empty($page->breadcrumb_bg)){
                unlink(public_path($page->breadcrumb_bg));
            }
            $page->delete();
            // delete page harf
            $href = Href::where('href', 'pages/'.$slug)->first();
            $href->delete();

            return $this->backWithSuccess('Page has been Deleted successfully');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
