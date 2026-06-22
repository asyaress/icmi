<div id="icmi-page-loader" class="icmi-page-loader" data-icmi-loader role="status" aria-live="polite" aria-label="{{ __('ui.loading.label') }}">
    <div class="icmi-page-loader__visual" aria-hidden="true">
        <span class="icmi-page-loader__ring icmi-page-loader__ring--outer"></span>
        <span class="icmi-page-loader__ring icmi-page-loader__ring--inner"></span>
        <div class="icmi-page-loader__logo-wrap">
            <img src="{{ asset('logo-icmi.png') }}" alt="" class="icmi-page-loader__logo">
        </div>
    </div>
    <p class="icmi-page-loader__label">{{ __('ui.loading.label') }}</p>
    <div class="icmi-page-loader__dots" aria-hidden="true"><span></span><span></span><span></span></div>
</div>
<noscript><style>.icmi-page-loader { display: none !important; }</style></noscript>
