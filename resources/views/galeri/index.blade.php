@extends('layouts.page')

@section('title', __('ui.pages.gallery.title').' - ICMI Kaltim')
@section('meta_title', __('ui.pages.gallery.title').' - ICMI Kaltim')
@section('meta_description', __('ui.pages.gallery.title').' ICMI Kaltim.')
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('ui.pages.gallery.title') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="binduz-er-main-posts-area pt-30 pb-60">
    <div class="container">
        <form method="GET" action="{{ route('galeri') }}" class="row g-2 mb-4 icmi-listing-search" data-auto-search="true">
            <div class="col-md-12">
                <input type="search" name="q" class="form-control" placeholder="{{ __('ui.pages.gallery.search_placeholder') }}" value="{{ $search }}">
            </div>
        </form>

        <div class="row g-4 icmi-listing-grid">
            @forelse($galleries as $gallery)
                <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
                    <div class="binduz-er-main-posts-item w-100 h-100">
                        <div class="binduz-er-trending-news-list-box">
                            <div class="binduz-er-thumb">
                                <a href="{{ route('galeri.show', $gallery->slug) }}">
                                    <img src="{{ $gallery->cover_image ? asset('storage/'.$gallery->cover_image) : asset('assets/images/main-post-thumb-1.jpg') }}" alt="{{ $gallery->translated('title') }}">
                                </a>
                            </div>
                            <div class="binduz-er-content">
                                <div class="binduz-er-meta-item">
                                    <div class="binduz-er-meta-categories"><a href="#">{{ __('ui.pages.gallery.title') }}</a></div>
                                    <div class="binduz-er-meta-date"><span><i class="fal fa-images"></i> {{ __('ui.pages.gallery.photos_count', ['count' => $gallery->items_count]) }}</span></div>
                                </div>
                                <div class="binduz-er-trending-news-list-title">
                                    <h4 class="binduz-er-title"><a href="{{ route('galeri.show', $gallery->slug) }}">{{ $gallery->translated('title') }}</a></h4>
                                    <p>{{ \Illuminate\Support\Str::limit((string) $gallery->translated('description'), 100) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-light border">{{ __('ui.status.no_published_gallery') }}</div></div>
            @endforelse
        </div>

        <div class="mt-3">{{ $galleries->links() }}</div>
    </div>
</section>
@endsection

