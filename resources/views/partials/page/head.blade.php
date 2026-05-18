<!--====== Required meta tags ======-->
@php
    $defaultTitle = \App\Models\Setting::get('site_name', 'ICMI Kaltim');
    $metaTitle = trim($__env->yieldContent('meta_title')) ?: trim($__env->yieldContent('title')) ?: $defaultTitle;
    $metaDescription = trim($__env->yieldContent('meta_description')) ?: \App\Models\Setting::get('meta_default_description', 'Portal resmi ICMI Kaltim.');
    $metaImage = trim($__env->yieldContent('meta_image')) ?: asset('logo-icmi.png');
    $canonical = trim($__env->yieldContent('canonical_url')) ?: url()->current();
@endphp
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="{{ $metaDescription }}">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="robots" content="index,follow">
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:type" content="article">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
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
<link rel="stylesheet" href="{{ asset('assets/css/icmi-theme.css') }}">
