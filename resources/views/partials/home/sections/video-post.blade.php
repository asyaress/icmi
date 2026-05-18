<!--====== BINDUZ VIDEO POST PART START ======-->
@php
    $mainVideo = $videoPosts->first();
    $leftVideos = $videoPosts->slice(1, 2);
    $rightVideos = $videoPosts->slice(3, 2);
@endphp
<section class="binduz-er-video-post-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="binduz-er-video-post-topbar">
                    <div class="binduz-er-video-post-title">
                        <h3 class="binduz-er-title">{{ __('ui.sections.video_post') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-flex justify-content-lg-end justify-content-start align-items-center">
                <a href="{{ route('icmi-tv') }}" class="btn btn-sm btn-outline-dark">{{ __('ui.sections.view_all_videos') }}</a>
            </div>
        </div>

        <div class="row icmi-video-post-grid">
            <div class="col-lg-3 col-md-6 order-lg-1 order-1">
                <div class="binduz-er-video-post-item icmi-video-column">
                    @foreach($leftVideos as $video)
                        <article class="binduz-er-trending-news-list-box icmi-video-card">
                            <div class="binduz-er-thumb">
                                <img src="{{ $video->thumbnail ? asset('storage/'.$video->thumbnail) : 'https://img.youtube.com/vi/'.$video->youtube_id.'/hqdefault.jpg' }}" alt="{{ $video->title }}">
                                <div class="binduz-er-play"><a class="binduz-er-video-popup" href="{{ route('icmi-tv.show', $video->slug) }}"><i class="fas fa-play"></i></a></div>
                            </div>
                            <div class="binduz-er-content">
                                <div class="binduz-er-meta-item">
                                    <div class="binduz-er-meta-categories"><a href="{{ route('icmi-tv') }}">{{ __('ui.menu.tv') }}</a></div>
                                    <div class="binduz-er-meta-date"><span><i class="fal fa-calendar-alt"></i> {{ optional($video->published_at)?->translatedFormat('d M Y') }}</span></div>
                                </div>
                                <div class="binduz-er-trending-news-list-title">
                                    <h4 class="binduz-er-title"><a href="{{ route('icmi-tv.show', $video->slug) }}">{{ \Illuminate\Support\Str::limit($video->title, 60) }}</a></h4>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-6 order-lg-2 order-3">
                @if($mainVideo)
                    <div class="binduz-er-video-post-item icmi-video-column">
                        <article class="binduz-er-trending-news-list-box main-item icmi-video-card icmi-video-card--main">
                            <div class="binduz-er-thumb">
                                <img src="{{ $mainVideo->thumbnail ? asset('storage/'.$mainVideo->thumbnail) : 'https://img.youtube.com/vi/'.$mainVideo->youtube_id.'/hqdefault.jpg' }}" alt="{{ $mainVideo->title }}">
                                <div class="binduz-er-play"><a class="binduz-er-video-popup" href="{{ route('icmi-tv.show', $mainVideo->slug) }}"><i class="fas fa-play"></i></a></div>
                            </div>
                            <div class="binduz-er-content">
                                <div class="binduz-er-meta-item">
                                    <div class="binduz-er-meta-categories"><a href="{{ route('icmi-tv') }}">{{ __('ui.menu.tv') }}</a></div>
                                    <div class="binduz-er-meta-date"><span><i class="fal fa-calendar-alt"></i> {{ optional($mainVideo->published_at)?->translatedFormat('d M Y') }}</span></div>
                                </div>
                                <div class="binduz-er-trending-news-list-title">
                                    <h4 class="binduz-er-title"><a href="{{ route('icmi-tv.show', $mainVideo->slug) }}">{{ $mainVideo->title }}</a></h4>
                                </div>
                            </div>
                        </article>
                    </div>
                @endif
            </div>

            <div class="col-lg-3 col-md-6 order-lg-3 order-2">
                <div class="binduz-er-video-post-item icmi-video-column">
                    @foreach($rightVideos as $video)
                        <article class="binduz-er-trending-news-list-box icmi-video-card">
                            <div class="binduz-er-thumb">
                                <img src="{{ $video->thumbnail ? asset('storage/'.$video->thumbnail) : 'https://img.youtube.com/vi/'.$video->youtube_id.'/hqdefault.jpg' }}" alt="{{ $video->title }}">
                                <div class="binduz-er-play"><a class="binduz-er-video-popup" href="{{ route('icmi-tv.show', $video->slug) }}"><i class="fas fa-play"></i></a></div>
                            </div>
                            <div class="binduz-er-content">
                                <div class="binduz-er-meta-item">
                                    <div class="binduz-er-meta-categories"><a href="{{ route('icmi-tv') }}">{{ __('ui.menu.tv') }}</a></div>
                                    <div class="binduz-er-meta-date"><span><i class="fal fa-calendar-alt"></i> {{ optional($video->published_at)?->translatedFormat('d M Y') }}</span></div>
                                </div>
                                <div class="binduz-er-trending-news-list-title">
                                    <h4 class="binduz-er-title"><a href="{{ route('icmi-tv.show', $video->slug) }}">{{ \Illuminate\Support\Str::limit($video->title, 60) }}</a></h4>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!--====== BINDUZ VIDEO POST PART ENDS ======-->
