/* TIREA — Pages légales : smooth scroll TOC + section active via IntersectionObserver */
(function(){
  'use strict';

  var links = document.querySelectorAll('.tirea-legal-toc a[href^="#"], .tirea-legal-toc-mobile-list a[href^="#"]');
  if (!links.length) return;

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Smooth scroll au clic sur un lien du TOC
  links.forEach(function(a){
    a.addEventListener('click', function(e){
      var id = a.getAttribute('href');
      if (!id || id.charAt(0) !== '#') return;
      var el = document.querySelector(id);
      if (!el) return;
      e.preventDefault();
      el.scrollIntoView({
        behavior: reduceMotion ? 'auto' : 'smooth',
        block: 'start'
      });
      history.replaceState(null, '', id);

      // Ferme l'accordéon mobile après clic
      var mobile = document.querySelector('.tirea-legal-toc-mobile');
      if (mobile && mobile.open && a.closest('.tirea-legal-toc-mobile')) {
        mobile.open = false;
      }
    });
  });

  // Highlight de la section visible (desktop uniquement)
  var tocLinks = document.querySelectorAll('.tirea-legal-toc a[href^="#"]');
  if (!tocLinks.length || !('IntersectionObserver' in window)) return;

  var map = new Map();
  tocLinks.forEach(function(a){
    map.set(a.getAttribute('href').slice(1), a);
  });

  var io = new IntersectionObserver(function(entries){
    entries.forEach(function(en){
      var id = en.target.id;
      var link = map.get(id);
      if (!link) return;
      if (en.isIntersecting){
        tocLinks.forEach(function(l){ l.classList.remove('is-active'); });
        link.classList.add('is-active');
      }
    });
  }, { rootMargin: '-30% 0px -60% 0px', threshold: 0 });

  document.querySelectorAll('.tirea-legal-section[id]').forEach(function(s){
    io.observe(s);
  });
})();