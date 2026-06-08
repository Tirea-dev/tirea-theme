(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var widget = document.querySelector('.tirea-rating[data-tirea-rating="empty"]');
    if (!widget) return;

    var btn = widget.querySelector('.tirea-rating-help');
    var bubble = widget.querySelector('.tirea-rating-bubble');
    var stars = widget.querySelector('.tirea-rating-stars');
    var bubbleLink = widget.querySelector('.tirea-rating-bubble-link');

    if (!btn || !bubble) return;

    var canHover = window.matchMedia('(hover: hover)').matches;
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var pinned = false;

    function openBubble(pin) {
      bubble.hidden = false;
      if (pin) {
        pinned = true;
        widget.classList.add('is-pinned');
        btn.setAttribute('aria-expanded', 'true');
      }
    }

    function closeBubble() {
      pinned = false;
      widget.classList.remove('is-pinned');
      btn.setAttribute('aria-expanded', 'false');
      bubble.hidden = true;
    }

    function scrollToTarget(selector) {
      if (!selector) return;
      var target = document.querySelector(selector);
      if (!target) return;
      var sticky = document.getElementById('tireaStickyHeader');
      var offset = (sticky ? sticky.offsetHeight : 0) + 16;
      target.style.scrollMarginTop = offset + 'px';
      target.scrollIntoView({
        behavior: reduceMotion ? 'auto' : 'smooth',
        block: 'start'
      });
    }

    if (canHover) {
      btn.addEventListener('mouseenter', function () {
        if (!pinned) openBubble(false);
      });
      btn.addEventListener('mouseleave', function () {
        if (!pinned) closeBubble();
      });
    }

    btn.addEventListener('click', function (e) {
      e.preventDefault();
      if (pinned) {
        closeBubble();
      } else {
        openBubble(true);
      }
    });

    if (bubbleLink) {
      bubbleLink.addEventListener('click', function (e) {
        e.preventDefault();
        var selector = bubbleLink.getAttribute('data-tirea-scroll');
        closeBubble();
        scrollToTarget(selector);
      });
    }

    if (stars) {
      stars.addEventListener('click', function () {
        scrollToTarget(stars.getAttribute('data-tirea-scroll'));
      });
    }

    document.addEventListener('click', function (e) {
      if (pinned && !widget.contains(e.target)) {
        closeBubble();
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !bubble.hidden) {
        closeBubble();
        btn.focus();
      }
    });

    window.addEventListener('scroll', function () {
      if (!bubble.hidden) {
        closeBubble();
      }
    }, { passive: true });
  });
})();