@php
    $topHeaderClass = $topHeaderClass ?? 'binduz-er-top-header-area-4 bg_cover d-none d-lg-block';
    $activeLang = strtolower(app()->getLocale());
@endphp

<div class="{{ $topHeaderClass }}">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="icmi-lang-switch" role="group" aria-label="Language switcher">
                    <a
                        href="{{ request()->fullUrlWithQuery(['lang' => 'id']) }}"
                        class="icmi-lang-switch__item {{ $activeLang === 'id' ? 'is-active' : '' }}"
                        aria-label="Bahasa Indonesia"
                        title="Bahasa Indonesia"
                    >
                        <img class="icmi-lang-switch__flag-img" src="{{ asset('assets/images/flags/id.svg') }}" alt="ID">
                    </a>
                    <a
                        href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}"
                        class="icmi-lang-switch__item {{ $activeLang === 'en' ? 'is-active' : '' }}"
                        aria-label="English"
                        title="English"
                    >
                        <img class="icmi-lang-switch__flag-img" src="{{ asset('assets/images/flags/gb.svg') }}" alt="EN">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
