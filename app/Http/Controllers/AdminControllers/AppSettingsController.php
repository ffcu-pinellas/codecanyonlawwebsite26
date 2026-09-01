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
            $logoSettings = LogoSettings::first();
            if ($logoSettings) {
                if (empty($logoSettings->logo) || (!str_starts_with($logoSettings->logo, 'http') && !file_exists(public_path($logoSettings->logo)))) {
                    if (file_exists(public_path('upload/settings/1731322171New_Project-removebg-preview.png'))) {
                        $logoSettings->logo = '/upload/settings/1731322171New_Project-removebg-preview.png';
                    }
                }
                if (empty($logoSettings->favicon) || (!str_starts_with($logoSettings->favicon, 'http') && !file_exists(public_path($logoSettings->favicon)))) {
                    if (file_exists(public_path('upload/settings/1632769376favicon.png'))) {
                        $logoSettings->favicon = '/upload/settings/1632769376favicon.png';
                    } elseif (file_exists(public_path('upload/settings/1631508115dna3emDAC.png'))) {
                        $logoSettings->favicon = '/upload/settings/1631508115dna3emDAC.png';
                    }
                }
            }
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

    /*>>>>>>>>>>> Chatwoot & Live Support Settings >>>>>>>>*/
    public function getChatSettings()
    {
        try {
            $title = 'Live Chat & Chatwoot Settings';
            $chatSettings = \App\Services\ChatwootService::getSettings();
            return view('backend.pages.settings.chat', compact('title', 'chatSettings'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function saveChatSettings(Request $request)
    {
        $request->validate([
            'provider' => 'required|in:chatwoot,tawkto,internal',
            'website_token' => 'nullable|string',
            'base_url' => 'nullable|url',
            'account_id' => 'nullable|string',
            'hmac_key' => 'nullable|string',
            'tawkto_property_id' => 'nullable|string',
        ]);

        try {
            $chatData = [
                'provider' => $request->provider,
                'website_token' => $request->website_token,
                'base_url' => rtrim($request->base_url ?: 'https://app.chatwoot.com', '/'),
                'account_id' => $request->account_id,
                'hmac_key' => $request->hmac_key,
                'tawkto_property_id' => $request->tawkto_property_id,
            ];

            // 1. Save to Database (Permanent & immune to Git reset)
            $general = \App\Models\GeneralSettings::first();
            if ($general) {
                $general->chat_settings = $chatData;
                $general->save();
            }

            // 2. Save to settings.json
            $settingsPath = storage_path('settings.json');
            $data = [];
            if (file_exists($settingsPath)) {
                $data = json_decode(file_get_contents($settingsPath), true) ?: [];
            }
            $data['chat'] = $chatData;
            file_put_contents($settingsPath, json_encode($data, JSON_PRETTY_PRINT));

            \App\Models\SystemAuditLog::logAction('CHAT_SETTINGS_UPDATED', 'Updated Chatwoot and live chat integration settings.', auth()->id(), 'admin');

            return $this->backWithSuccess('Chat & Chatwoot settings updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function getPaymentSettings()
    {
        try {
            $title = 'Escrow & Payment Depository Settings';
            $paymentSettings = [
                'bank_name' => 'JPMorgan Chase Bank, N.A.',
                'beneficiary' => config('app.name', 'Your CPA Expert') . ' Trust & Escrow LLC',
                'account_number' => '987654321098',
                'routing_number' => '021000021',
                'swift_code' => 'CHASUS33',
                'wire_instructions' => 'Please include invoice number in the wire memo.',
                'crypto_usdt_address' => 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE',
                'crypto_btc_address' => 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh',
                'ach_details' => 'JPMorgan Chase ACH / Direct Deposit - Routing: 021000021',
                'late_fee_enabled' => 1,
                'late_fee_percent' => 5,
                'grace_period_days' => 7,
            ];

            // DB first
            $general = \App\Models\GeneralSettings::first();
            if ($general && !empty($general->payment_settings)) {
                $dbPay = is_array($general->payment_settings) ? $general->payment_settings : json_decode($general->payment_settings, true);
                if (!empty($dbPay) && is_array($dbPay)) {
                    $paymentSettings = array_merge($paymentSettings, $dbPay);
                }
            } else {
                $settPath = storage_path('settings.json');
                if (file_exists($settPath)) {
                    $all = json_decode(file_get_contents($settPath), true);
                    if (!empty($all['payment'])) {
                        $paymentSettings = array_merge($paymentSettings, $all['payment']);
                    }
                }
            }

            return view('backend.pages.settings.payment', compact('title', 'paymentSettings'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function savePaymentSettings(Request $request)
    {
        try {
            $paymentData = [
                'bank_name' => $request->bank_name,
                'beneficiary' => $request->beneficiary,
                'account_number' => $request->account_number,
                'routing_number' => $request->routing_number,
                'swift_code' => $request->swift_code,
                'wire_instructions' => $request->wire_instructions,
                'crypto_usdt_address' => $request->crypto_usdt_address,
                'crypto_btc_address' => $request->crypto_btc_address,
                'ach_details' => $request->ach_details,
                'late_fee_enabled' => $request->has('late_fee_enabled') ? 1 : 0,
                'late_fee_percent' => $request->late_fee_percent ?: 5,
                'grace_period_days' => $request->grace_period_days ?: 7,
            ];

            // Save to DB
            $general = \App\Models\GeneralSettings::first();
            if ($general) {
                $general->payment_settings = $paymentData;
                $general->save();
            }

            // Save to file
            $settPath = storage_path('settings.json');
            $all = file_exists($settPath) ? json_decode(file_get_contents($settPath), true) : [];
            $all['payment'] = $paymentData;
            file_put_contents($settPath, json_encode($all, JSON_PRETTY_PRINT));

            return $this->backWithSuccess('Escrow & Payment Depository settings saved successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
