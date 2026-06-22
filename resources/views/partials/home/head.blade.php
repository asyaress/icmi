<!--====== Required meta tags ======-->
@php
    $siteName = \App\Models\Setting::get('site_name', 'ICMI Kaltim');
    $siteTagline = \App\Models\Setting::get('site_tagline', 'Website resmi ICMI Kaltim');
    $defaultTitle = \App\Models\Setting::get('meta_default_title', $siteName . ' | ' . $siteTagline);
    $defaultDescription = \App\Models\Setting::get('meta_default_description', 'Portal resmi ICMI Kaltim untuk berita, opini tokoh, info media, galeri, dan ICMI TV.');
    $defaultKeywords = \App\Models\Setting::get('meta_default_keywords', 'ICMI Kaltim, ICMI Kalimantan Timur, berita ICMI, opini tokoh, info media, galeri ICMI, ICMI TV');

    $metaTitle = trim($__env->yieldContent('meta_title')) ?: trim($__env->yieldContent('title')) ?: $defaultTitle;
    $metaDescription = trim($__env->yieldContent('meta_description')) ?: $defaultDescription;
    $metaKeywords = trim($__env->yieldContent('meta_keywords')) ?: $defaultKeywords;
    $metaImage = trim($__env->yieldContent('meta_image')) ?: asset('logo-icmi.png');
    $canonical = trim($__env->yieldContent('canonical_url')) ?: url()->current();
    $robots = trim($__env->yieldContent('meta_robots')) ?: 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1';

    $query = request()->query();
    unset($query['lang']);
    $xDefaultUrl = url()->current() . (empty($query) ? '' : ('?' . http_build_query($query)));
    $hreflangId = request()->fullUrlWithQuery(['lang' => 'id']);
    $hreflangEn = request()->fullUrlWithQuery(['lang' => 'en']);
@endphp
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">
<link rel="alternate" hreflang="id-ID" href="{{ $hreflangId }}">
<link rel="alternate" hreflang="en-US" href="{{ $hreflangEn }}">
<link rel="alternate" hreflang="x-default" href="{{ $xDefaultUrl }}">

<meta property="og:type" content="website">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ app()->getLocale() === 'en' ? 'en_US' : 'id_ID' }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $metaImage }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $metaImage }}">

<!--====== Title ======-->
<title>{{ $metaTitle }}</title>

<!--====== Favicon Icon ======-->
<link rel="shortcut icon" href="{{ asset('logo-icmi.png') }}" type="image/png">

<!--====== Bootstrap css ======-->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

<!--====== Fontawesome css ======-->
<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">

<!--====== nice select css ======-->
<link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">

<!--====== Magnific Popup css ======-->
<link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">

<!--====== Slick css ======-->
<link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">

<!--====== Default css ======-->
<link rel="stylesheet" href="{{ asset('assets/css/default.css') }}">

<!--====== Style css ======-->
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

<!--====== ICMI Theme css ======-->
<link rel="stylesheet" href="{{ route('icmi-assets', ['asset' => 'theme.css', 'v' => filemtime(public_path('assets/css/icmi-theme.css'))]) }}">
