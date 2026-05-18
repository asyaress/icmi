<!--====== BINDUZ TRENDING PART START ======-->
@php
    $resolvePostUrl = function ($post): string {
        return match ($post->type) {
            \App\Models\Post::TYPE_OPINION => route('opini-tokoh.show', $post->slug),
            \App\Models\Post::TYPE_MEDIA_INFO => route('info-media.show', $post->slug),
            default => route('berita.show', $post->slug),
        };
    };
    $headline = $trendingPosts->first();
    $sidePosts = $trendingPosts->slice(1, 3);
@endphp
<section class="binduz-er-trending-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <div class="binduz-er-trending-news-topbar d-block d-md-flex justify-content-between align-items-center">
                    <div class="binduz-er-trending-box">
                        <div class="binduz-er-title">
                            <h3 class="binduz-er-title">{{ __('ui.sections.trending_news') }}</h3>
                        </div>
                    </div>
                </div>

                @if($headline)
                    <div class="row mt-30">
                        <div class="col-lg-7 col-md-6">
                            <div class="binduz-er-trending-box icmi-trending-headline-box">
                                <div class="binduz-er-trending-news-item icmi-trending-headline">
                                    <img src="{{ $headline->featured_image ? asset('storage/'.$headline->featured_image) : asset('assets/images/trending-thumb.png') }}" alt="{{ $headline->title }}">
                                    <div class="binduz-er-trending-news-overlay">
                                        <div class="binduz-er-trending-news-meta">
                                            <div class="binduz-er-meta-categories"><a href="#">{{ $headline->category->name ?? __('ui.menu.news') }}</a></div>
                                            <div class="binduz-er-meta-date"><span><i class="fal fa-calendar-alt"></i> {{ optional($headline->published_at)?->translatedFormat('d M Y') }}</span></div>
                                            <div class="binduz-er-trending-news-title">
                                                <h3 class="binduz-er-title"><a href="{{ $resolvePostUrl($headline) }}">{{ $headline->title }}</a></h3>
                                            </div>
                                        </div>
                                        <div class="binduz-er-news-share"><a href="{{ $resolvePostUrl($headline) }}"><i class="fal fa-share"></i></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6">
                            <div class="icmi-trending-side-list">
                                @foreach($sidePosts as $post)
                                    <article class="icmi-trending-side-card">
                                        <a class="icmi-trending-side-card__thumb" href="{{ $resolvePostUrl($post) }}">
                                            <img src="{{ $post->featured_image ? asset('storage/'.$post->featured_image) : asset('assets/images/trending-news-list-thumb-1.jpg') }}" alt="{{ $post->title }}">
                                        </a>
                                        <div class="icmi-trending-side-card__content">
                                            <div class="binduz-er-meta-item icmi-trending-side-card__meta">
                                                <div class="binduz-er-meta-categories"><a href="#">{{ $post->category->name ?? __('ui.menu.news') }}</a></div>
                                                <div class="binduz-er-meta-date"><span><i class="fal fa-calendar-alt"></i> {{ optional($post->published_at)?->translatedFormat('d M Y') }}</span></div>
                                            </div>
                                            <h4 class="binduz-er-title icmi-trending-side-card__title">
                                                <a href="{{ $resolvePostUrl($post) }}">{{ \Illuminate\Support\Str::limit($post->title, 82) }}</a>
                                            </h4>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-light border mt-4">{{ __('ui.status.no_trending') }}</div>
                @endif
            </div>

            <div class="col-lg-3 col-md-12">
                <div class="binduz-er-sidebar-categories">
                    <div class="binduz-er-sidebar-title">
                        <h4 class="binduz-er-title">{{ __('ui.sections.categories') }}</h4>
                    </div>
                    <div class="binduz-er-categories-list">
                        @forelse($categories as $category)
                            <div class="binduz-er-item">
                                <a href="{{ route('berita', ['category' => $category->slug]) }}">
                                    <span>{{ $category->name }}</span>
                                    <span class="binduz-er-number">{{ $category->published_posts_count }}</span>
                                </a>
                            </div>
                        @empty
                            <div class="binduz-er-item"><a href="#"><span>{{ __('ui.common.no_data') }}</span><span class="binduz-er-number">0</span></a></div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--====== BINDUZ TRENDING PART ENDS ======-->
