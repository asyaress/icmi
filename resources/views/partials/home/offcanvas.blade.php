<!--====== OFFCANVAS MENU PART START ======-->
@php
    $sekilasPages = collect();
    if (\Illuminate\Support\Facades\Schema::hasTable('profile_pages')) {
        $sekilasPages = \App\Models\ProfilePage::query()
            ->published()
            ->orderBy('menu_order')
            ->with('translations')
            ->get(['slug', 'title', 'menu_label']);
    }
@endphp

    <div class="binduz-er-news-off_canvars_overlay"></div>
    <div class="binduz-er-news-offcanvas_menu">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="binduz-er-news-offcanvas_menu_wrapper">
                        <div class="binduz-er-news-canvas_close">
                            <a href="javascript:void(0)"><i class="fal fa-times"></i></a>
                        </div>
                        <div class="binduz-er-news-header-social">
                            <ul class="text-center">
                                <li><a href="#">Facebook</a></li>
                                <li><a href="#">Twitter</a></li>
                                <li><a href="#">YouTube</a></li>
                            </ul>
                        </div>
                        <div id="menu" class="text-left ">
                            <ul class="binduz-er-news-offcanvas_main_menu">
                                <li><a href="{{ route('home') }}">{{ __('ui.menu.home') }}</a></li>
                                <li><a href="{{ route('sekilas-icmi') }}">{{ __('ui.menu.about') }}</a></li>
                                @foreach($sekilasPages as $sekilasPage)
                                    <li>
                                        <a href="{{ route('sekilas-icmi.show', $sekilasPage->slug) }}">
                                            - {{ $sekilasPage->translated('menu_label') ?: $sekilasPage->translated('title') }}
                                        </a>
                                    </li>
                                @endforeach
                                <li><a href="{{ route('info-media') }}">{{ __('ui.menu.info_media') }}</a></li>
                                <li><a href="{{ route('info-media', ['category' => 'siaran-pers']) }}">- {{ __('ui.submenu.info_media.siaran_pers') }}</a></li>
                                <li><a href="{{ route('info-media', ['category' => 'kabar-icmi']) }}">- {{ __('ui.submenu.info_media.kabar_icmi') }}</a></li>
                                <li><a href="{{ route('berita') }}">{{ __('ui.menu.news') }}</a></li>
                                <li><a href="{{ route('berita', ['category' => \App\Models\Category::ICMI_DAERAH_SLUG]) }}">- {{ __('ui.submenu.news.icmi_daerah') }}</a></li>
                                <li><a href="{{ route('berita', ['category' => \App\Models\Category::ICMI_PUSAT_SLUG]) }}">- {{ __('ui.submenu.news.icmi_pusat') }}</a></li>
                                <li><a href="{{ route('opini-tokoh') }}">{{ __('ui.menu.opinion') }}</a></li>
                                <li><a href="{{ route('opini-tokoh', ['category' => 'opini']) }}">- {{ __('ui.submenu.opinion.opini') }}</a></li>
                                <li><a href="{{ route('opini-tokoh', ['category' => 'tokoh']) }}">- {{ __('ui.submenu.opinion.tokoh') }}</a></li>
                                <li><a href="{{ route('galeri') }}">{{ __('ui.menu.gallery') }}</a></li>
                                <li><a href="{{ route('unduhan') }}">{{ __('ui.menu.letter_downloads') }}</a></li>
                                <li><a href="{{ route('icmi-tv') }}">{{ __('ui.menu.tv') }}</a></li>
                            </ul>
                        </div>
                        <div class="binduz-er-news-offcanvas_footer">
                            <div class="binduz-er-news-logo text-center mb-30 mt-30">
                                <a href="{{ route('home') }}">
                                    <img class="icmi-logo-offcanvas" src="{{ asset('logo-icmi.png') }}" alt="Logo ICMI Kaltim">
                                </a>
                            </div>
                            <p>{{ __('ui.footer.description') }}</p>
                            <ul>
                                <li><i class="fas fa-phone"></i> +62 000 0000 0000</li>
                                <li><i class="fas fa-home"></i> {{ __('ui.footer.address') }}</li>
                                <li><i class="fas fa-envelope"></i> info@icmikaltim.or.id</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--====== OFFCANVAS MENU PART ENDS ======-->

