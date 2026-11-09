<head>
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="description" content="@yield('meta_description')"/>
    <meta name="keywords" content="@yield('meta_keyword')"/>
    <meta property="og:title" content="{{clean($generalSetting?$generalSetting->og_meta_title:'')}}" />
    <meta property="og:description" content="{{clean($generalSetting?$generalSetting->og_meta_description:'')}}" />
    <meta property="og:image" content="{{$generalSetting?$generalSetting->og_meta_image:''}}" />
    <meta name="author" content="{{clean($generalSetting?$generalSetting->author_name:'')}}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->

    <!-- Favicon & PWA Application Manifest -->
    <link href="{{ $logoFavicon?$logoFavicon->favicon:'' }}" rel="shortcut icon" type="image/png">
    <link rel="manifest" href="{{ asset('client-manifest.json') }}">
    <meta name="theme-color" content="#fecc56">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Client Portal">
    <link rel="apple-touch-icon" href="{{ asset('upload/settings/logo.png') }}">

    <!-- High-Performance Font Preconnect & DNS Prefetch -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <!------------------------------------------
      Main CSS File
    <------------------------------------------>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/theme1/css/bootstrap.min.css') }}">
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/theme1/css/style.css') }}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/theme1/css/responsive.css') }}">

    @yield('page-css')
    {!! $insertHeaderFooter?$insertHeaderFooter->header:'' !!}

    <!-- Rich Structured Data Schema (JSON-LD) for Google Legal & Accounting Rich Results -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "AccountingService",
          "@id": "{{ url('/') }}#accounting",
          "name": "{{ config('app.name', 'Your CPA Expert') }}",
          "url": "{{ url('/') }}",
          "telephone": "{{ \App\Models\GeneralSettings::getCompanyPhone() }}",
          "email": "{{ \App\Models\GeneralSettings::getCompanyEmail() }}",
          "priceRange": "$$",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ \App\Models\GeneralSettings::getCompanyAddress() }}",
            "addressLocality": "Washington",
            "addressRegion": "DC",
            "addressCountry": "US"
          },
          "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
            "opens": "08:00",
            "closes": "18:00"
          }
        },
        {
          "@type": "LegalService",
          "@id": "{{ url('/') }}#legal",
          "name": "{{ config('app.name', 'Your CPA Expert') }} Legal & Audit Counsel",
          "url": "{{ url('/') }}",
          "telephone": "{{ \App\Models\GeneralSettings::getCompanyPhone() }}",
          "priceRange": "$$$"
        }
      ]
    }
    </script>
</head>

