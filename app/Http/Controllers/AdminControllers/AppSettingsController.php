<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\FooterSettings;
use App\Models\GeneralSettings;
use App\Models\HeaderFooterSettings;
use App\Models\HeaderSettings;
use App\Models\SocialMediaSettings;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\SEOSettings;
use App\Models\LogoSettings;
use Mews\Purifier\Purifier;

class AppSettingsController extends Controller
{
    public function __construct()
    {
        Parent::__construct();
    }


    public function getGeneralSettings()
    {
        try {
            $title = 'General Settings';
            $generalSetting = GeneralSettings::first();
            return view('backend.pages.settings.general', compact('title','generalSetting'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function saveGeneralSettings(Request $request)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        // input's validation check
        $request->validate([
            'site_name' => 'required',
            'site_tag_line' => 'required',
            'site_sub_tag_line' => 'nullable',
            'author_name' => 'required',
            'og_meta_title' => 'nullable',
            'og_meta_description' => 'nullable',
            'og_meta_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ], [
            'site_name' => 'Site name is required.',
            'site_tag_line' => 'Site tag line is required.',
            'author_name' => 'Author name  is required.',
        ]);

        try {
            $generalSettingStore = GeneralSettings::first()?GeneralSettings::first(): new GeneralSettings();
            $generalSettingStore->site_name = $request->site_name;
            $generalSettingStore->site_tag_line = $request->site_tag_line;
            $generalSettingStore->site_sub_tag_line = $request->site_sub_tag_line;
            $generalSettingStore->author_name = $request->author_name;
            $generalSettingStore->og_meta_title = $request->og_meta_title;
            $generalSettingStore->og_meta_description = $request->og_meta_description;
            if ($request->hasFile('og_meta_image')) {

                if (!empty($generalSettingStore) && !empty($generalSettingStore->og_meta_image)){
                    $path = $generalSettingStore->og_meta_image;
                    if (file_exists(public_path($path))){
                        unlink(public_path($path));
                    }
                }
                $img = $request->og_meta_image;
                //image name
                $filename = time().$img->getClientOriginalName();
                //upload image
                $img->move(public_path('/upload/settings/'), $filename);
                //save to database
                $path = "/upload/settings/".$filename;
                $generalSettingStore->og_meta_image = $path;

            }
            $generalSettingStore->save();
            return $this->backWithSuccess('General Settings created successfully.');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Top Header  >>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
    public function topHeaderIndex()
    {
        try {
            $title = 'Top Header';
            $headerSettingValue = HeaderSettings::first();
            return view('backend.pages.settings.top-header-settings',compact('title','headerSettingValue'));
        }catch (\Throwable $th){
            return $this->backWithError($th->getMessage());
        }
    }

    public function topHeaderStore(Request $request)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $headerSettingStore = HeaderSettings::first()?HeaderSettings::first(): new HeaderSettings();
            $headerSettingStore->show = $request->has('show');
            $headerSettingStore->left_content = $request->left_content;
            $headerSettingStore->right_content = $request->right_content;
            $headerSettingStore ->save();
            return $this->backWithSuccess('Header Settings created successfully.');
        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    /*>>>>>>>>>>>>>>>>>>>>>> Footer settings  >>>>>>>>>>>>>>>>>>>>>>>>>>*/
    public function footerIndex()
    {
        try {
            $title = 'Footer Settings';
            $footer = FooterSettings::first();
            return view('backend.pages.settings.footer-settings',compact('title','footer'));
        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function storeFooter(Request $request)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $group = FooterSettings::first()?FooterSettings::first():new FooterSettings();
            $inputs = $request->all();

            unset($inputs['page']);
            unset($inputs['group']);
            unset($inputs['_token']);

            $inputs = (object)$inputs;
            if ($request->hasFile('footer_logo')) {
                if ($group->footer_logo) {
                    if (file_exists(public_path($group->footer_logo))) {
                        unlink(public_path($group->footer_logo));
                    }
                }
            }

            if (!$request->has('show')){
                $group->update(['show'=>false]);
            }

            if (!$request->has('show_social')){
                $group->update(['show_social'=>false]);
            }


            foreach ($inputs as $key => $value){
                if ($key == 'footer_logo') {
                    foreach ($value as $img){
                        $filename = time().$img->getClientOriginalName();
                        $img->move(public_path('/upload/settings/'), $filename);
                    }
                    $group->footer_logo = '/upload/settings/' . $filename;
                }
                else{
                    if ($key == 'show'){
                        $group->update([$key=>true]);
                    }elseif ($key == 'show_social'){
                        $group->update([$key=>true]);
                    }else{
                        $group->update([$key=>$value]);
                    }
                }
                $group->save();
            }

            return $this->backWithSuccess('Saved Successfully');
        }catch (\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /*>>>>>>>>>>>>>>>>>>>>>>>>> LOGO/FAVICON  >>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
    public function getLogoFaviconSettings()
    {
        try {
            $title = 'Logo Settings';
            $logoSettings= LogoSettings::first();
            return view('backend.pages.settings.logo-favicon', compact('title','logoSettings'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function saveLogoFaviconSettings(Request $request)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $logoFaviconSettingStore = LogoSettings::first()?LogoSettings::first(): new LogoSettings();
            if ($request->hasFile('logo')) {
                if (LogoSettings::first()) {
                    $path = $logoFaviconSettingStore->logo;
                    if (file_exists(public_path($path))) {
                        unlink(public_path($path));
                    }
                }
                $images = $request->logo;
                foreach ($images as $img){
                    //image name
                    $filename = time().$img->getClientOriginalName();
                    //upload image
                    $img->move(public_path('/upload/settings'), $filename);
                }

                $path = "/upload/settings/".$filename;
                $logoFaviconSettingStore->logo=$path;

            }

            if ($request->hasFile('favicon')) {
                if (LogoSettings::first()) {
                    $path = $logoFaviconSettingStore->favicon;
                    if (file_exists(public_path($path))) {
                        unlink(public_path($path));
                    }
                }
                $images = $request->favicon;
                foreach ($images as $img){
                    //image name
                    $filename = time().$img->getClientOriginalName();
                    //upload image
                    $img->move(public_path('/upload/settings'), $filename);
                }
                $path = '/upload/settings/'.$filename;
                $logoFaviconSettingStore->favicon=$path;
            }
            $logoFaviconSettingStore->save();

            return  $this->backWithSuccess('Logo sections are updated successfully....');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Seo  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
    public function getSeoSettings()
    {
        try {
            $title = 'Seo Settings';
            $seoSetting= SEOSettings::first();
            return view('backend.pages.settings.seo', compact('title','seoSetting'));
        } catch (\Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function saveSeoSettings(Request $request)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $seoSettingStore = SEOSettings::first()?SEOSettings::first(): new SEOSettings();
            $seoSettingStore-> meta_keyword = $request-> meta_keyword;
            $seoSettingStore-> meta_description = $request-> meta_description;
            $seoSettingStore-> save();
            return $this->backWithSuccess('SEO Settings created successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        return $this->backWithSuccess('Success');
    }

    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> SMTP  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
    public function getSmtpSettings()
    {
        try {
            $title = 'Smtp Settings';
            return view('backend.pages.settings.smtp', compact('title'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function saveSmtpSettings(Request $request)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $path = base_path('.env');

            if (file_exists($path)) {
                file_put_contents($path, str_replace(
                    'MAIL_MAILER='.env("MAIL_MAILER"), 'MAIL_MAILER='.$request->mail_driver, file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    'MAIL_HOST='.env("MAIL_HOST"), 'MAIL_HOST='.$request->mail_host, file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    'MAIL_PORT='.env("MAIL_PORT"), 'MAIL_PORT='.$request->mail_port, file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    'MAIL_USERNAME='.env("MAIL_USERNAME"), 'MAIL_USERNAME='.$request->mail_username, file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    'MAIL_PASSWORD='.env("MAIL_PASSWORD"), 'MAIL_PASSWORD='.$request->mail_password, file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    'MAIL_ENCRYPTION='.env("MAIL_ENCRYPTION"), 'MAIL_ENCRYPTION='.$request->mail_encryption, file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    'MAIL_FROM_ADDRESS='.env("MAIL_FROM_ADDRESS"), 'MAIL_FROM_ADDRESS='.$request->mail_username, file_get_contents($path)
                ));
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        return $this->backWithSuccess('Success');
    }


    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Insert Header Footer >>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
    public function getInsertHeaderFooterSettings()
    {
        try {
            $title = 'Insert Header Footer';
            $headerFooterData = HeaderFooterSettings::first();
            return view('backend.pages.settings.insert-header-footer', compact('title','headerFooterData'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function saveInsertHeaderFooterSettings(Request $request)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $headerFooterSettingStore = HeaderFooterSettings::first()?HeaderFooterSettings::first(): new HeaderFooterSettings();
            $headerFooterSettingStore->header = $request->header;
            $headerFooterSettingStore->footer = $request->footer;
            $headerFooterSettingStore ->save();
            return $this->backWithSuccess('Header Footer created successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        return $this->backWithSuccess('Success');
    }

    /*>>>>>>>>>>> Social Media >>>>>>>>*/
    public function socialMediaSettings()
    {
        try {
            $title = 'Social Media Settings';
            $socialMediaSettingAll = SocialMediaSettings::all();
            return view('backend.pages.settings.social-media', compact('title','socialMediaSettingAll'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function saveSocialMediaSettings(Request $request)
    {
        //return $this->backWithWarning('Create, update and delete is not allowed to demo version');
        try {
            $inputs = $request->all();
            unset($inputs['_token']);
            foreach ($inputs as $key => $value){
                if ($value != null) {
                    $value = \Mews\Purifier\Facades\Purifier::clean($value, function (HTMLPurifier_Config $config) {
                        $uri = $config->getDefinition('URI');
                        $uri->addFilter(new HTMLPurifier_URIFilter_NameOfFilter(), $config);
                    });
                }
                SocialMediaSettings::where('name',$key)->update(['url' => $value]);
            }
            return $this->backWithSuccess('Social Media Settings created successfully.');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
