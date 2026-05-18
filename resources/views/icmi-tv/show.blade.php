@extends('layouts.page')

@section('title', $video->title.' - ICMI TV')
@section('meta_title', $video->seo_title ?: $video->title.' - ICMI TV')
@section('meta_description', $video->seo_description ?: \Illuminate\Support\Str::limit((string) $video->description, 160))
@section('meta_image', $video->thumbnail ? asset('storage/'.$video->thumbnail) : 'https://img.youtube.com/vi/'.$video->youtube_id.'/hqdefault.jpg')
@section('body_class', 'gray-bg bg-2')
@section('top_header_cover_class', 'bg_cover')

@section('content')
<div class="binduz-er-breadcrumb-area">
    <div class="container"><div class="row"><div class="col-lg-12"><div class="binduz-er-breadcrumb-box"><nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('ui.menu.home') }}</a></li><li class="breadcrumb-item"><a href="{{ route('icmi-tv') }}">{{ __('ui.pages.tv.title') }}</a></li><li class="breadcrumb-item active" aria-current="page">{{ $video->title }}</li></ol></nav></div></div></div></div>
</div>

<section class="binduz-er-blog-details-area pb-60 pt-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="binduz-er-blog-details-box">
                    <div class="binduz-er-meta-item mb-2">
                        <div class="binduz-er-meta-categories"><a href="#">{{ __('ui.pages.tv.title') }}</a></div>
                        <div class="binduz-er-meta-date"><span><i class="fal fa-calendar-alt"></i> {{ optional($video->published_at)->format('d M Y') }}</span></div>
                    </div>
                    <h1 class="binduz-er-title mb-3">{{ $video->title }}</h1>

                    <div class="ratio ratio-16x9 mb-4">
                        <iframe
                            src="{{ $video->embed_url }}"
                            title="{{ $video->title }}"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allowfullscreen>
                        </iframe>
                    </div>

                    @if($video->description)
                        <div class="binduz-er-text">
                            {!! nl2br(e($video->description)) !!}
                        </div>
                    @endif
                </article>
            </div>
            <div class="col-lg-4">
                <div class="binduz-er-sidebar-latest-post">
                    <div class="binduz-er-sidebar-title">
                        <h4 class="binduz-er-title">{{ __('ui.pages.tv.related') }}</h4>
                    </div>
                    <div class="binduz-er-sidebar-latest-post-box">
                        @forelse($relatedVideos as $related)
                            <div class="binduz-er-sidebar-latest-post-item">
                                <div class="binduz-er-thumb">
                                    <img src="{{ $related->thumbnail ? asset('storage/'.$related->thumbnail) : 'https://img.youtube.com/vi/'.$related->youtube_id.'/hqdefault.jpg' }}" alt="{{ $related->title }}">
                                </div>
                                <div class="binduz-er-content">
                                    <span><i class="fal fa-calendar-alt"></i> {{ optional($related->published_at)->format('d M Y') }}</span>
                                    <h4 class="binduz-er-title"><a href="{{ route('icmi-tv.show', $related->slug) }}">{{ $related->title }}</a></h4>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">{{ __('ui.status.no_related_video') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
