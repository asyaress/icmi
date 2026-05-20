@extends('admin.layouts.app')

@section('title', 'Setting Homepage')
@section('page_title', 'Setting Homepage')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.home.update') }}">
            @csrf
            @method('PUT')

            <h6 class="mb-3">Identitas Website</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="site_name" class="form-label">Site Name</label>
                    <input type="text" name="site_name" id="site_name" class="form-control" value="{{ old('site_name', $settings['site_name']) }}">
                </div>
                <div class="col-md-6">
                    <label for="site_tagline" class="form-label">Tagline</label>
                    <input type="text" name="site_tagline" id="site_tagline" class="form-control" value="{{ old('site_tagline', $settings['site_tagline']) }}">
                </div>
                <div class="col-12">
                    <label for="meta_default_title" class="form-label">Meta Title Default</label>
                    <input type="text" name="meta_default_title" id="meta_default_title" class="form-control" value="{{ old('meta_default_title', $settings['meta_default_title']) }}">
                </div>
                <div class="col-12">
                    <label for="meta_default_description" class="form-label">Meta Description Default</label>
                    <textarea name="meta_default_description" id="meta_default_description" rows="3" class="form-control">{{ old('meta_default_description', $settings['meta_default_description']) }}</textarea>
                </div>
                <div class="col-12">
                    <label for="meta_default_keywords" class="form-label">Meta Keywords (opsional)</label>
                    <textarea name="meta_default_keywords" id="meta_default_keywords" rows="2" class="form-control">{{ old('meta_default_keywords', $settings['meta_default_keywords']) }}</textarea>
                </div>
            </div>

            <hr class="my-4">
            <h6 class="mb-3">Section Visibility</h6>
            <div class="row g-2">
                @foreach([
                    'home_show_hero' => 'Hero',
                    'home_show_trending' => 'Trending',
                    'home_show_featured' => 'Featured',
                    'home_show_video' => 'Video Post',
                    'home_show_trending_today' => 'Trending Today',
                    'home_show_main_posts' => 'Main Posts',
                ] as $field => $label)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                value="1"
                                id="{{ $field }}"
                                name="{{ $field }}"
                                @checked(old($field, $settings[$field]) === '1')
                            >
                            <label class="form-check-label" for="{{ $field }}">{{ $label }}</label>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr class="my-4">
            <h6 class="mb-3">Jumlah Konten per Section</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="home_hero_limit">Hero Limit</label>
                    <input type="number" min="1" max="10" class="form-control" id="home_hero_limit" name="home_hero_limit" value="{{ old('home_hero_limit', $settings['home_hero_limit']) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="home_trending_limit">Trending Limit</label>
                    <input type="number" min="3" max="20" class="form-control" id="home_trending_limit" name="home_trending_limit" value="{{ old('home_trending_limit', $settings['home_trending_limit']) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="home_featured_limit">Featured Limit</label>
                    <input type="number" min="2" max="12" class="form-control" id="home_featured_limit" name="home_featured_limit" value="{{ old('home_featured_limit', $settings['home_featured_limit']) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="home_video_limit">Video Limit</label>
                    <input type="number" min="3" max="12" class="form-control" id="home_video_limit" name="home_video_limit" value="{{ old('home_video_limit', $settings['home_video_limit']) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="home_trending_today_limit">Trending Today Limit</label>
                    <input type="number" min="2" max="12" class="form-control" id="home_trending_today_limit" name="home_trending_today_limit" value="{{ old('home_trending_today_limit', $settings['home_trending_today_limit']) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="home_main_posts_limit">Main Posts Limit</label>
                    <input type="number" min="3" max="18" class="form-control" id="home_main_posts_limit" name="home_main_posts_limit" value="{{ old('home_main_posts_limit', $settings['home_main_posts_limit']) }}">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan Setting</button>
            </div>
        </form>
    </div>
</div>
@endsection
