<!--====== BINDUZ MAIN POSTS PART START ======-->
@php
    $latestSidebarPosts = $mainPosts->slice(0, 4);
@endphp
<section class="binduz-er-main-posts-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="binduz-er-video-post-topbar">
                    <div class="binduz-er-video-post-title">
                        <h3 class="binduz-er-title">{{ __('ui.sections.main_posts') }}</h3>
                    </div>
                </div>
                <div class="row g-4 icmi-main-post-grid">
                    @forelse($mainPosts as $post)
                        <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
                            <div class="binduz-er-main-posts-item w-100 h-100">
                                <div class="binduz-er-trending-news-list-box">
                                    <div class="binduz-er-thumb">
                                        <img src="{{ $post->featured_image ? asset('storage/'.$post->featured_image) : asset('assets/images/main-post-thumb-1.jpg') }}" alt="{{ $post->title }}">
                                    </div>
                                    <div class="binduz-er-content">
                                        <div class="binduz-er-meta-item">
                                            <div class="binduz-er-meta-categories"><a href="#">{{ $post->category->name ?? __('ui.menu.news') }}</a></div>
                                            <div class="binduz-er-meta-date"><span><i class="fal fa-calendar-alt"></i> {{ optional($post->published_at)->format('d M Y') }}</span></div>
                                        </div>
                                        <div class="binduz-er-trending-news-list-title">
                                            <h4 class="binduz-er-title"><a href="{{ route('berita.show', $post->slug) }}">{{ \Illuminate\Support\Str::limit($post->title, 58) }}</a></h4>
                                            <p>{{ \Illuminate\Support\Str::limit((string) $post->excerpt, 120) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="alert alert-light border">{{ __('ui.status.no_main_post') }}</div></div>
                    @endforelse
                </div>
                <div class="binduz-er-add pt-10">
                    <img src="{{ asset('assets/images/space-thumb.jpg') }}" alt="">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="binduz-er-sidebar-latest-post">
                    <div class="binduz-er-sidebar-title">
                        <h4 class="binduz-er-title">{{ __('ui.common.latest_post') }}</h4>
                    </div>
                    <div class="binduz-er-sidebar-latest-post-box icmi-latest-post-list">
                        @forelse($latestSidebarPosts as $latest)
                            <div class="binduz-er-sidebar-latest-post-item">
                                <div class="binduz-er-thumb">
                                    <img src="{{ $latest->featured_image ? asset('storage/'.$latest->featured_image) : asset('assets/images/latest-post-1.jpg') }}" alt="latest">
                                </div>
                                <div class="binduz-er-content">
                                    <span><i class="fal fa-calendar-alt"></i> {{ optional($latest->published_at)->format('d M Y') }}</span>
                                    <h4 class="binduz-er-title"><a href="{{ route('berita.show', $latest->slug) }}">{{ \Illuminate\Support\Str::limit($latest->title, 46) }}</a></h4>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">{{ __('ui.status.no_latest_post') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--====== BINDUZ MAIN POSTS PART ENDS ======-->
