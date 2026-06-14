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
use Illuminate\Support\Facades\Mail;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        // 1. Load basic settings (less likely to fail but wrapped just in case)
        $headerSetting = null; $logoFavicon = null; $categories = []; $popular_post = []; 
        $featured_post = []; $menus = []; $headerMenu = null; $social_media = []; 
        $contactMassage = []; $appointmentMassage = []; $generalSetting = null; 
        $seoSetting = null; $footerData = null; $systemPages = []; $insertHeaderFooter = null;
        $newReliefRequestCount = 0;

        try { $headerSetting = HeaderSettings::first(); } catch (\Throwable $th) {}
        try {
            $logoFavicon = LogoSettings::first();
            if (!$logoFavicon) {
                $logoFavicon = new LogoSettings();
            }
            if (empty($logoFavicon->logo) || (!str_starts_with($logoFavicon->logo, 'http') && !file_exists(public_path($logoFavicon->logo)))) {
                if (file_exists(public_path('upload/settings/1731322171New_Project-removebg-preview.png'))) {
                    $logoFavicon->logo = '/upload/settings/1731322171New_Project-removebg-preview.png';
                }
            }
            if (empty($logoFavicon->favicon) || (!str_starts_with($logoFavicon->favicon, 'http') && !file_exists(public_path($logoFavicon->favicon)))) {
                if (file_exists(public_path('upload/settings/1632769376favicon.png'))) {
                    $logoFavicon->favicon = '/upload/settings/1632769376favicon.png';
                } elseif (file_exists(public_path('upload/settings/1631508115dna3emDAC.png'))) {
                    $logoFavicon->favicon = '/upload/settings/1631508115dna3emDAC.png';
                }
            }
        } catch (\Throwable $th) {}
        try { $categories = BlogCategory::all(); } catch (\Throwable $th) {}
        try { $popular_post = Blog::where('is_popular', true)->take(config('page.footer.column3_popular_post_title_number'))->get(); } catch (\Throwable $th) {}
        try { $featured_post = Blog::where('is_featured', true)->take(config('page.footer.column2_recent_post_number'))->get(); } catch (\Throwable $th) {}
        try { $menus = MenuCategory::all(); } catch (\Throwable $th) {}
        try { $headerMenu = $menus->count() > 0 ? $this->headerMenueView($menus[0]) : null; } catch (\Throwable $th) {}
        try { $social_media = SocialMediaSettings::all(); } catch (\Throwable $th) {}
        try { $contactMassage = Contact::where('status', 1)->get(); } catch (\Throwable $th) {}
        try { $appointmentMassage = Appointment::where('status', 1)->get(); } catch (\Throwable $th) {}
        try { $generalSetting = GeneralSettings::first(); } catch (\Throwable $th) {}
        try { $seoSetting = SEOSettings::first(); } catch (\Throwable $th) {}
        try { $footerData = FooterSettings::first(); } catch (\Throwable $th) {}
        try { $systemPages = DynamicPage::orderBy('id', 'desc')->get(); } catch (\Throwable $th) {}
        try { $insertHeaderFooter = HeaderFooterSettings::first(); } catch (\Throwable $th) {}
        try { $newReliefRequestCount = \App\Models\ClientCase::where('status', 'pending')->count(); } catch (\Throwable $th) {}
        $pendingCasesCount = $newReliefRequestCount;

        // 2. share variables with all views
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
        View::share('pendingCasesCount', $pendingCasesCount);
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

    protected function sendSlackNotification($message)
    {
        $webhookUrl = env('SLACK_WEBHOOK_URL');
        if (empty($webhookUrl)) {
            return;
        }

        try {
            $ch = curl_init($webhookUrl);
            $payload = json_encode(['text' => $message]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);
        } catch (\Throwable $e) {
            // Fail silently
        }
    }

    protected function getHtmlEmailWrapper($subject, $bodyText, $recipientName = 'Valued Member')
    {
        $generalSettings = \App\Models\GeneralSettings::first();
        $appName = $generalSettings && $generalSettings->site_name ? $generalSettings->site_name : env('APP_NAME', 'Your CPA Expert');
        
        $contactPage = \App\Models\PageSettings::where('name', 'contact')->first();
        $contactInfo = $contactPage ? $contactPage->sections()->where('name(' . 'contact_info' . ')') ?: $contactPage->sections()->where('name', 'contact_info')->first() : null;
        $emailInfo = $contactPage ? $contactPage->sections()->where('name', 'email')->first() : null;

        // Address
        $companyAddress = env('COMPANY_ADDRESS');
        if (!$companyAddress && $contactInfo) {
            $addressParts = array_filter([$contactInfo->line_one, $contactInfo->line_two]);
            if (!empty($addressParts)) {
                $companyAddress = implode(', ', $addressParts);
            }
        }
        if (!$companyAddress) {
            $companyAddress = '582 Professional Way, Financial District, DC';
        }

        // Phone
        $companyPhone = env('COMPANY_PHONE');
        if (!$companyPhone && $contactInfo && $contactInfo->line_two && preg_match('/[0-9]/', $contactInfo->line_two)) {
            $companyPhone = $contactInfo->line_two;
        }
        if (!$companyPhone) {
            $companyPhone = '(216) 230-1837';
        }

        // Email
        $companyEmail = env('COMPANY_EMAIL');
        if (!$companyEmail && $emailInfo && $emailInfo->line_one) {
            $companyEmail = $emailInfo->line_one;
        }
        if (!$companyEmail) {
            $companyEmail = 'support@yourcpaexpert.com';
        }

        // Logo
        $logoSettings = \App\Models\LogoSettings::first();
        $logoPath = $logoSettings ? $logoSettings->logo : null;
        if (!$logoPath || (!str_starts_with($logoPath, 'http') && !file_exists(public_path($logoPath)))) {
            if (file_exists(public_path('upload/settings/1731322171New_Project-removebg-preview.png'))) {
                $logoPath = '/upload/settings/1731322171New_Project-removebg-preview.png';
            }
        }
        $companyLogoUrl = null;
        if ($logoPath) {
            if (str_starts_with($logoPath, 'http')) {
                $companyLogoUrl = $logoPath;
            } else {
                $companyLogoUrl = rtrim(env('APP_URL', 'https://yourcpaexpert.com'), '/') . '/' . ltrim($logoPath, '/');
            }
        }

        $companyLogoHtml = '';
        if ($companyLogoUrl) {
            $companyLogoHtml = '<img class="logo-img" src="' . e($companyLogoUrl) . '" alt="' . e($appName) . '" style="max-height: 50px; width: auto; object-fit: contain;"><br>';
        }

        // Format body text beautifully
        $lines = explode("\n", $bodyText);
        $formattedBody = '<p style="font-size: 16px; font-weight: 600; color: #1e3c72; margin-top: 0; margin-bottom: 20px;">Dear ' . e($recipientName) . ',</p>';
        $inTable = false;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                if ($inTable) {
                    $formattedBody .= '</table>';
                    $inTable = false;
                }
                $formattedBody .= '<div style="height: 10px;"></div>';
                continue;
            }

            // Match dynamic key-value separator (e.g. "Label: value" or "**Label**: value")
            if (preg_match('/^\*?\*?([^*:]+)\*?\*?\s*:\s*(.+)$/', $line, $matches)) {
                $key = trim($matches[1]);
                $val = trim($matches[2]);

                if (!$inTable) {
                    $formattedBody .= '<table cellpadding="0" cellspacing="0" width="100%" style="margin: 15px 0; border: 1px solid #e2e8f0; border-collapse: separate; border-spacing: 0; border-radius: 6px; overflow: hidden; background-color: #fafbfc;">';
                    $inTable = true;
                }

                $formattedBody .= '<tr>'
                    . '<td style="padding: 10px 15px; width: 35%; font-weight: 600; color: #2d3748; border-bottom: 1px solid #edf2f7; font-size: 14px; background-color: #edf2f7;">' . e($key) . '</td>'
                    . '<td style="padding: 10px 15px; color: #4a5568; border-bottom: 1px solid #edf2f7; font-size: 14px;">' . e($val) . '</td>'
                    . '</tr>';
            } else {
                if ($inTable) {
                    $formattedBody .= '</table>';
                    $inTable = false;
                }
                $formattedBody .= '<p style="margin: 10px 0; font-size: 15px; color: #4a5568; line-height: 1.6;">' . e($line) . '</p>';
            }
        }

        if ($inTable) {
            $formattedBody .= '</table>';
        }

        $formattedBody .= '<p style="margin-top: 25px; margin-bottom: 0; font-size: 15px; color: #718096;">Best Regards,<br><strong style="color: #1e3c72;">' . e($appName) . ' Team</strong></p>';

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$subject}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #333333;
            -webkit-font-smoothing: antialiased;
        }
        table {
            border-collapse: collapse;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f6f9;
            padding-bottom: 40px;
        }
        .main-table {
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e1e8ed;
        }
        .brand-bar {
            height: 4px;
            background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);
        }
        .header {
            padding: 30px 40px;
            text-align: center;
            border-bottom: 1px solid #f0f3f6;
        }
        .logo-img {
            max-height: 50px;
            width: auto;
            margin-bottom: 10px;
            display: inline-block;
        }
        .company-title {
            font-size: 22px;
            font-weight: 700;
            color: #1e3c72;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .company-subtitle {
            font-size: 13px;
            color: #666666;
            margin-top: 8px;
            line-height: 1.4;
        }
        .content-td {
            padding: 40px;
            line-height: 1.6;
            font-size: 16px;
            color: #444444;
        }
        .content-td h2 {
            color: #1e3c72;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .footer-td {
            background-color: #f8f9fa;
            padding: 30px 40px;
            text-align: center;
            font-size: 13px;
            color: #777777;
            border-top: 1px solid #f0f3f6;
        }
        .footer-td p {
            margin: 6px 0;
        }
    </style>
</head>
<body>
    <center class="wrapper">
        <table class="main-table" align="center" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td class="brand-bar"></td>
            </tr>
            <tr>
                <td class="header">
                    {$companyLogoHtml}
                    <div class="company-title">{$appName}</div>
                    <div class="company-subtitle">
                        <strong>Corporate Office</strong><br>
                        Address: {$companyAddress}<br>
                        Phone: {$companyPhone} | Email: {$companyEmail}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="content-td">
                    {$formattedBody}
                </td>
            </tr>
            <tr>
                <td class="footer-td">
                    <p><strong>&copy; 2026 {$appName}</strong>. All Rights Reserved.</p>
                    <p style="font-style: italic; font-size: 11px; margin-bottom: 15px;">This is an automated notification. Please do not reply directly to this email.</p>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
HTML;
    }

    protected function sendEmailNotification($to, $subject, $body, $attachmentPath = null, $attachmentName = null, $trackingToken = null)
    {
        try {
            $user = \App\Models\User::where('email', $to)->first();
            $recipientName = $user ? $user->name : 'Valued Member';

            $htmlContent = $this->getHtmlEmailWrapper($subject, $body, $recipientName);
            
            if ($trackingToken) {
                $trackingUrl = route('document.track', $trackingToken);
                $htmlContent = str_replace('</body>', '<img src="' . $trackingUrl . '" width="1" height="1" style="display:none;" /></body>', $htmlContent);
            }

            Mail::html($htmlContent, function ($message) use ($to, $subject, $attachmentPath, $attachmentName) {
                $message->to($to)->subject($subject);
                if ($attachmentPath && file_exists($attachmentPath)) {
                    $options = [];
                    if ($attachmentName) {
                        $options['as'] = $attachmentName;
                        $options['mime'] = 'application/pdf';
                    }
                    $message->attach($attachmentPath, $options);
                }
            });
        } catch (\Throwable $e) {
            // Silent fallback if email servers are not configured
        }
    }

    protected function notifyAdminOfDocumentAction($documentLog, $action, $recipientNotes = null)
    {
        try {
            // Fetch admin user
            $admin = \App\Models\User::role('admin')->first() ?: \App\Models\User::first();
            $adminEmail = $admin ? $admin->email : 'admin@yourcpaexpert.com';
            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');

            $recipient = $documentLog->client ?: $documentLog->staff;
            $recipientName = $recipient ? $recipient->name : 'Valued Member';
            $recipientEmail = $recipient ? $recipient->email : $documentLog->recipient_email;

            // Formulate subject and body for email
            $subject = "Document Notice: " . $recipientName . " has " . strtoupper($action) . " '" . $documentLog->template_title . "'";
            
            $body = "Hello Administrator,\n\n"
                . "A recipient has performed an action on a generated document:\n\n"
                . "Document Title: " . $documentLog->template_title . "\n"
                . "Recipient Name: " . $recipientName . "\n"
                . "Recipient Email: " . $recipientEmail . "\n"
                . "Action Performed: " . strtoupper($action) . "\n";

            if ($recipientNotes) {
                $body .= "Recipient Notes / Comments: " . $recipientNotes . "\n";
            }

            $historyUrl = route('admin.document-templates.history');
            $body .= "\n"
                . "You can view the full tracking logs and document details in the Admin Portal here:\n"
                . $historyUrl . "\n\n"
                . "Best regards,\n"
                . "Operations System\n"
                . $companyName;

            // Send Email to Admin
            $this->sendEmailNotification($adminEmail, $subject, $body);

            // Send Telegram Notification to Admin
            $telegramMsg = "🔔 *Document Update Notice*\n\n"
                . "*Document:* " . $documentLog->template_title . " (" . $documentLog->template_key . ")\n"
                . "*Recipient:* " . $recipientName . " (" . $recipientEmail . ")\n"
                . "*Action:* " . strtoupper($action) . "\n";

            if ($recipientNotes) {
                $telegramMsg .= "*Recipient Notes:* " . $recipientNotes . "\n";
            }

            $telegramMsg .= "\n🔗 *View Details:* [Document History](" . $historyUrl . ")";

            \App\Models\GeneralSettings::sendTelegramNotification($telegramMsg);
        } catch (\Throwable $e) {
            // Silent fallback if notification tools fail
        }
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
