<!--====== BINDUZ FEATURED PART START ======-->
@php
    $mainFeatured = $featuredPosts->first();
    $otherFeatured = $featuredPosts->slice(1);
@endphp
<section class="binduz-er-featured-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="binduz-er-featured-slider mt-30">
                    <div class="binduz-er-featured-title">
                        <h3 class="binduz-er-title">{{ __('ui.sections.featured') }}</h3>
                    </div>
                    <div class="binduz-er-featured-slider-item">
                        @forelse($otherFeatured as $post)
                            <div class="binduz-er-trending-news-list-box">
                                <div class="binduz-er-thumb">
                                    <img src="{{ $post->featured_image ? asset('storage/'.$post->featured_image) : asset('assets/images/feature-news-thuimb.jpg') }}" alt="{{ $post->title }}">
                                </div>
                                <div class="binduz-er-content">
                                    <div class="binduz-er-meta-item">
                                        <div class="binduz-er-meta-categories"><a href="#">{{ $post->category->name ?? __('ui.menu.news') }}</a></div>
                                        <div class="binduz-er-meta-date"><span><i class="fal fa-calendar-alt"></i> {{ optional($post->published_at)?->translatedFormat('d M Y') }}</span></div>
                                    </div>
                                    <div class="binduz-er-trending-news-list-title">
                                        <h4 class="binduz-er-title"><a href="{{ route('berita.show', $post->slug) }}">{{ \Illuminate\Support\Str::limit($post->title, 65) }}</a></h4>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="binduz-er-trending-news-list-box"><div class="binduz-er-content"><p>{{ __('ui.status.no_featured') }}</p></div></div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-6">
                @if($mainFeatured)
                    <div class="binduz-er-trending-news-item mb-30">
                        <img src="{{ $mainFeatured->featured_image ? asset('storage/'.$mainFeatured->featured_image) : asset('assets/images/featured-trending-thumb-1.jpg') }}" alt="{{ $mainFeatured->title }}">
                        <div class="binduz-er-trending-news-overlay">
                            <div class="binduz-er-trending-news-meta">
                                <div class="binduz-er-meta-categories"><a href="#">{{ $mainFeatured->category->name ?? __('ui.menu.news') }}</a></div>
                                <div class="binduz-er-meta-date"><span><i class="fal fa-calendar-alt"></i> {{ optional($mainFeatured->published_at)?->translatedFormat('d M Y') }}</span></div>
                                <div class="binduz-er-trending-news-title">
                                    <h3 class="binduz-er-title"><a href="{{ route('berita.show', $mainFeatured->slug) }}">{{ $mainFeatured->title }}</a></h3>
                                </div>
                            </div>
                            <div class="binduz-er-news-share"><a href="{{ route('berita.show', $mainFeatured->slug) }}"><i class="fal fa-share"></i></a></div>
                        </div>
                    </div>
                @endif

                @if($latestGalleries->isNotEmpty())
                    <div class="binduz-er-trending-news-item mb-30">
                        <img src="{{ $latestGalleries->first()->cover_image ? asset('storage/'.$latestGalleries->first()->cover_image) : asset('assets/images/featured-trending-thumb-2.jpg') }}" alt="{{ $latestGalleries->first()->title }}">
                        <div class="binduz-er-trending-news-overlay">
                            <div class="binduz-er-trending-news-meta">
                                <div class="binduz-er-meta-categories"><a href="{{ route('galeri') }}">{{ __('ui.menu.gallery') }}</a></div>
                                <div class="binduz-er-meta-date"><span><i class="fal fa-images"></i> {{ __('ui.pages.gallery.photos_count', ['count' => $latestGalleries->first()->items_count]) }}</span></div>
                                <div class="binduz-er-trending-news-title">
                                    <h3 class="binduz-er-title"><a href="{{ route('galeri.show', $latestGalleries->first()->slug) }}">{{ $latestGalleries->first()->title }}</a></h3>
                                </div>
                            </div>
                            <div class="binduz-er-news-share"><a href="{{ route('galeri.show', $latestGalleries->first()->slug) }}"><i class="fal fa-share"></i></a></div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-3">
                <div class="binduz-er-sidebar-social">
                    <div class="binduz-er-sidebar-title">
                        <h4 class="binduz-er-title">{{ __('ui.sections.quick_links') }}</h4>
                    </div>
                    <div class="binduz-er-social-list">
                        <div class="binduz-er-list">
                            <a href="{{ route('berita') }}"><span><i class="fas fa-newspaper"></i> {{ __('ui.menu.news') }}</span><span>{{ __('ui.common.open') }}</span></a>
                            <a href="{{ route('opini-tokoh') }}"><span><i class="fas fa-user-edit"></i> {{ __('ui.menu.opinion') }}</span><span>{{ __('ui.common.open') }}</span></a>
                            <a href="{{ route('info-media') }}"><span><i class="fas fa-bullhorn"></i> {{ __('ui.menu.info_media') }}</span><span>{{ __('ui.common.open') }}</span></a>
                            <a href="{{ route('icmi-tv') }}"><span><i class="fab fa-youtube"></i> {{ __('ui.menu.tv') }}</span><span>{{ __('ui.common.open') }}</span></a>
                        </div>
                    </div>
                    <div class="binduz-er-sidebar-add mt-20">
                        <h3 class="binduz-er-title">{{ __('ui.sections.activity_docs') }} <span>{{ __('ui.sections.activity_docs_sub') }}</span></h3>
                        <a class="binduz-er-main-btn" href="{{ route('galeri') }}">{{ __('ui.sections.view_gallery') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--====== BINDUZ FEATURED PART ENDS ======-->
