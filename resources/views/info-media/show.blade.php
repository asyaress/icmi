@extends('layouts.page')

@section('title', $post->translated('title').' - Info Media')
@section('meta_title', $post->translated('seo_title') ?: $post->translated('title').' - Info Media')
@section('meta_description', $post->translated('seo_description') ?: \Illuminate\Support\Str::limit((string) ($post->translated('excerpt') ?: strip_tags($post->translated('content'))), 160))
@section('meta_image', $post->featured_image ? asset('storage/'.$post->featured_image) : asset('logo-icmi.png'))
@section('body_class', 'gray-bg bg-2')
@section('top_header_cover_class', 'bg_cover')

@section('content')
<div class="binduz-er-breadcrumb-area"><div class="container"><div class="row"><div class="col-lg-12"><div class="binduz-er-breadcrumb-box"><nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('ui.menu.home') }}</a></li><li class="breadcrumb-item"><a href="{{ route('info-media') }}">{{ __('ui.pages.info_media.title') }}</a></li><li class="breadcrumb-item active" aria-current="page">{{ $post->translated('title') }}</li></ol></nav></div></div></div></div></div>
<section class="binduz-er-blog-details-area pb-60 pt-30">
    <div class="container"><div class="row"><div class="col-lg-8">
        <article class="binduz-er-blog-details-box">
            <div class="binduz-er-meta-item mb-2"><div class="binduz-er-meta-categories"><a href="#">{{ optional($post->category)->translated('name') ?? __('ui.pages.info_media.title') }}</a></div><div class="binduz-er-meta-date"><span><i class="fal fa-calendar-alt"></i> {{ optional($post->published_at)->format('d M Y H:i') }}</span></div></div>
            <h1 class="binduz-er-title mb-3">{{ $post->translated('title') }}</h1>
            @if($post->featured_image)
                <div class="binduz-er-thumb mb-4"><img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->translated('title') }}"></div>
            @endif
            @php
                $isHtmlContent = strip_tags((string) $post->translated('content')) !== (string) $post->translated('content');
            @endphp
            <div class="binduz-er-text">{!! $isHtmlContent ? $post->translated('content') : nl2br(e($post->translated('content'))) !!}</div>
        </article>
    </div><div class="col-lg-4"><div class="binduz-er-sidebar-latest-post"><div class="binduz-er-sidebar-title"><h4 class="binduz-er-title">{{ __('ui.pages.info_media.related') }}</h4></div><div class="binduz-er-sidebar-latest-post-box">
        @forelse($relatedPosts as $related)
            <div class="binduz-er-sidebar-latest-post-item"><div class="binduz-er-thumb"><img src="{{ $related->featured_image ? asset('storage/'.$related->featured_image) : asset('assets/images/latest-post-1.jpg') }}" alt="{{ $related->translated('title') }}"></div><div class="binduz-er-content"><span><i class="fal fa-calendar-alt"></i> {{ optional($related->published_at)->format('d M Y') }}</span><h4 class="binduz-er-title"><a href="{{ route('info-media.show', $related->slug) }}">{{ $related->translated('title') }}</a></h4></div></div>
        @empty
            <p class="text-muted">{{ __('ui.status.no_related_info') }}</p>
        @endforelse
    </div></div></div></div></div>
</section>
@endsection




