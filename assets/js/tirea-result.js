/* ============================================
   TIREA — Result : slider avant/après
   Gestion drag (souris + tactile) + clavier + hint visuel
   ============================================ */

(function () {
    'use strict';

    function initSlider(slider) {
        if (slider.dataset.tireaInit === '1') return;
        slider.dataset.tireaInit = '1';

        var beforeWrap = slider.querySelector('.tirea-result-before-wrap');
        var handle     = slider.querySelector('.tirea-result-handle');
        if (!beforeWrap || !handle) return;

        var isDragging = false;

        function setPosition(percent) {
            percent = Math.max(0, Math.min(100, percent));
            beforeWrap.style.width = percent + '%';
            handle.style.left      = percent + '%';
            slider.setAttribute('aria-valuenow', Math.round(percent));
        }

        function getPercentFromEvent(clientX) {
            var rect = slider.getBoundingClientRect();
            return ((clientX - rect.left) / rect.width) * 100;
        }

        // ----- Souris -----
        slider.addEventListener('mousedown', function (e) {
            isDragging = true;
            setPosition(getPercentFromEvent(e.clientX));
            e.preventDefault();
        });
        document.addEventListener('mousemove', function (e) {
            if (!isDragging) return;
            setPosition(getPercentFromEvent(e.clientX));
        });
        document.addEventListener('mouseup', function () {
            isDragging = false;
        });

        // ----- Tactile -----
        slider.addEventListener('touchstart', function (e) {
            isDragging = true;
            setPosition(getPercentFromEvent(e.touches[0].clientX));
        }, { passive: true });
        slider.addEventListener('touchmove', function (e) {
            if (!isDragging) return;
            setPosition(getPercentFromEvent(e.touches[0].clientX));
        }, { passive: true });
        slider.addEventListener('touchend', function () {
            isDragging = false;
        });

        // ----- Clavier -----
        slider.addEventListener('keydown', function (e) {
            var current = parseFloat(slider.getAttribute('aria-valuenow')) || 50;
            var step = e.shiftKey ? 10 : 2;
            if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') {
                setPosition(current - step);
                e.preventDefault();
            } else if (e.key === 'ArrowRight' || e.key === 'ArrowUp') {
                setPosition(current + step);
                e.preventDefault();
            } else if (e.key === 'Home') {
                setPosition(0);
                e.preventDefault();
            } else if (e.key === 'End') {
                setPosition(100);
                e.preventDefault();
            }
        });

        // ----- Hint visuel quand le slider entre dans le viewport -----
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        slider.classList.add('is-hinting');
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.4 });
            observer.observe(slider);
        }
    }

    function init() {
        var sliders = document.querySelectorAll('.tirea-result-slider');
        sliders.forEach(initSlider);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();