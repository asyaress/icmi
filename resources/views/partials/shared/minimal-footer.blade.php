<footer class="icmi-minimal-footer">
    <div class="container">
        <div class="icmi-minimal-footer__top">
            <div class="row g-3 align-items-center">
                <div class="col-lg-7">
                    <a class="d-inline-flex align-items-center mb-2" href="{{ route('home') }}">
                        <img class="icmi-logo-footer" src="{{ asset('logo-icmi.png') }}" alt="Logo ICMI Kaltim">
                    </a>
                    <h4 class="icmi-minimal-footer__brand">{{ __('ui.meta.site_name') }}</h4>
                    <p class="icmi-minimal-footer__desc">{{ __('ui.footer.description') }}</p>
                </div>
                <div class="col-lg-5">
                    <nav class="icmi-minimal-footer__nav">
                        <a href="{{ route('home') }}">{{ __('ui.menu.home') }}</a>
                        <a href="{{ route('sekilas-icmi') }}">{{ __('ui.menu.about') }}</a>
                        <a href="{{ route('info-media') }}">{{ __('ui.menu.info_media') }}</a>
                        <a href="{{ route('berita') }}">{{ __('ui.menu.news') }}</a>
                        <a href="{{ route('opini-tokoh') }}">{{ __('ui.menu.opinion') }}</a>
                        <a href="{{ route('galeri') }}">{{ __('ui.menu.gallery') }}</a>
                        <a href="{{ route('unduhan') }}">{{ __('ui.menu.letter_downloads') }}</a>
                        <a href="{{ route('icmi-tv') }}">{{ __('ui.menu.tv') }}</a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="icmi-minimal-footer__bottom">
            <p>{{ __('ui.footer.copyright', ['year' => now()->year]) }}</p>
        </div>
    </div>
</footer>
