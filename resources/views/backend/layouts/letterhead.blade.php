@php
    $letterheadTemplate = \App\Models\DocumentTemplate::where('key', 'company_letterhead')->where('status', true)->first();
    $companySettings = \App\Models\GeneralSettings::first();
    $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');
    
    $contactPage = \App\Models\PageSettings::where('name', 'contact')->first();
    $contactInfo = $contactPage ? $contactPage->sections()->where('name', 'contact_info')->first() : null;
    $emailInfo = $contactPage ? $contactPage->sections()->where('name', 'email')->first() : null;
    
    $companyAddress = env('COMPANY_ADDRESS') ?: ($contactInfo ? implode(', ', array_filter([$contactInfo->line_one, $contactInfo->line_two])) : '582 Professional Way, Financial District, DC');
    $companyPhone = env('COMPANY_PHONE') ?: ($contactInfo && $contactInfo->line_two && preg_match('/[0-9]/', $contactInfo->line_two) ? $contactInfo->line_two : '(216) 230-1837');
    $companyEmail = env('COMPANY_EMAIL') ?: ($emailInfo ? $emailInfo->line_one : 'support@yourcpaexpert.com');
    
    $logoHtml = '';
    if (!empty($logoFavicon) && !empty($logoFavicon->logo)) {
        $logoHtml = '<img src="' . asset($logoFavicon->logo) . '" alt="' . e($companyName) . '" style="max-height: 60px; margin-bottom: 10px; display: inline-block;">';
    }
@endphp

@if($letterheadTemplate)
    @php
        $placeholders = [
            '{{company_logo}}' => $logoHtml,
            '{{company_name}}' => $companyName,
            '{{company_address}}' => $companyAddress,
            '{{company_phone}}' => $companyPhone,
            '{{company_email}}' => $companyEmail,
        ];
        $formattedLetterhead = str_replace(array_keys($placeholders), array_values($placeholders), $letterheadTemplate->content);
    @endphp
    {!! $formattedLetterhead !!}
@else
    <div style="text-align: center; border-bottom: 3px double #000; padding-bottom: 20px; margin-bottom: 30px;">
        {!! $logoHtml !!}
        <h1 style="font-size: 26px; text-transform: uppercase; margin: 10px 0 5px 0; color: #111; letter-spacing: 1px; font-family: sans-serif;">{{ $companyName }}</h1>
        <p style="margin: 5px 0 0 0; font-size: 13px; color: #555; font-style: normal; font-family: sans-serif; font-weight: normal;">
            {{ $companyAddress }} &bull; Phone: {{ $companyPhone }} &bull; Email: {{ $companyEmail }}
        </p>
    </div>
@endif
