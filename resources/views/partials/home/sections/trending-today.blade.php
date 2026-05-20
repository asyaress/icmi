<!--====== BINDUZ TRENDING TODAY PART START ======-->
@php
    $resolvePostUrl = function ($post): string {
        return match ($post->type) {
            \App\Models\Post::TYPE_OPINION => route('opini-tokoh.show', $post->slug),
            \App\Models\Post::TYPE_MEDIA_INFO => route('info-media.show', $post->slug),
            default => route('berita.show', $post->slug),
        };
    };
    $displayPosts = $trendingTodayPosts->take(4);
@endphp
<section class="binduz-er-trending-today-area">
    <div class="binduz-er-bg-cover"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="binduz-er-trending-today-topbar">
                    <div class="binduz-er-trending-today-title">
                        <div class="icmi-section-head">
                            <h3 class="binduz-er-title">{{ __('ui.sections.trending_today') }}</h3>
                            <span class="icmi-section-head__line" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row icmi-trending-today-grid">
            @forelse($displayPosts as $post)
                <div class="col-lg-3 col-md-6">
                    <div class="binduz-er-trending-today-item h-100">
                        <div class="binduz-er-trending-news-list-box">
                            <div class="binduz-er-thumb">
                                <img src="{{ $post->featured_image ? asset('storage/'.$post->featured_image) : asset('assets/images/trending-today-thumb-1.png') }}" alt="{{ $post->translated('title') }}">
                            </div>
                            <div class="binduz-er-content">
                                <div class="binduz-er-meta-item">
                                    <div class="binduz-er-meta-categories"><a href="#">{{ optional($post->category)->translated('name') ?? __('ui.menu.news') }}</a></div>
                                    <div class="binduz-er-meta-date"><span><i class="fal fa-calendar-alt"></i> {{ optional($post->published_at)?->translatedFormat('d M Y') }}</span></div>
                                </div>
                                <div class="binduz-er-trending-news-list-title">
                                    <h4 class="binduz-er-title"><a href="{{ $resolvePostUrl($post) }}">{{ \Illuminate\Support\Str::limit($post->translated('title'), 62) }}</a></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-light border">{{ __('ui.status.no_trending_today') }}</div></div>
            @endforelse
        </div>
    </div>
</section>
<!--====== BINDUZ TRENDING TODAY PART ENDS ======-->




