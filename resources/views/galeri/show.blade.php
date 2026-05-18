@extends('layouts.page')

@section('title', $gallery->title.' - Galeri ICMI Kaltim')
@section('meta_title', $gallery->seo_title ?: $gallery->title.' - Galeri ICMI Kaltim')
@section('meta_description', $gallery->seo_description ?: \Illuminate\Support\Str::limit((string) $gallery->description, 160))
@section('meta_image', $gallery->cover_image ? asset('storage/'.$gallery->cover_image) : asset('logo-icmi.png'))
@section('body_class', 'gray-bg bg-2')
@section('top_header_cover_class', 'bg_cover')

@section('content')
<div class="binduz-er-breadcrumb-area">
    <div class="container"><div class="row"><div class="col-lg-12"><div class="binduz-er-breadcrumb-box"><nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('ui.menu.home') }}</a></li><li class="breadcrumb-item"><a href="{{ route('galeri') }}">{{ __('ui.pages.gallery.title') }}</a></li><li class="breadcrumb-item active" aria-current="page">{{ $gallery->title }}</li></ol></nav></div></div></div></div>
</div>

<section class="binduz-er-blog-details-area pb-60 pt-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="binduz-er-blog-details-box">
                    <div class="binduz-er-meta-item mb-2">
                        <div class="binduz-er-meta-categories"><a href="#">{{ __('ui.pages.gallery.title') }}</a></div>
                        <div class="binduz-er-meta-date"><span><i class="fal fa-calendar-alt"></i> {{ optional($gallery->published_at)->format('d M Y') }}</span></div>
                    </div>
                    <h1 class="binduz-er-title mb-3">{{ $gallery->title }}</h1>
                    @if($gallery->description)
                        <p class="mb-4">{{ $gallery->description }}</p>
                    @endif

                    <div class="row g-3">
                        @forelse($gallery->items as $item)
                            <div class="col-md-6">
                                <div class="border rounded p-2 h-100 bg-white">
                                    <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->caption ?? $gallery->title }}" class="img-fluid rounded mb-2">
                                    @if($item->caption)
                                        <small class="text-muted">{{ $item->caption }}</small>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-light border">{{ __('ui.status.gallery_no_items') }}</div>
                            </div>
                        @endforelse
                    </div>
                </article>
            </div>
            <div class="col-lg-4">
                <div class="binduz-er-sidebar-latest-post">
                    <div class="binduz-er-sidebar-title">
                        <h4 class="binduz-er-title">{{ __('ui.pages.gallery.related') }}</h4>
                    </div>
                    <div class="binduz-er-sidebar-latest-post-box">
                        @forelse($relatedGalleries as $related)
                            <div class="binduz-er-sidebar-latest-post-item">
                                <div class="binduz-er-thumb">
                                    <img src="{{ $related->cover_image ? asset('storage/'.$related->cover_image) : asset('assets/images/latest-post-1.jpg') }}" alt="{{ $related->title }}">
                                </div>
                                <div class="binduz-er-content">
                                    <span><i class="fal fa-calendar-alt"></i> {{ optional($related->published_at)->format('d M Y') }}</span>
                                    <h4 class="binduz-er-title"><a href="{{ route('galeri.show', $related->slug) }}">{{ $related->title }}</a></h4>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">{{ __('ui.status.no_related_gallery') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
