(function () {
    'use strict';

    function normalizeItem(item) {
        if (!item || typeof item !== 'object') {
            return null;
        }

        var name = String(item.name || '').trim();
        if (!name) {
            return null;
        }

        var temp = String(item.temp_c ?? '--').trim();
        var condition = String(item.condition || '').trim();
        var icon = String(item.icon || 'fas fa-cloud').trim();

        return {
            name: name,
            temp_c: temp,
            condition: condition || 'Weather update',
            icon: icon || 'fas fa-cloud'
        };
    }

    function initWeatherRotator(root) {
        var endpoint = root.getAttribute('data-weather-endpoint');
        var rotateMs = Number(root.getAttribute('data-rotate-ms') || 4500);
        var itemEl = root.querySelector('.icmi-weather-rotator__item');

        if (!itemEl) {
            return;
        }

        var iconEl = itemEl.querySelector('.icmi-weather-rotator__icon i');
        var cityEl = itemEl.querySelector('.icmi-weather-rotator__city');
        var tempEl = itemEl.querySelector('.icmi-weather-rotator__temp');
        var condEl = itemEl.querySelector('.icmi-weather-rotator__condition');

        var items = [];
        try {
            var parsed = JSON.parse(root.getAttribute('data-weather-items') || '[]');
            if (Array.isArray(parsed)) {
                items = parsed.map(normalizeItem).filter(Boolean);
            }
        } catch (e) {
            items = [];
        }

        if (!items.length) {
            return;
        }

        var pointer = 0;

        function render(item) {
            if (!item) {
                return;
            }

            root.classList.add('is-animating');
            window.setTimeout(function () {
                if (iconEl) {
                    iconEl.className = item.icon;
                }

                if (cityEl) {
                    cityEl.textContent = item.name;
                }

                if (tempEl) {
                    tempEl.textContent = item.temp_c !== '--' ? item.temp_c + '\u00B0C' : '--';
                }

                if (condEl) {
                    condEl.textContent = item.condition;
                }

                root.classList.remove('is-animating');
            }, 150);
        }

        window.setInterval(function () {
            if (items.length < 2) {
                return;
            }

            pointer = (pointer + 1) % items.length;
            render(items[pointer]);
        }, Math.max(rotateMs, 2500));

        if (endpoint) {
            window.setTimeout(function () {
                fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(function (response) {
                        return response.ok ? response.json() : null;
                    })
                    .then(function (payload) {
                        if (!payload || !Array.isArray(payload.items)) {
                            return;
                        }

                        var fresh = payload.items.map(normalizeItem).filter(Boolean);
                        if (!fresh.length) {
                            return;
                        }

                        items = fresh;
                        pointer = 0;
                        render(items[pointer]);
                    })
                    .catch(function () {
                        // Silent fallback to server-rendered cached weather.
                    });
            }, 600);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var roots = document.querySelectorAll('.icmi-weather-rotator');
        if (!roots.length) {
            return;
        }

        roots.forEach(initWeatherRotator);
    });
})();
