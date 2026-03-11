<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\PageSectionInputs;
use App\Models\PageSectionSettings;
use App\Models\PageSettings;
use Illuminate\Http\Request;

class PageSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        Parent::__construct();

    }

    public function homeIndex()
    {
        try {
            if (!PageSettings::where('name', 'home')->first()){
                PageSettings::create([
                    'name' => 'home'
                ]);
            }
            return view('backend.pages.settings.page-settings.home-page', [
                'title' => 'Home Page Settings',
                    'settingsPage' => PageSettings::where('name', 'home')->first()
            ]);
        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function contactIndex()
    {
        try {
            if (!PageSettings::where('name', 'contact')->first()){
                PageSettings::create([
                    'name' => 'contact',
                ]);
            }
            return view('backend.pages.settings.page-settings.contact-page', [
                'title' => 'Contact Page Settings',
                'settingsPage' => PageSettings::where('name', 'contact')->first()
            ]);
        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function aboutIndex(){
        if (!PageSettings::where('name', 'about')->first()){
            PageSettings::create([
                'name' => 'about'
            ]);
        }
        return view('backend.pages.settings.page-settings.about-page', [
            'title' => 'About Page Settings',
            'settingsPage' => PageSettings::where('name', 'about')->first()
        ]);
    }

    public function servicesIndex(){
        if (!PageSettings::where('name', 'services')->first()){
            PageSettings::create([
                'name' => 'services'
            ]);
        }
        return view('backend.pages.settings.page-settings.serviceas-page', [
            'title' => 'Services Page Settings',
            'settingsPage' => PageSettings::where('name', 'services')->first()
        ]);
    }

    public function casesIndex(){
        if (!PageSettings::where('name', 'cases')->first()){
            PageSettings::create([
                'name' => 'cases'
            ]);
        }
        return view('backend.pages.settings.page-settings.cases-page', [
            'title' => 'Cases Page Settings',
            'settingsPage' => PageSettings::where('name', 'cases')->first()
        ]);
    }

    public function blogsIndex(){
        if (!PageSettings::where('name', 'blogs')->first()){
            PageSettings::create([
                'name' => 'blogs'
            ]);
        }
        return view('backend.pages.settings.page-settings.blogs-page', [
            'title' => 'Blogs Page Settings',
            'settingsPage' => PageSettings::where('name', 'blogs')->first()
        ]);
    }

    public function teamsIndex(){
        if (!PageSettings::where('name', 'teams')->first()){
            PageSettings::create([
                'name' => 'teams'
            ]);
        }
        return view('backend.pages.settings.page-settings.teams-page', [
            'title' => 'Teams Page Settings',
            'settingsPage' => PageSettings::where('name', 'teams')->first()
        ]);
    }

    public function faqIndex(){
        if (!PageSettings::where('name', 'faq')->first()){
            PageSettings::create([
                'name' => 'faq'
            ]);
        }
        return view('backend.pages.settings.page-settings.faq-page', [
            'title' => 'faq Page Settings',
            'settingsPage' => PageSettings::where('name', 'faq')->first()
        ]);
    }

    public function clientDashboardPageIndex(){
        if (!PageSettings::where('name', 'client_dashboard')->first()){
            PageSettings::create([
                'name' => 'client_dashboard'
            ]);
        }
        return view('backend.pages.settings.page-settings.client-dashboard', [
            'title' => 'Client Dashboard Page Settings',
            'settingsPage' => PageSettings::where('name', 'client_dashboard')->first()
        ]);
    }

    public function store(Request $request)
    {
        //return response()->json(['msg'=>'Create, update and delete is not allowed to demo version','status'=>null]);
        try {
            $settingsPage = PageSettings::where('name', $request->page)->first();
            $group = $settingsPage->sections()->where('name', $request->group)->first()?$settingsPage->sections()->where('name', $request->group)->first(): null;
            if ($group == null){
                $group = new PageSectionSettings();
                $group->page_id = $settingsPage->id;
                $group->name = $request->group;
                $group->save();
            }
            $inputs = $request->all();
            unset($inputs['page']);
            unset($inputs['group']);
            foreach ($inputs as $key => $value){
                $value = $key == 'show'?($value?true:false):$value;
                $group->update([$key=>$value]);
            }

            return response()->json($group);
        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function storeWithImg(Request $request)
    {
       //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $settingsPage = PageSettings::where('name', $request->page)->first();
            $group = $settingsPage->sections()->where('name', $request->group)->first()?$settingsPage->sections()->where('name', $request->group)->first(): null;
            if ($group == null){
                $group = new PageSectionSettings();
                $group->page_id = $settingsPage->id;
                $group->name = $request->group;
                $group->save();
            }
            $inputs = $request->all();
            unset($inputs['page']);
            unset($inputs['group']);
            unset($inputs['_token']);

            $inputs = (object)$inputs;
            if ($request->hasFile('fnt_img')){
                if ($group->fnt_img){
                    if (file_exists(public_path($group->fnt_img))){
                        unlink(public_path($group->fnt_img));
                    }
                }
            }

            if ($request->hasFile('bg_img')) {
                if ($group->bg_img) {
                    if (file_exists(public_path($group->bg_img))) {
                        unlink(public_path($group->bg_img));
                    }
                }
            }

            if (!$request->has('show')){
                $group->update(['show'=>false]);
            }
            foreach ($inputs as $key => $value){
                if ($key == 'bg_img') {
                    foreach ($value as $img){
                        $filename = time().$img->getClientOriginalName();
                        $img->move(public_path('/upload/settings/'), $filename);
                    }
                    $group->bg_img = '/upload/settings/' . $filename;
                    $group->save();
                }elseif ($key == 'fnt_img') {
                    foreach ($value as $img){
                        $filename = time().$img->getClientOriginalName();
                        $img->move(public_path('/upload/settings/'), $filename);
                    }
                    $group->fnt_img = '/upload/settings/' . $filename;
                    $group->save();
                }else{
                    $value = $key == 'show'?($value?true:false):$value;
                    $group->update([$key=>$value]);
                }
            }

            return $this->backWithSuccess('Saved Successfully');
        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function seoSettings(Request $request)
    {
       //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $page = PageSettings::where('name', $request->page)->first();
            $page->meta_keyword = $request->meta_keyword;
            $page->meta_description = $request->meta_description;
            $page->save();
            return $this->backWithSuccess('Page meta has been updated successfully');
        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function inputFields(Request $request)
    {
        $page = PageSettings::where('name', $request->page)->first();
        $group = $page->sections()->where('name', $request->group)->first();
        $section = new PageSectionInputs();
        if ($request->group == "slider"){
            return response()->json($section->slider($page));
        }elseif ($request->group == "about"){
            return response()->json($section->about($page));
        }elseif ($request->group == "service"){
            return response()->json($section->service($page));
        }elseif ($request->group == "testimonial"){
            return response()->json($section->testimonial($page));
        }elseif ($request->group == "appointment"){
            return response()->json($section->appointment($page));
        }elseif ($request->group == "case_study"){
            return response()->json($section->case_study($page));
        }elseif ($request->group == "attorney"){
            return response()->json($section->attorney($page));
        }elseif ($request->group == "blog"){
            return response()->json($section->blog($page));
        }elseif ($request->group == "partner"){
            return response()->json($section->partner($page));
        }elseif ($request->group == "contact_info"){
            return response()->json($section->contact_info($page));
        }elseif ($request->group == "business_hour"){
            return response()->json($section->business_hour($page));
        }elseif ($request->group == "email"){
            return response()->json($section->email($page));
        }elseif ($request->group == "about_breadcumb_bg_img"){
            return response()->json($section->about_breadcumb_bg_img($page));
        }elseif ($request->group == "contact_breadcumb_bg_img"){
            return response()->json($section->contact_breadcumb_bg_img($page));
        }elseif ($request->group == "services_breadcumb_bg_img"){
            return response()->json($section->services_breadcumb_bg_img($page));
        }elseif ($request->group == "cases_breadcumb_bg_img"){
            return response()->json($section->cases_breadcumb_bg_img($page));
        }elseif ($request->group == "blogs_breadcumb_bg_img"){
            return response()->json($section->blogs_breadcumb_bg_img($page));
        }elseif ($request->group == "teams_breadcumb_bg_img"){
            return response()->json($section->teams_breadcumb_bg_img($page));
        }elseif ($request->group == "faq_breadcumb_bg_img"){
            return response()->json($section->faq_breadcumb_bg_img($page));
        }elseif ($request->group == "client_dashboard_breadcumb_bg_img"){
            return response()->json($section->client_dashboard_breadcumb_bg_img($page));
        }elseif ($request->group == "left_about_img"){
            return response()->json($section->left_about_img($page));
        }elseif ($request->group == "right_about"){
            return response()->json($section->right_about($page));
        }elseif ($request->group == "about_appointment"){
            return response()->json($section->about_appointment($page));
        }elseif ($request->group == "about_attorney"){
            return response()->json($section->about_attorney($page));
        }

    }

}
