<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\CommentSettings;
use App\Models\HeaderSettings;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class AdminBlogController extends Controller
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
        try{
            $blogs = DB::table('blogs')
                ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                ->select('blogs.*'
                    , 'blog_categories.name as category_name'
                )
                ->get();
            return view('backend.pages.blogs.blog', [
                'title' => 'Blog',
                'blogs' => $blogs,
                'categories' => BlogCategory::all(),
            ]);
        }catch (\Exception $th) {
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
        try{
            $blog= null;
            return view('backend.pages.blogs.blog-form', [
                'title' => 'Blog',
                'blog' => $blog,
                'tags' => Tag::all(),
                'categories' => BlogCategory::all(),
            ]);
        }catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
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
        try{

            $this->validate($request, [
                'title' => 'required',
                'description' => 'required',
                'category_id' => 'required'
            ]);

            $blog = new Blog();

            // for thumb image check
            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];
            if ($request->hasFile('bg_image')) {
                foreach ($request->bg_image as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return back()->with('error','Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }


            if ($request->hasFile('bg_image')) {

                $images = $request->bg_image;
                foreach ($images as $img){
                    //image name
                    $filename = time().$img->getClientOriginalName();
                    //upload image
                    $img->move(public_path('/upload/blogs/bg-images'), $filename);
                }
                $blog->bg_image = $filename;
            }


            // for Feature image check
            if ($request->hasFile('feature_img')) {
                foreach ($request->feature_img as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return back()->with('error','Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }
            if ($request->hasFile('feature_img')) {

                $fimages = $request->feature_img;
                foreach ($fimages as $fimg){
                    //image name
                    $feature_image_name = time().$fimg->getClientOriginalName();
                    //upload image
                    $fimg->move(public_path('/upload/blogs/features'), $feature_image_name);
                }
                $blog->feature_img = $feature_image_name;
            }


            $blog->title = $request->title;
            $blog->user_id = $request->user_id;
            $blog->category_id = $request->category_id;
            $blog->description = $request->description;

            $blog->save();

            // add blog tags
            if($request->has('blog_tags')) {
                foreach ($request->blog_tags as $blog_tag) {
                    $nblog_tag = new BlogTag();
                    $nblog_tag->blog_id = $blog->id;
                    $nblog_tag->tag_id = $blog_tag;
                    $nblog_tag->save();
                }
            }

            return $this->backWithSuccess('Blog has been created successfully');

        }catch (\Exception $th) {
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
        //return response()->json(['msg'=>'Create, update and delete is not allowed to demo version','status'=>null]);
        try {
            $blog = Blog::findOrfail($id);

            if ($blog->is_popular) {
                $blog->is_popular = false;
                $blog->save();
                $msg= 'Blog popular successfully.';
                $status= true;
            } else {
                $blog->is_popular = true;
                $blog->save();
                $msg= 'Blog unpopular successfully.';
                $status=false;
            }
            return response()->json(['msg'=>$msg,'status'=>$status]);
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function makeFeatured($id)
    {
        //return response()->json(['msg'=>'Create, update and delete is not allowed to demo version','status'=>null]);
        try {
            $blog = Blog::findOrfail($id);

            if ($blog->is_featured) {
                $blog->is_featured = false;
                $blog->save();
                $msg= 'Blog featured successfully.';
                $status= true;
            } else {
                $blog->is_featured = true;
                $blog->save();
                $msg= 'Blog unfeatured successfully.';
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
        try{
            $fblog = Blog::findOrfail($id);

            $blog= $fblog?$fblog:null;
            if(!empty($blog)){
                $blog_tags = BlogTag::where('blog_id', $blog->id)->get();
            }else{
                $blog_tags = null;
            }
            return view('backend.pages.blogs.blog-form', [
                'title' => 'Blog',
                'blog' => $blog,
                'tags' => Tag::all(),
                'blog_tags' => $blog_tags,
                'categories' => BlogCategory::all(),
                'userName' => Auth::user(),
            ]);
        }catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
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
        try{
            $this->validate($request, [
                'title' => 'required',
                'description' => 'required',
                'category_id' => 'required'
            ]);

            $blog = Blog::findOrfail($id);

            $this->validate($request, [
                'title' => 'required',
                'description' => 'required',
                'category_id' => 'required'
            ]);

            // for thumb image check
            $acceptable = ['jpeg', 'png', 'jpg', 'gif'];
            if ($request->hasFile('bg_image')) {
                foreach ($request->bg_image as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return back()->with('error','Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }


            DB::beginTransaction();

            if ($request->hasFile('bg_image')) {

                // delete existing image
                $file = '/upload/blogs/bg-images/' . $blog->bg_image;
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                }

                $images = $request->bg_image;
                foreach ($images as $img){
                    //image name
                    $bg_image_name = time().$img->getClientOriginalName();
                    //upload image
                    $img->move(public_path('/upload/blogs/bg-images'), $bg_image_name);
                }

                $blog->bg_image = $bg_image_name;
            }


            // for Feature image check
            if ($request->hasFile('feature_img')) {
                foreach ($request->feature_img as $img) {
                    if (!in_array($img->getClientOriginalExtension(), $acceptable)) {
                        return back()->with('error','Only jpeg, png, jpg and gif file is supported.');
                    }
                }
            }


            if ($request->hasFile('feature_img')) {
                // delete existing image
                $file = '/upload/blogs/features/' . $blog->feature_img;
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                }
                $fimages = $request->feature_img;
                foreach ($fimages as $fimg){
                    //image name
                    $feature_image_name = time().$fimg->getClientOriginalName();
                    //upload image
                    $fimg->move(public_path('/upload/blogs/features'), $feature_image_name);
                }

                $blog->feature_img = $feature_image_name;
            }


            $blog->title = $request->title;
            $blog->category_id = $request->category_id;
            $blog->description = $request->description;

            $blog->save();

            $deleteTags = DB::table('blog_tags')->where('blog_id', $blog->id)->delete();
            if(!empty($request->blog_tags)){
                foreach ($request->blog_tags as $blog_tag) {
                    $nblog_tag = new BlogTag();
                    $nblog_tag->blog_id = $blog->id;
                    $nblog_tag->tag_id = $blog_tag;
                    $nblog_tag->save();
                }
            }

            DB::commit();

            return $this->backWithSuccess('Blog has been created successfully');
        }catch (\Exception $th) {
            DB::rollBack();
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
        try{
            $blog = Blog::findOrfail($id);

            $file = '/upload/blogs/bg-images/' . $blog->bg_image;
            if (file_exists(public_path($file))) {
                unlink(public_path($file));
            }

            $file = '/upload/blogs/features/' . $blog->feature_img;
            if (file_exists(public_path($file))) {
                unlink(public_path($file));
            }

            $blog->delete();

            return $this->backWithSuccess('Blog has been deleted successfully');
        }catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function commentSettingsIndex()
    {
        try {
            $title = 'Top Header';
            $commentSetting = CommentSettings::first();
            return view('backend.pages.blogs.comment',compact('title','commentSetting'));
        }catch (\Throwable $th){
            return $this->backWithError($th->getMessage());
        }
    }

    public function commentSettingsStore(Request $request)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            if ($request->has('show')){
                $request->validate([
                    'code' => 'required',
                    'url' => 'nullable',
                ], [
                    'code' => 'Comment code is required.',
                    'url' => 'url is required.'
                ]);
            }

            $commentSetting = CommentSettings::first()?CommentSettings::first(): new CommentSettings();
            $commentSetting->show = $request->has('show');
            $commentSetting->code = $request->code;
            $commentSetting->url = $request->url;
            $commentSetting->save();
            return $this->backWithSuccess('Comment settings updated successfully.');
        }catch (\Throwable $th){
            return $this->backWithError($th->getMessage());
        }
    }
}
