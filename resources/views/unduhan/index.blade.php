@extends('layouts.page')

@section('title', __('ui.pages.downloads.title').' - ICMI Kaltim')
@section('meta_title', __('ui.pages.downloads.title').' - ICMI Kaltim')
@section('meta_description', __('ui.pages.downloads.description'))
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('ui.pages.downloads.title') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="binduz-er-main-posts-area pt-30 pb-60">
    <div class="container">
        <form method="GET" action="{{ route('unduhan') }}" class="row g-2 mb-4 icmi-listing-search" data-auto-search="true">
            <div class="col-md-12">
                <input type="search" name="q" class="form-control" placeholder="{{ __('ui.pages.downloads.search_placeholder') }}" value="{{ $search }}">
            </div>
        </form>

        <div class="row g-4">
            @forelse($downloads as $download)
                <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
                    <article class="binduz-er-main-posts-item w-100 h-100">
                        <div class="binduz-er-content p-4 d-flex flex-column h-100">
                            <div class="icmi-download-preview mb-3">
                                <iframe
                                    src="{{ route('unduhan.preview', $download->slug) }}#toolbar=0&navpanes=0&scrollbar=0"
                                    title="Preview {{ $download->translated('title') }}"
                                    loading="lazy"
                                ></iframe>
                            </div>

                            <div class="binduz-er-meta-item mb-2">
                                <div class="binduz-er-meta-categories">
                                    <a href="#">{{ __('ui.pages.downloads.file_label') }}</a>
                                </div>
                                <div class="binduz-er-meta-date">
                                    <span><i class="fal fa-calendar-alt"></i> {{ optional($download->published_at)->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>
                            <h4 class="binduz-er-title mb-2">{{ $download->translated('title') }}</h4>
                            @if($download->translated('description'))
                                <p class="mb-3">{{ \Illuminate\Support\Str::limit(strip_tags((string) $download->translated('description')), 140) }}</p>
                            @endif

                            <div class="small text-muted mb-3 mt-auto">
                                {{ $download->original_name }} · {{ number_format(((int) $download->file_size) / 1024, 1) }} KB
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary" target="_blank" href="{{ route('unduhan.preview', $download->slug) }}">
                                    <i class="fal fa-eye"></i> {{ __('ui.pages.downloads.preview_button') }}
                                </a>
                                <a class="btn btn-sm btn-outline-dark" href="{{ route('unduhan.download', $download->slug) }}">
                                    <i class="fal fa-download"></i> {{ __('ui.pages.downloads.download_button') }}
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light border">{{ __('ui.pages.downloads.empty') }}</div>
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $downloads->links() }}
        </div>
    </div>
</section>
@endsection
