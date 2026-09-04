@php
    $companySettings = \App\Models\GeneralSettings::first();
    $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');
    
    $contactPage = \App\Models\PageSettings::where('name', 'contact')->first();
    $contactInfo = $contactPage ? $contactPage->sections()->where('name', 'contact_info')->first() : null;
    $emailInfo = $contactPage ? $contactPage->sections()->where('name', 'email')->first() : null;
    
    $companyAddress = env('COMPANY_ADDRESS') ?: ($contactInfo ? implode(', ', array_filter([$contactInfo->line_one, $contactInfo->line_two])) : '582 Professional Way, Financial District, NY');
    $companyPhone = env('COMPANY_PHONE') ?: ($contactInfo && $contactInfo->line_two && preg_match('/[0-9]/', $contactInfo->line_two) ? $contactInfo->line_two : '(216) 230-1837');
    $companyEmail = env('COMPANY_EMAIL') ?: ($emailInfo ? $emailInfo->line_one : 'support@yourcpaexpert.com');
    
    // Resolve master logo image
    $logoSrc = '';
    if (file_exists(public_path('upload/settings/ashish_master_logo.png'))) {
        $logoSrc = asset('upload/settings/ashish_master_logo.png');
    } elseif (!empty($logoFavicon) && !empty($logoFavicon->logo) && file_exists(public_path($logoFavicon->logo))) {
        $logoSrc = asset($logoFavicon->logo);
    } elseif (file_exists(public_path('upload/settings/logo.png'))) {
        $logoSrc = asset('upload/settings/logo.png');
    }
@endphp

<div class="company-executive-letterhead" style="margin-bottom: 25px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Georgia', serif;">
    <!-- Top Brand Accent Bar (Ashish Master Style) -->
    <div style="height: 4px; background: linear-gradient(90deg, #1e3c72 0%, #2a5298 50%, #d97706 100%); margin-bottom: 20px; border-radius: 2px;"></div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px;">
        <tr>
            <td style="vertical-align: middle; text-align: left; width: 60%;">
                @if($logoSrc)
                    <img src="{{ $logoSrc }}" alt="{{ $companyName }}" style="max-height: 70px; width: auto; max-width: 250px; display: block; margin-bottom: 6px;">
                @else
                    <div style="font-size: 24px; font-weight: 800; color: #1e3c72; letter-spacing: 0.8px; text-transform: uppercase;">
                        {{ $companyName }}
                    </div>
                @endif
                <div style="font-size: 10.5px; font-weight: 700; color: #b45309; letter-spacing: 1.5px; text-transform: uppercase; margin-top: 2px;">
                    Privileged Legal &amp; CPA Advisory Services &bull; Forensic Recovery
                </div>
            </td>
            <td style="vertical-align: middle; text-align: right; width: 40%; font-size: 11px; color: #475569; line-height: 1.45;">
                <strong style="color: #1e3c72; font-size: 12px;">Corporate Headquarters</strong><br>
                <span>{{ $companyAddress }}</span><br>
                <span><strong>Phone:</strong> {{ $companyPhone }}</span><br>
                <span><strong>Email:</strong> {{ $companyEmail }}</span>
            </td>
        </tr>
    </table>

    <!-- Institutional Double Rule -->
    <div style="border-bottom: 2px solid #1e3c72; position: relative;">
        <div style="height: 1px; background: #fecc56; margin-top: 2px;"></div>
    </div>
</div>
