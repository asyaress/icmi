@extends('layouts.home')

@section('meta_title', ($siteName ?? 'ICMI Kaltim').' - '.($siteTagline ?? 'Website resmi ICMI Kaltim'))
@section('meta_description', $metaDescription ?? 'Portal resmi ICMI Kaltim.')
@section('title', ($siteName ?? 'ICMI Kaltim').' - '.($siteTagline ?? 'Website resmi ICMI Kaltim'))

@section('content')
    @if($homeConfig['show_hero'])
        @include('partials.home.sections.hero')
    @endif

    @if($homeConfig['show_trending'])
        @include('partials.home.sections.trending')
    @endif

    @if($homeConfig['show_featured'])
        @include('partials.home.sections.featured')
    @endif

    @if($homeConfig['show_video'])
        @include('partials.home.sections.video-post')
    @endif

    @if($homeConfig['show_trending_today'])
        @include('partials.home.sections.trending-today')
    @endif

    @if($homeConfig['show_main_posts'])
        @include('partials.home.sections.main-posts')
    @endif
@endsection
