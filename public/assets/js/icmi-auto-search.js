(function () {
    'use strict';

    function debounce(fn, wait) {
        var timer = null;
        return function () {
            var args = arguments;
            var ctx = this;
            window.clearTimeout(timer);
            timer = window.setTimeout(function () {
                fn.apply(ctx, args);
            }, wait);
        };
    }

    function initAutoSearch(form) {
        var qInput = form.querySelector('input[type="search"], input[name="q"]');
        var select = form.querySelector('select[name="category"], select[name="type"], select[name="topic"]');

        var initial = new URLSearchParams(new FormData(form)).toString();

        function submitIfChanged() {
            var now = new URLSearchParams(new FormData(form)).toString();
            if (now === initial) {
                return;
            }
            form.submit();
        }

        if (qInput) {
            qInput.addEventListener('input', debounce(submitIfChanged, 500));
        }

        if (select) {
            select.addEventListener('change', submitIfChanged);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var forms = document.querySelectorAll('form[data-auto-search="true"]');
        if (!forms.length) {
            return;
        }

        forms.forEach(initAutoSearch);
    });
})();
