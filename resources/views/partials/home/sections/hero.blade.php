<!--====== BINDUZ HERO PART START ======-->
@php
    $resolvePostUrl = function ($post): string {
        return match ($post->type) {
            \App\Models\Post::TYPE_OPINION => route('opini-tokoh.show', $post->slug),
            \App\Models\Post::TYPE_MEDIA_INFO => route('info-media.show', $post->slug),
            default => route('berita.show', $post->slug),
        };
    };

    $weatherItems = array_values(array_filter($weatherItems ?? [], fn ($item): bool => is_array($item) && isset($item['name'])));
    if ($weatherItems === []) {
        $weatherItems = [[
            'name' => 'Kalimantan Timur',
            'temp_c' => '--',
            'condition' => __('ui.topbar.weather_loading'),
            'icon' => 'fas fa-cloud',
            'updated_at' => now('Asia/Makassar')->format('H:i'),
        ]];
    }
    $firstWeather = $weatherItems[0];
@endphp

@if($heroPosts->isNotEmpty())
    <div class="hero-slide-active">
        @foreach($heroPosts as $heroPost)
            <div class="binduz-er-hero-area d-flex align-items-center">
                <div class="binduz-er-bg-cover" style="background-image:url('{{ $heroPost->featured_image ? asset('storage/'.$heroPost->featured_image) : asset('assets/images/hero-bg-1.jpg') }}')"></div>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-md-8">
                            <div class="binduz-er-hero-news-content">
                                <div class="binduz-er-hero-meta">
                                    <div class="binduz-er-meta-category">
                                        <a href="#">{{ optional($heroPost->category)->translated('name') ?? __('ui.menu.news') }}</a>
                                    </div>
                                    <div class="binduz-er-meta-date">
                                        <span><i class="fal fa-calendar-alt"></i> {{ optional($heroPost->published_at)->format('d M Y') }}</span>
                                    </div>
                                </div>
                                <div class="binduz-er-hero-title">
                                    <h3 class="binduz-er-title"><a href="{{ $resolvePostUrl($heroPost) }}">{{ $heroPost->translated('title') }}</a></h3>
                                </div>
                                <div class="binduz-er-meta-author">
                                    <div class="binduz-er-author">
                                        <img src="{{ asset('assets/images/user-1.png') }}" alt="">
                                        <span>{{ __('ui.common.by') }} <span>{{ $heroPost->author->name ?? 'Admin ICMI' }}</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="icmi-hero-weather-overlay icmi-weather-rotator"
                    data-weather-endpoint="{{ route('weather.kaltim') }}"
                    data-weather-items='@json($weatherItems, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)'
                    data-rotate-ms="4500"
                >
                    <div class="icmi-weather-rotator__item" aria-live="polite" aria-atomic="true">
                        <span class="icmi-weather-rotator__icon" aria-hidden="true">
                            <i class="{{ $firstWeather['icon'] ?? 'fas fa-cloud' }}"></i>
                        </span>
                        <div class="icmi-hero-weather-overlay__content">
                            <span class="icmi-weather-rotator__city">{{ $firstWeather['name'] ?? 'Kalimantan Timur' }}</span>
                            <span class="icmi-weather-rotator__temp">{{ $firstWeather['temp_c'] ?? '--' }}{{ isset($firstWeather['temp_c']) && $firstWeather['temp_c'] !== '--' ? ' &deg;C' : '' }}</span>
                            <span class="icmi-weather-rotator__condition">{{ $firstWeather['condition'] ?? __('ui.topbar.weather_loading') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="hero-portal-area">
        <div class="binduz-er-hero-news-portal hero-portal-active">
            @foreach($heroPosts as $heroPost)
                <div class="binduz-er-news-portal-item">
                    <div class="binduz-er-thumb">
                        <a href="{{ $resolvePostUrl($heroPost) }}">
                            <img src="{{ $heroPost->featured_image ? asset('storage/'.$heroPost->featured_image) : asset('assets/images/news-portal-1.jpg') }}" alt="{{ $heroPost->translated('title') }}">
                        </a>
                    </div>
                    <div class="binduz-er-content">
                        <div class="binduz-er-post-meta-date">
                            <span><i class="fal fa-calendar-alt"></i> {{ optional($heroPost->published_at)->format('d M Y') }}</span>
                        </div>
                        <h4 class="binduz-er-title"><a href="{{ $resolvePostUrl($heroPost) }}">{{ \Illuminate\Support\Str::limit($heroPost->translated('title'), 60) }}</a></h4>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
<!--====== BINDUZ HERO PART ENDS ======-->






