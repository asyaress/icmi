(function () {
    'use strict';

    var loader = document.querySelector('[data-icmi-loader]');
    if (!loader) {
        return;
    }

    var startedAt = window.performance && performance.now ? performance.now() : 0;
    var hidden = false;
    var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.body.classList.add('icmi-is-loading');

    function hideLoader() {
        if (hidden) {
            return;
        }

        hidden = true;
        var elapsed = window.performance && performance.now ? performance.now() - startedAt : 450;
        var minimumVisible = reducedMotion ? 0 : 420;
        var delay = Math.max(0, minimumVisible - elapsed);

        window.setTimeout(function () {
            loader.classList.add('is-leaving');
            document.body.classList.remove('icmi-is-loading');

            window.setTimeout(function () {
                loader.hidden = true;
            }, reducedMotion ? 0 : 420);
        }, delay);
    }

    if (document.readyState === 'complete') {
        hideLoader();
    } else {
        window.addEventListener('load', hideLoader, { once: true });
    }

    window.setTimeout(hideLoader, 4000);
}());
