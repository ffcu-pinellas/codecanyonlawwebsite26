<?php

namespace App\Http\Controllers\GuestViewControllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Attorney;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\CaseStudy;
use App\Models\CommentSettings;
use App\Models\Contact;
use App\Models\DynamicPage;
use App\Models\Faq;
use App\Models\Message;
use App\Models\PageSectionSettings;
use App\Models\PageSettings;
use App\Models\Partners;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Tag;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuestViewController extends Controller
{
    public function __construct()
    {
        Parent::__construct();
    }

    //===============  login Page ================//
    public function loginRedirect()
    {
        try {
            if (Auth::user()){
                if (!Auth::user()->hasRole('client')){
                    return redirect()->route('admin.dashboard');
                }else {
                    return redirect()->route('client.dashboard');
                }
            }
            return view('auth.login');
        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function adminLogin()
    {
        return redirect()->route('login');
    }
    //===============  register Page ================//
    public function userRegister()
    {
        try {
            if (Auth::user()){
                if (!Auth::user()->hasRole('client')){
                    return redirect()->route('admin.dashboard');
                }else {
                    return redirect()->route('client.dashboard');
                }
            }
            return view('auth.register');
        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    //===============  Forget Password Page ================//
    public function forgetPassword()
    {
        try {
            return view('auth.forgot-password');
        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function resetPassword(Request $request, $token)
    {
        try {
            return view('auth.reset-password', [
                'token' => $token,
                'email' => $request->email
            ]);
        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    //===============  Welcome Page ================//
    public function index()
    {
        try {

            $page =PageSettings::where('name', 'home')->first();
            if($page){
                $title = ucwords(clean($page->name));

                // slider section
                $slider_setting = $page->sections()->where('name','slider')->first()?$page->sections()->where('name','slider')->first():null;
                if(!empty($slider_setting)){
                    $sliders = Slider::where('status',true)->take($slider_setting->number_of_content)->get();
                }else{
                    $sliders = null;
                }

                // about section
                $about_setting = $page->sections()->where('name','about')->first()?$page->sections()->where('name','about')->first():null;

                // services section
                $service_setting = $page->sections()->where('name','service')->first()?$page->sections()->where('name','service')->first():null;
                if(!empty($service_setting)){
                    $services = Service::where('status',true)->take($service_setting->number_of_content)->get();
                }else{
                    $services = null;
                }

                // testimonial section
                $testimonial_setting = $page->sections()->where('name','testimonial')->first()?$page->sections()->where('name','testimonial')->first():null;
                if(!empty($testimonial_setting)){
                    $testimonials = Testimonial::where('status',true)->take($testimonial_setting->number_of_content)->get();
                }else{
                    $testimonials = null;
                }

                // appointment section
                $appointment_setting = $page->sections()->where('name','appointment')->first()?$page->sections()->where('name','appointment')->first():null;

                // case study section
                $case_study_setting = $page->sections()->where('name','case_study')->first()?$page->sections()->where('name','case_study')->first():null;
                if(!empty($case_study_setting)){
                    $case_studies = CaseStudy::where('service_id', '!=', null)->take($case_study_setting->number_of_content)->get();
                }else{
                    $case_studies = null;
                }

                //attorney section
                $attorney_setting = $page->sections()->where('name','attorney')->first()?$page->sections()->where('name','attorney')->first():null;
                if(!empty($attorney_setting)){
                    $attorneys = Attorney::where('status',true)->take($attorney_setting->number_of_content)->get();
                }else{
                    $attorneys = null;
                }

                // blog section
                $blog_setting = $page->sections()->where('name','blog')->first()?$page->sections()->where('name','blog')->first():null;
                if(!empty($blog_setting)){
                    $blogs = Blog::take($blog_setting->number_of_content)->get();
                }else{
                    $blogs = null;
                }

                // partner section
                $partner_setting = $page->sections()->where('name','partner')->first()?$page->sections()->where('name','partner')->first():null;
                if(!empty($partner_setting)){
                    $partners = Partners::all();
                }else{
                    $partners = null;
                }

            }else{
                $title = __('Welcome');
                $about_setting = $slider_setting = $sliders = $service_setting = $services = $testimonial_setting =
                $testimonials = $case_study_setting = $case_studies = $attorneys = $attorney_setting = $blogs =
                $blog_setting = $partner_setting = $partners = $appointment_setting = null;

            }
            return view('welcome', compact(
                'title',
                'about_setting',
                'slider_setting','sliders','service_setting','services',
                'testimonial_setting',
                'testimonials','case_study_setting','case_studies',
                'attorneys','attorney_setting',
                'blogs','blog_setting',
                'partner_setting','partners',
                'appointment_setting'
            ));
        }catch (\Throwable $e){
            return abort(404);
        }
    }
    //===============  Contact Page ================//
    public function viewContactPage()
    {
        try {
            $page =PageSettings::where('name', 'contact')->first();
            if(!empty($page)) {
                $pageContent = PageSectionSettings::where('name', 'contact_breadcumb_bg_img')->first();
                if(!empty($pageContent)){
                    $title = ucfirst($pageContent->title);
                }else{
                    $title = ucfirst($page->name);
                }
                $contact = $page->sections()->where('name', 'contact_info')->first() ? $page->sections()->where('name', 'contact_info')->first() : (object)[];
                $businessInfo = $page->sections()->where('name', 'business_hour')->first() ? $page->sections()->where('name', 'business_hour')->first() : (object)[];
                $emailInfo = $page->sections()->where('name', 'email')->first() ? $page->sections()->where('name', 'email')->first() : (object)[];
            }else{
                $title = __('Contact');
                $contact = null;
                $businessInfo = null;
                $emailInfo = null;
                $pageContent = null;
            }
            return view('frontend.theme1.pages.contact.index', compact('title','contact','businessInfo','emailInfo', 'pageContent', 'page'));
        }catch (\Throwable $e){
            return abort(404);
        }
    }
    // about page
    public function viewAboutPage(){
        try {
            $page = PageSettings::where('name', 'about')->first();
            if(!empty($page)){
                // breadcumb section
                $breadcumbBgImg = $page->sections()->where('name','about_breadcumb_bg_img')->first()?$page->sections()->where('name','about_breadcumb_bg_img')->first():(object)[];
                if(!empty($breadcumbBgImg)){
                    $title = ucfirst($breadcumbBgImg->title);
                }else{
                    $title = ucfirst($page->name);
                }
                //about section
                $leftImage = $page->sections()->where('name','left_about_img')->first()?$page->sections()->where('name','left_about_img')->first():(object)[];
                $rightAbout = $page->sections()->where('name','right_about')->first()?$page->sections()->where('name','right_about')->first():(object)[];
                //appointment
                $aboutAppointment = $page->sections()->where('name','about_appointment')->first()?$page->sections()->where('name','about_appointment')->first():(object)[];
                // attorney
                $aboutAttorney = $page->sections()->where('name','about_attorney')->first()?$page->sections()->where('name','about_attorney')->first():(object)[];
                $attorneys = Attorney::where('status',true)->take((int)$aboutAttorney->number_of_content)->get();
            }else{
                $title = __('About Us');
                $leftImage = null;
                $rightAbout = null;
                $aboutAppointment = null;
                $aboutAttorney = null;
                $attorneys = null;
            }

            return view('frontend.theme1.pages.about.index', compact('title', 'breadcumbBgImg','leftImage','rightAbout','aboutAppointment','aboutAttorney','attorneys','page'));
        }catch (\Throwable $e){
            return abort(404);
        }
    }
    //===============  Service Page ================//
    public function viewAllServicesPage(){
        try {
            $page = PageSettings::where('name', 'services')->first();
            if(!empty($page)){
                $pageContent = PageSectionSettings::where('name', 'services_breadcumb_bg_img')->first();
                if(!empty($pageContent)){
                    $title = ucfirst($pageContent->title);
                    if(!empty($pageContent->number_of_content)){
                        $services = Service::where('status',true)->paginate((int)$pageContent->number_of_content);
                    }else{
                        $services = Service::where('status',true)->paginate(6);
                    }
                }else{
                    $title = ucfirst($page->name);
                    $services = Service::where('status',true)->paginate(6);
                }

            }else{
                $title = __('Our Services');
                $services = Service::where('status',true)->paginate(6);
            }

            return view('frontend.theme1.pages.services.all_services', compact('title','services','pageContent','page'));
        }catch (\Throwable $e){
            return abort(404);
        }
    }
    public function viewSingleServicePage($id){
        try {
            $service = Service::findOrfail($id);
            $title =  $service->title;
            $allService = Service::all();
            $page = PageSettings::where('name', 'services')->first();
            if(!empty($page)) {
                $pageContent = PageSectionSettings::where('name', 'services_breadcumb_bg_img')->first();
            }else{
                $pageContent = null;
            }

            return view('frontend.theme1.pages.services.single_service', compact('title','service','allService', 'page', 'pageContent'));
        }catch (\Throwable $e){
            abort(404);
        }
    }
    //===============  Case Page ================//
    public function viewAllCasesPage(){
        try {
            $page = PageSettings::where('name', 'cases')->first();
            if(!empty($page)){
                $pageContent = PageSectionSettings::where('name', 'cases_breadcumb_bg_img')->first();
                if(!empty($pageContent)){
                    $title = ucfirst($pageContent->title);
                    $allCases = CaseStudy::where('service_id', '!=', null)->paginate((int)$pageContent->number_of_content);
                }else{
                    $title = ucfirst($page->name);
                    $allCases = CaseStudy::paginate(6);
                }
            }else{
                $title = __('Cases');
                $allCases = CaseStudy::paginate(6);
                $pageContent = null;
            }
            return view('frontend.theme1.pages.cases.all_cases', compact('title','allCases', 'pageContent','page'));
        }catch (\Throwable $e){
            return abort(404);
        }
    }
    public function viewSingleCasePage($id){
        try {
            $page = PageSettings::where('name', 'cases')->first();
            if(!empty($page)) {
                $pageContent = PageSectionSettings::where('name', 'cases_breadcumb_bg_img')->first();
            }else{
                $pageContent = null;
            }
            $singleCase = CaseStudy::findOrfail($id);
            $title = $singleCase->title;
            $caseStudy = CaseStudy::all();
            return view('frontend.theme1.pages.cases.single_case', compact('title','singleCase','caseStudy', 'pageContent', 'page'));
        }catch (\Throwable $e){
            return abort(404);
        }
    }
    //===============  blog Page ================//
    public function viewAllBlogsPage(){
        try {
            $page = PageSettings::where('name', 'blogs')->first();
            if(!empty($page)){
                $pageContent = PageSectionSettings::where('name', 'blogs_breadcumb_bg_img')->first();
                if(!empty($pageContent)){
                    $title = ucfirst($pageContent->title);
                    $blogs = DB::table('blogs')
                        ->join('users', 'blogs.user_id', '=', 'users.id')
                        ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                        ->select('blogs.*'
                            , 'users.name as user_name'
                            , 'blog_categories.id as category_id'
                            , 'blog_categories.name as category_name'
                        )
                        ->paginate((int)$pageContent->number_of_content);
                }else{
                    $title = ucfirst($page->name);
                    $blogs = DB::table('blogs')
                        ->join('users', 'blogs.user_id', '=', 'users.id')
                        ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                        ->select('blogs.*'
                            , 'users.name as user_name'
                            , 'blog_categories.id as category_id'
                            , 'blog_categories.name as category_name'
                        )
                        ->paginate(6);
                }
            }else{
                $title = __('Our Blogs');
                $blogs = DB::table('blogs')
                    ->join('users', 'blogs.user_id', '=', 'users.id')
                    ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                    ->select('blogs.*'
                        , 'users.name as user_name'
                        , 'blog_categories.id as category_id'
                        , 'blog_categories.name as category_name'
                    )
                    ->paginate(6);
                $pageContent = null;
            }

            return view('frontend.theme1.pages.blogs.all_blogs', compact('title','blogs','page'));
        }catch (\Throwable $e){
            return abort(404);
        }
    }
    public function viewSingleBlogPage($id){
        try {
            $page = PageSettings::where('name', 'blogs')->first();
            $commentSettings = CommentSettings::first();
            $blog = DB::table('blogs')
                ->join('users','blogs.user_id','=','users.id')
                ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                ->select('blogs.*'
                    ,'users.name as userName'
                    ,'blog_categories.name as cat_name'
                )
                ->where('blogs.id',$id)
                ->first();

            $title = $blog->title;

            $categories = DB::table('blog_categories')->get();
            $recentBlogs = Blog::latest("updated_at")->take(5)->get();

            $blogTags = DB::table('blog_tags')
                ->join('tags', 'blog_tags.tag_id', '=', 'tags.id')
                ->select('blog_tags.*'
                    , 'tags.name as tag_name'
                )
                ->where('blog_tags.blog_id', $blog->id)
                ->get();

            return view('frontend.theme1.pages.blogs.single_blog', compact('title','blog', 'recentBlogs', 'categories', 'blogTags','page','commentSettings'));
        }catch (\Throwable $e){
            return abort(404);
        }
    }
    public function blogCategory($id){

        try{
            $page = PageSettings::where('name', 'blogs')->first();
            $blogCategory = BlogCategory::findOrFail($id);
            $title = $blogCategory->name;
            if(!empty($page)){
                $pageContent = PageSectionSettings::where('name', 'blogs_breadcumb_bg_img')->first();
                if(!empty($pageContent)){
                    $blogs = DB::table('blogs')
                        ->join('users', 'blogs.user_id', '=', 'users.id')
                        ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                        ->select('blogs.*'
                            , 'users.name as user_name'
                            , 'blog_categories.id as category_id'
                            , 'blog_categories.name as category_name'
                        )
                        ->where('category_id', $id)
                        ->paginate((int)$pageContent->number_of_content);
                }else{
                    $blogs = DB::table('blogs')
                        ->join('users', 'blogs.user_id', '=', 'users.id')
                        ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                        ->select('blogs.*'
                            , 'users.name as user_name'
                            , 'blog_categories.id as category_id'
                            , 'blog_categories.name as category_name'
                        )
                        ->where('category_id', $id)
                        ->paginate(6);
                }
            }else{
                $blogs = DB::table('blogs')
                    ->join('users', 'blogs.user_id', '=', 'users.id')
                    ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                    ->select('blogs.*'
                        , 'users.name as user_name'
                        , 'blog_categories.name as category_name'
                    )
                    ->where('category_id', $id)
                    ->paginate(6);
                $pageContent = null;
            }

            return view('frontend.theme1.pages.blogs.all_blogs', compact('blogs', 'title', 'pageContent','page'));
        }catch (\Exception $e){
            abort(404);
        }


    }
    public function blogTag($id){

        try{
            $page = PageSettings::where('name', 'blogs')->first();
            $tag = Tag::findOrFail($id);
            $title = $tag->name;
            if(!empty($page)){
                $pageContent = PageSectionSettings::where('name', 'blogs_breadcumb_bg_img')->first();
                if(!empty($pageContent)){
                    $blogs = DB::table('blogs')
                        ->join('users', 'blogs.user_id', '=', 'users.id')
                        ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                        ->join('blog_tags', 'blogs.id', '=', 'blog_tags.blog_id')
                        ->select('blogs.*'
                            , 'users.name as user_name'
                            , 'blog_categories.id as category_id'
                            , 'blog_categories.name as category_name'
                            , 'blog_tags.tag_id as tag_id'
                        )
                        ->where('tag_id', $id)
                        ->paginate((int)$pageContent->number_of_content);
                }else{
                    $blogs = DB::table('blogs')
                        ->join('users', 'blogs.user_id', '=', 'users.id')
                        ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                        ->join('blog_tags', 'blogs.id', '=', 'blog_tags.blog_id')
                        ->select('blogs.*'
                            , 'users.name as user_name'
                            , 'blog_categories.id as category_id'
                            , 'blog_categories.name as category_name'
                            , 'blog_tags.tag_id as tag_id'
                        )
                        ->where('tag_id', $id)
                        ->paginate(6);
                }
            }else{
                $blogs = DB::table('blogs')
                    ->join('users', 'blogs.user_id', '=', 'users.id')
                    ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                    ->join('blog_tags', 'blogs.id', '=', 'blog_tags.blog_id')
                    ->select('blogs.*'
                        , 'users.name as user_name'
                        , 'blog_categories.id as category_id'
                        , 'blog_categories.name as category_name'
                        , 'blog_tags.tag_id as tag_id'
                    )
                    ->where('tag_id', $id)
                    ->paginate(6);
                $pageContent = null;
            }

            return view('frontend.theme1.pages.blogs.all_blogs', compact('blogs', 'title', 'pageContent','page'));
        }catch (\Exception $e){
            abort(404);
        }
    }
    public function searchBlog(Request $request){
        try{
            $page = PageSettings::where('name', 'blogs')->first();
            $s = $request->s;
            $title = 'Search Result for "'.$s.'"';
            if(!empty($page)){
                $pageContent = PageSectionSettings::where('name', 'blogs_breadcumb_bg_img')->first();
                if(!empty($pageContent)){
                    $blogs = DB::table('blogs')
                        ->join('users', 'blogs.user_id', '=', 'users.id')
                        ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                        ->select('blogs.*'
                        , 'users.name as user_name'
                        , 'blog_categories.id as category_id'
                        , 'blog_categories.name as category_name'
                        )
                        ->where('title', 'LIKE', '%'.$s.'%')
                        ->paginate((int)$pageContent->number_of_content);
                }else{
                    $blogs = DB::table('blogs')
                        ->join('users', 'blogs.user_id', '=', 'users.id')
                        ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                        ->select('blogs.*'
                            , 'users.name as user_name'
                            , 'blog_categories.id as category_id'
                            , 'blog_categories.name as category_name'
                        )
                        ->where('title', 'LIKE', '%'.$s.'%')
                        ->paginate(6);
                }
            }else{
                $blogs = DB::table('blogs')
                    ->join('users', 'blogs.user_id', '=', 'users.id')
                    ->join('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                    ->select('blogs.*'
                        , 'users.name as user_name'
                        , 'blog_categories.name as category_name'
                    )
                    ->where('title', 'LIKE', '%'.$s.'%')
                    ->paginate(6);
                $pageContent = null;
            }
            return view('frontend.theme1.pages.blogs.all_blogs', compact('blogs', 'title', 'pageContent','page'));
        }catch (\Exception $e){
            abort(404);
        }
    }
    //===============  team Page ================//
    public function viewAllTeamsPage(){
        try {
            $page = PageSettings::where('name', 'teams')->first();
            if(!empty($page)){
                $pageContent = PageSectionSettings::where('name', 'teams_breadcumb_bg_img')->first();
                if(!empty($pageContent)){
                    $title = ucfirst($pageContent->title);
                    $teamMembers = Attorney::where('status', true)->paginate((int)$pageContent->number_of_content);
                }else{
                    $title = ucfirst($page->name);
                    $teamMembers = Attorney::where('status', true)->all();
                    $pageContent = null;
                }
            }else{
                $title = __('Our Team');
                $teamMembers = Attorney::where('status', true)->all();
                $pageContent = null;
            }
            return view('frontend.theme1.pages.teams.all_teams', compact('title','teamMembers', 'pageContent','page'));
        }catch (\Throwable $e){
            abort(404);
        }
    }
    public function viewAttorney($id){
        try {
            $page = PageSettings::where('name', 'teams')->first();
            $attorney = Attorney::findOrfail($id);
            $title = $attorney->name;
            if(!empty($page)){
                $pageContent = PageSectionSettings::where('name', 'teams_breadcumb_bg_img')->first();
            }else{
                $pageContent = null;
            }
            return view('frontend.theme1.pages.teams.team-details', compact('title','attorney', 'pageContent', 'page'));
        }catch (\Throwable $e){
            return abort(404);
        }
    }
    //===============  Contact Massage Page ================//
    public function storeContactMessage(Request $request){
        try{
            $requests = $request->all();
            $requests['status'] = true;
            Contact::create($requests);
            return response()->json('success');

        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    //===============  Store Appointment ================//
    public function storeAppointment(Request $request){
        try{
            $requests = $request->all();
            Appointment::create($requests);
            return response()->json('success');

        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    //===============  FAQ Page ================//
    public function viewFaqPage(){
        try {
            $page = PageSettings::where('name', 'faq')->first();

            if(!empty($page)){
                $pageContent = PageSectionSettings::where('name', 'faq_breadcumb_bg_img')->first();
                if(!empty($pageContent)){
                    $title = ucfirst($pageContent->title);
                }else{
                    $title = ucfirst($page->name);
                    $pageContent = null;
                }
            }else{
                $title = __('FAQ');
                $pageContent = null;
            }
            $faqs = Faq::all();
            return view('frontend.theme1.pages.faq.index', compact('title','faqs', 'pageContent','page'));
        }catch (\Throwable $e){
            abort(404);
        }
    }
    //===============  Dynamic Page ================//
    public function dynamicPage($slug){
        try {
            $page = DynamicPage::where('slug', $slug)->first();
            $title = $page->title;
            return view('frontend.theme1.pages.dynamic-page.index', compact('title','page'));
        }catch (\Throwable $e){
            return abort(404);
        }
    }

    //===============  Download chatting Files ================//
    public function downloadMessageFile(Message $message)
    {
        try {
            $file = public_path($message->file);
            if (file_exists($file)){
                return response()->download($file, $message->file_name);
            }else{
                return $this->backWithError($message->file_name,' file is not exist....');
            }
        }catch (\Throwable $th){
            return $this->backWithError($th->getMessage());
        }
    }
}
