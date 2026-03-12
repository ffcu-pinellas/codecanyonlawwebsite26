<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Attorney;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Contact;
use App\Models\DynamicPage;
use App\Models\FooterSettings;
use App\Models\GeneralSettings;
use App\Models\ReliefRequest;
use App\Models\HeaderFooterSettings;
use App\Models\HeaderSettings;
use App\Models\LogoSettings;
use App\Models\MenuCategory;
use App\Models\SEOSettings;
use App\Models\SocialMediaSettings;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $headerSetting = HeaderSettings::first();
        $logoFavicon = LogoSettings::first();
        $categories = BlogCategory::all();
        $popular_post = Blog::where('is_popular', true)->take(config('page.footer.column3_popular_post_title_number'))->get();
        $featured_post = Blog::where('is_featured', true)->take(config('page.footer.column2_recent_post_number'))->get();
        $menus = MenuCategory::all();
        $headerMenu = $menus->count() > 0 ? $this->headerMenueView($menus[0]) : null;
        $social_media = SocialMediaSettings::all();
        $contactMassage = Contact::where('status', 1)->get();
        $appointmentMassage = Appointment::where('status', 1)->get();
        $generalSetting = GeneralSettings::first();
        $seoSetting = SEOSettings::first();
        $footerData = FooterSettings::first();
        $systemPages = DynamicPage::orderBy('id', 'desc')->get();
        $insertHeaderFooter = HeaderFooterSettings::first();
        $newReliefRequestCount = ReliefRequest::where('viewed', false)->count();

        View::share('headerSetting', $headerSetting);
        View::share('logoFavicon', $logoFavicon);
        View::share('popular_post', $popular_post);
        View::share('featured_post', $featured_post);
        View::share('categories', $categories);
        View::share('headerMenu', $headerMenu);
        View::share('social_media', $social_media);
        View::share('contactMassage', $contactMassage);
        View::share('appointmentMassage', $appointmentMassage);
        View::share('generalSetting', $generalSetting);
        View::share('seoSetting', $seoSetting);
        View::share('footerData', $footerData);
        View::share('systemPages', $systemPages);
        View::share('insertHeaderFooter', $insertHeaderFooter);
        View::share('newReliefRequestCount', $newReliefRequestCount);
    }


    public function backWithError($message)
    {
        $notification = [
            'message' => $message,
            'alert-type' => 'error'
        ];
        return back()->with($notification);
    }


    public function backWithSuccess($message)
    {
        $notification = [
            'message' => $message,
            'alert-type' => 'success'
        ];
        return back()->with($notification);
    }

    public function backWithWarning($message)
    {
        $notification = [
            'message' => $message,
            'alert-type' => 'warning'
        ];
        return back()->with($notification);
    }

    public function headerMenueView($headerMenu)
    {
        $row1 = [];
        if ($headerMenu) {
            foreach ($headerMenu->menus()->where('parent_id', null)->orderBy('position', 'ASC')->get() as $menu) {
                $row2 = [];
                if ($menu->childs->count()) {
                    foreach ($menu->childs()->orderBy('position', 'ASC')->get() as $menuItem1) {
                        $row3 = [];
                        if ($menuItem1->childs->count() > 0) {
                            foreach ($menuItem1->childs()->orderBy('position', 'ASC')->get() as $menuItem2) {
                                $row4 = [];
                                if ($menuItem2->childs->count() > 0) {
                                    foreach ($menuItem2->childs()->orderBy('position', 'ASC')->get() as $menuItem3) {
                                        $row4[] = '<a class="dropdown-item" href="' . $menuItem3->href . '" title="' . $menuItem3->title . '" target="' . $menuItem3->target . '" ' . ($menuItem3->childs->count() > 0 ? 'data-toggle="dropdown"' : '') . ' aria-haspopup="true" aria-expanded="false">' . __($menuItem3->text) . ' </a>';
                                    }
                                }

                                $row3[] = '<div class="dropdown"><a class="' . ($menuItem2->childs->count() > 0 ? 'dropdown-toggle' : '') . ' dropdown-item" href="' . ($menuItem2->childs->count() > 0 ? 'javascript:void(0)' : $menuItem2->href) . '" target="' . $menuItem2->target . '" title="' . $menuItem2->title . '" ' . ($menuItem2->childs->count() > 0 ? 'data-toggle="dropdown"' : '') . ' aria-haspopup="true" aria-expanded="false">' . __($menuItem2->text) . '</a>' . implode(" ", $row4) . '</div>';
                            }
                        }
                        $row2[] = '<a class="dropdown-item  ' . ($menuItem1->childs->count() > 0 ? 'dropdown-toggle' : '') . '" href="' . ($menuItem1->childs->count() > 0 ? 'javascript:void(0)' : url($menuItem1->href)) . '" target="' . $menuItem1->target . '" title="' . $menuItem1->title . '" ' . ($menuItem1->childs->count() > 0 ? 'data-toggle="dropdown"' : '') . ' aria-haspopup="true" aria-expanded="false">' . __($menuItem1->text) . '</a>' . implode(" ", $row3);
                    }
                }

                $row1[] = '<li class="nav-item ' . ($menu->childs->count() > 0 ? 'dropdown' : '') . '">
                                            <a href="' . ($menu->childs->count() > 0 ? 'javascript:void(0)' : $menu->href) . '" class="nav-link ' . ($menu->childs->count() > 0 ? 'dropdown-toggle' : '') . '" target="' . $menu->target . '" title="' . $menu->title . '" ' . ($menu->childs->count() > 0 ? 'data-toggle="dropdown"' : '') . ' aria-haspopup="true" aria-expanded="false">' . __($menu->text) . '</a>' . '<div class="dropdown-menu">'.implode("", $row2).'</div>' . '</li>';
            }
        }
        $output = '<ul class="navbar-nav ml-xl-auto">' . implode(" ", $row1) . '</ul>';
        return $output;
    }
}
