@extends('layouts.page')

@section('title', __('ui.pages.tv.title').' - ICMI Kaltim')
@section('meta_title', __('ui.pages.tv.title').' - ICMI Kaltim')
@section('meta_description', __('ui.pages.tv.title').' ICMI Kaltim.')
@section('body_class', 'gray-bg bg-2')
@section('top_header_cover_class', 'bg_cover')

@section('content')
@php
    $mainVideo = $featuredVideos->first();
    $sideVideos = $featuredVideos->slice(1);
    $programLabels = [
        __('ui.pages.tv.labels.news'),
        __('ui.pages.tv.labels.dialogue'),
        __('ui.pages.tv.labels.imtaq'),
        __('ui.pages.tv.labels.figure'),
        __('ui.pages.tv.labels.update'),
    ];
@endphp

<section class="icmi-tv-showcase">
    <div class="container">
        <div class="icmi-tv-showcase__head">
            <h2>{{ __('ui.pages.tv.title') }}</h2>
            <form method="GET" action="{{ route('icmi-tv') }}" class="icmi-tv-search icmi-listing-search" data-auto-search="true">
                <input type="search" name="q" value="{{ $search }}" placeholder="{{ __('ui.pages.tv.search_placeholder') }}">
            </form>
        </div>

        @if($mainVideo)
            <div class="row g-4">
                <div class="col-lg-6">
                    <article class="icmi-tv-main-card">
                        <div class="ratio ratio-16x9">
                            <iframe
                                src="{{ $mainVideo->embed_url }}"
                                title="{{ $mainVideo->translated('title') }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <div class="icmi-tv-main-card__body">
                            <h3><a href="{{ route('icmi-tv.show', $mainVideo->slug) }}">{{ $mainVideo->translated('title') }}</a></h3>
                            <p><i class="fal fa-calendar-alt"></i> {{ optional($mainVideo->published_at)->translatedFormat('l, j M Y') }}</p>
                        </div>
                    </article>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        @foreach($sideVideos as $index => $video)
                            <div class="col-md-6">
                                <article class="icmi-tv-side-card">
                                    <a class="thumb" href="{{ route('icmi-tv.show', $video->slug) }}">
                                        <img src="{{ $video->thumbnail ? asset('storage/'.$video->thumbnail) : 'https://img.youtube.com/vi/'.$video->youtube_id.'/hqdefault.jpg' }}" alt="{{ $video->translated('title') }}">
                                        <span class="badge">{{ $programLabels[$index % count($programLabels)] }}</span>
                                        <span class="play"><i class="fas fa-play"></i></span>
                                    </a>
                                    <h4><a href="{{ route('icmi-tv.show', $video->slug) }}">{{ \Illuminate\Support\Str::limit($video->translated('title'), 62) }}</a></h4>
                                    <p><i class="fal fa-calendar-alt"></i> {{ optional($video->published_at)->translatedFormat('D, j M Y') }}</p>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-light border mb-0">{{ __('ui.status.no_published_video') }}</div>
        @endif
    </div>
</section>

<section class="icmi-tv-program">
    <div class="container">
        <div class="icmi-tv-program__head">
            <h2>{{ __('ui.pages.tv.program') }}</h2>
        </div>

        <div class="row g-4">
            @forelse($programVideos as $index => $video)
                <div class="col-lg-4 col-md-6">
                    <article class="icmi-tv-program-card">
                        <a class="thumb" href="{{ route('icmi-tv.show', $video->slug) }}">
                            <img src="{{ $video->thumbnail ? asset('storage/'.$video->thumbnail) : 'https://img.youtube.com/vi/'.$video->youtube_id.'/hqdefault.jpg' }}" alt="{{ $video->translated('title') }}">
                            <span class="badge">{{ $programLabels[$index % count($programLabels)] }}</span>
                            <span class="play"><i class="fas fa-play"></i></span>
                        </a>
                        <h4><a href="{{ route('icmi-tv.show', $video->slug) }}">{{ \Illuminate\Support\Str::limit($video->translated('title'), 72) }}</a></h4>
                        <p><i class="fal fa-calendar-alt"></i> {{ optional($video->published_at)->translatedFormat('l, j M Y') }}</p>
                    </article>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-light border">{{ __('ui.status.no_published_video') }}</div></div>
            @endforelse
        </div>

        <div class="mt-4">{{ $programVideos->links() }}</div>
    </div>
</section>
@endsection

