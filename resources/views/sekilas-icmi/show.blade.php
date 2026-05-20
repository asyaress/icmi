@extends('layouts.page')

@section('title', ($page->translated('seo_title') ?: $page->translated('title')).' - ICMI Kaltim')
@section('meta_title', $page->translated('seo_title') ?: ($page->translated('title').' - ICMI Kaltim'))
@section('meta_description', $page->translated('seo_description') ?: \Illuminate\Support\Str::limit(strip_tags((string) $page->translated('content')), 160))
@section('body_class', 'gray-bg bg-2')
@section('top_header_cover_class', 'bg_cover')

@section('content')
<div class="binduz-er-breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="binduz-er-breadcrumb-box">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('ui.menu.home') }}</a></li>
                            <li class="breadcrumb-item">{{ __('ui.pages.profile.section') }}</li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $page->translated('title') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="binduz-er-main-posts-area pt-30 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <article class="icmi-profile-article">
                    <h1 class="binduz-er-title mb-3">{{ $page->translated('title') }}</h1>
                    <div class="icmi-profile-meta d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <p class="mb-0">
                            {{ __('ui.pages.profile.posted_on') }}
                            {{ optional($page->published_at)->translatedFormat('l, j M Y H:i') }} WITA
                        </p>
                        @if($page->attachment_path)
                            <a class="btn btn-outline-secondary btn-sm" href="{{ asset('storage/'.$page->attachment_path) }}" target="_blank">
                                {{ __('ui.pages.profile.download') }}
                            </a>
                        @endif
                    </div>
                    <hr>

                    <div class="icmi-profile-content">
                        {!! $page->translated('content') !!}
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection

