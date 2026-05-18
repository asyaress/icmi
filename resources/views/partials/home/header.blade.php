<!--====== BINDUZ HEADER PART START ======-->
@php
    $sekilasPages = collect();
    if (\Illuminate\Support\Facades\Schema::hasTable('profile_pages')) {
        $sekilasPages = \App\Models\ProfilePage::query()
            ->published()
            ->orderBy('menu_order')
            ->get(['slug', 'title', 'menu_label']);
    }
@endphp

    <header class="binduz-er-header-area">
        <div class="binduz-er-header-nav">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="navigation">
                            <nav class="navbar navbar-expand-lg">
                                <div class="navbar-brand logo">
                                    <a href="{{ route('home') }}">
                                        <img class="icmi-logo-main" src="{{ asset('logo-icmi.png') }}" alt="Logo ICMI Kaltim">
                                    </a>
                                </div> <!-- logo -->
                                <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                    <ul class="navbar-nav m-auto">
                                        <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('home') }}">{{ __('ui.menu.home') }}</a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('sekilas-icmi*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('sekilas-icmi') }}">{{ __('ui.menu.about') }}</a>
                                            @if($sekilasPages->isNotEmpty())
                                                <ul class="sub-menu">
                                                    @foreach($sekilasPages as $sekilasPage)
                                                        <li>
                                                            <a href="{{ route('sekilas-icmi.show', $sekilasPage->slug) }}">
                                                                {{ $sekilasPage->menu_label ?: \Illuminate\Support\Str::upper($sekilasPage->title) }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('info-media*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('info-media') }}">{{ __('ui.menu.info_media') }}</a>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('info-media', ['category' => 'siaran-pers']) }}">SIARAN PERS</a></li>
                                                <li><a href="{{ route('info-media', ['category' => 'kabar-icmi']) }}">KABAR ICMI</a></li>
                                            </ul>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('berita*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('berita') }}">{{ __('ui.menu.news') }}</a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('opini-tokoh*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('opini-tokoh') }}">{{ __('ui.menu.opinion') }}</a>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('opini-tokoh', ['category' => 'opini']) }}">OPINI</a></li>
                                                <li><a href="{{ route('opini-tokoh', ['category' => 'tokoh']) }}">TOKOH</a></li>
                                            </ul>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('galeri*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('galeri') }}">{{ __('ui.menu.gallery') }}</a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('icmi-tv*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('icmi-tv') }}">{{ __('ui.menu.tv') }}</a>
                                        </li>
                                    </ul>
                                </div> <!-- navbar collapse -->
                                <div class="binduz-er-navbar-btn d-flex">
                                    <div class="binduz-er-widget d-flex">
                                        <a class="binduz-er-news-search-open" href="#"><i class="far fa-search"></i></a>
                                    </div>
                                    <span class="binduz-er-toggle-btn binduz-er-news-canvas_open d-block d-lg-none">
                                        <i class="fal fa-bars"></i>
                                    </span>
                                </div>
                            </nav>
                        </div> <!-- navigation -->
                    </div>
                </div> <!-- row -->
            </div>
        </div>
    </header>
