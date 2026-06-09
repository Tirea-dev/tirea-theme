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
// ===== AJOUTS SAG (note reelle) : a coller a la fin de tirea-avis.js, sans rien retirer =====
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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

    // Note reelle (affichee seulement quand il y a des avis) : clic = scroll vers la liste
    var ratingLink = document.querySelector('.tirea-rating-link[data-tirea-scroll]');
    if (ratingLink) {
      ratingLink.addEventListener('click', function (e) {
        e.preventDefault();
        scrollToTarget(ratingLink.getAttribute('data-tirea-scroll'));
      });
    }

    // Bouton "Voir plus / Voir moins" de la liste d'avis
    var moreBtn = document.querySelector('.tirea-avis-more');
    var list = document.querySelector('.tirea-avis-list');
    if (moreBtn && list) {
      var initial = parseInt(list.getAttribute('data-initial'), 10) || 0;
      moreBtn.addEventListener('click', function () {
        var cards = list.querySelectorAll('.tirea-avis-card');
        var expanded = moreBtn.getAttribute('aria-expanded') === 'true';
        for (var i = initial; i < cards.length; i++) {
          if (expanded) { cards[i].setAttribute('hidden', ''); }
          else { cards[i].removeAttribute('hidden'); }
        }
        moreBtn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
        moreBtn.textContent = expanded ? "Voir plus d'avis" : 'Voir moins';
      });
    }
  });
})();