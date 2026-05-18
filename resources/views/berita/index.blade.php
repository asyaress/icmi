@extends('layouts.page')

@section('title', __('ui.pages.news.title').' - ICMI Kaltim')
@section('meta_title', __('ui.pages.news.title').' - ICMI Kaltim')
@section('meta_description', __('ui.pages.news.latest'))
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('ui.pages.news.title') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="binduz-er-main-posts-area pt-30 pb-60">
    <div class="container">
        <form method="GET" action="{{ route('berita') }}" class="row g-2 mb-4 icmi-listing-search" data-auto-search="true">
            <div class="col-md-8">
                <input type="search" name="q" class="form-control" placeholder="{{ __('ui.pages.news.search_placeholder') }}" value="{{ $search }}">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">{{ __('ui.common.all_categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" @selected($categorySlug === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="row">
            <div class="col-lg-12">
                <div class="binduz-er-video-post-topbar mb-20">
                    <div class="binduz-er-video-post-title">
                        <h3 class="binduz-er-title">{{ __('ui.pages.news.latest') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 icmi-listing-grid">
            @forelse($posts as $post)
                <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
                    <div class="binduz-er-main-posts-item w-100 h-100">
                        <div class="binduz-er-trending-news-list-box">
                            <div class="binduz-er-thumb">
                                <a href="{{ route('berita.show', $post->slug) }}">
                                    <img src="{{ $post->featured_image ? asset('storage/'.$post->featured_image) : asset('assets/images/main-post-thumb-1.jpg') }}" alt="{{ $post->title }}">
                                </a>
                            </div>
                            <div class="binduz-er-content">
                                <div class="binduz-er-meta-item">
                                    <div class="binduz-er-meta-categories">
                                        <a href="#">{{ $post->category->name ?? __('ui.menu.news') }}</a>
                                    </div>
                                    <div class="binduz-er-meta-date">
                                        <span><i class="fal fa-calendar-alt"></i> {{ optional($post->published_at)->format('d M Y') }}</span>
                                    </div>
                                </div>
                                <div class="binduz-er-trending-news-list-title">
                                    <h4 class="binduz-er-title"><a href="{{ route('berita.show', $post->slug) }}">{{ $post->title }}</a></h4>
                                    <p>{{ $post->excerpt }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light border">{{ __('ui.status.no_published_news') }}</div>
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $posts->links() }}
        </div>
    </div>
</section>
@endsection
