/**
 * TIREA Header — interactions
 * Gère : dropdown desktop, drawer mobile, overlay recherche, 
 * sticky au scroll, bouton remonter, état actif des liens.
 */
(function() {
  'use strict';

  // ============================================
  // 0. Déplacement des overlays vers <body>
  // ============================================
  ['tireaMobileMenu', 'tireaSearchOverlay'].forEach(function(id) {
    var el = document.getElementById(id);
    if (el && el.parentNode !== document.body) {
      document.body.appendChild(el);
    }
  });

  // ============================================
  // 1. Sélecteurs partagés
  // ============================================
  var body = document.body;
  var mobileMenu = document.getElementById('tireaMobileMenu');
  var searchOverlay = document.getElementById('tireaSearchOverlay');
  var searchInput = document.getElementById('tireaSearchInput');

  // ============================================
  // 2. Helpers
  // ============================================
  function toggleDropdown(button, dropdown) {
    if (!button || !dropdown) return;

    button.addEventListener('click', function(e) {
      e.stopPropagation();
      var isActive = button.classList.toggle('active');
      dropdown.classList.toggle('active');
      button.setAttribute('aria-expanded', isActive);
    });

    document.addEventListener('click', function(e) {
      if (!dropdown.contains(e.target) && !button.contains(e.target)) {
        button.classList.remove('active');
        dropdown.classList.remove('active');
        button.setAttribute('aria-expanded', 'false');
      }
    });
  }

  function openMobileMenu(burger) {
    if (!mobileMenu || !burger) return;
    burger.classList.add('active');
    mobileMenu.classList.add('active');
    mobileMenu.setAttribute('aria-hidden', 'false');
    burger.setAttribute('aria-expanded', 'true');
    body.classList.add('tirea-menu-open');
  }

  function closeMobileMenu() {
    if (!mobileMenu) return;
    document.querySelectorAll('.tirea-burger-mobile, .tirea-sticky-burger-mobile').forEach(function(b) {
      b.classList.remove('active');
      b.setAttribute('aria-expanded', 'false');
    });
    mobileMenu.classList.remove('active');
    mobileMenu.setAttribute('aria-hidden', 'true');
    body.classList.remove('tirea-menu-open');
  }

  function openSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.add('active');
    searchOverlay.setAttribute('aria-hidden', 'false');
    body.classList.add('tirea-search-open');
    setTimeout(function() {
      if (searchInput) searchInput.focus();
    }, 400);
  }

  function closeSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.remove('active');
    searchOverlay.setAttribute('aria-hidden', 'true');
    body.classList.remove('tirea-search-open');
    if (searchInput) searchInput.value = '';
  }

  // ============================================
  // 3. Dropdowns "Informations" (desktop normal + sticky)
  // ============================================
  toggleDropdown(
    document.getElementById('tireaBurgerDesktop'),
    document.getElementById('tireaDropdown')
  );
  toggleDropdown(
    document.getElementById('tireaStickyBurgerDesktop'),
    document.getElementById('tireaStickyDropdown')
  );

  // ============================================
  // 4. Drawer mobile (depuis burger normal OU sticky)
  // ============================================
  var burgerMobile = document.getElementById('tireaBurgerMobile');
  var stickyBurgerMobile = document.getElementById('tireaStickyBurgerMobile');
  var closeBtn = document.getElementById('tireaClose');

  if (burgerMobile) {
    burgerMobile.addEventListener('click', function() {
      if (mobileMenu.classList.contains('active')) closeMobileMenu();
      else openMobileMenu(burgerMobile);
    });
  }
  if (stickyBurgerMobile) {
    stickyBurgerMobile.addEventListener('click', function() {
      if (mobileMenu.classList.contains('active')) closeMobileMenu();
      else openMobileMenu(stickyBurgerMobile);
    });
  }
  if (closeBtn) closeBtn.addEventListener('click', closeMobileMenu);

  // Click sur l'overlay (en dehors du drawer) → ferme
  if (mobileMenu) {
    mobileMenu.addEventListener('click', function(e) {
      if (e.target === mobileMenu) closeMobileMenu();
    });
  }

  // Click sur un lien du menu mobile → ferme
  document.querySelectorAll('.tirea-mobile-nav a, .tirea-mobile-sublink').forEach(function(link) {
    link.addEventListener('click', closeMobileMenu);
  });

  // ============================================
  // 5. Accordéon "Légal" dans menu mobile
  // ============================================
  var accordionToggle = document.getElementById('tireaAccordionToggle');
  var accordionContent = document.getElementById('tireaAccordionContent');
  if (accordionToggle && accordionContent) {
    accordionToggle.addEventListener('click', function(e) {
      e.stopPropagation();
      var isActive = accordionToggle.classList.toggle('active');
      accordionContent.classList.toggle('active');
      accordionToggle.setAttribute('aria-expanded', isActive);
    });
  }

  // ============================================
  // 6. Recherche : ouverture/fermeture
  // ============================================
  var searchToggle = document.getElementById('tireaSearchToggle');
  var stickySearchToggle = document.getElementById('tireaStickySearchToggle');
  var searchClose = document.getElementById('tireaSearchClose');
  var mobileSearchBtn = document.getElementById('tireaMobileSearchBtn');

  if (searchToggle) searchToggle.addEventListener('click', openSearch);
  if (stickySearchToggle) stickySearchToggle.addEventListener('click', openSearch);
  if (searchClose) searchClose.addEventListener('click', closeSearch);

  if (mobileSearchBtn) {
    mobileSearchBtn.addEventListener('click', function() {
      closeMobileMenu();
      setTimeout(openSearch, 300);
    });
  }

  // ============================================
  // 7. Touche Échap → ferme ce qui est ouvert
  // ============================================
  document.addEventListener('keydown', function(e) {
    if (e.key !== 'Escape') return;
    if (mobileMenu && mobileMenu.classList.contains('active')) closeMobileMenu();
    if (searchOverlay && searchOverlay.classList.contains('active')) closeSearch();

    var dropdown = document.getElementById('tireaDropdown');
    var burgerD = document.getElementById('tireaBurgerDesktop');
    if (dropdown && dropdown.classList.contains('active')) {
      dropdown.classList.remove('active');
      if (burgerD) {
        burgerD.classList.remove('active');
        burgerD.setAttribute('aria-expanded', 'false');
      }
    }
    var stickyDropdown = document.getElementById('tireaStickyDropdown');
    var stickyBurgerD = document.getElementById('tireaStickyBurgerDesktop');
    if (stickyDropdown && stickyDropdown.classList.contains('active')) {
      stickyDropdown.classList.remove('active');
      if (stickyBurgerD) {
        stickyBurgerD.classList.remove('active');
        stickyBurgerD.setAttribute('aria-expanded', 'false');
      }
    }
  });

  // ============================================
  // 8. Sticky header + bouton remonter au scroll
  // ============================================
  var stickyHeader = document.getElementById('tireaStickyHeader');
  var backToTopDesktop = document.getElementById('tireaBackToTopDesktop');
  var backToTopMobile = document.getElementById('tireaBackToTopMobile');

  var STICKY_THRESHOLD = 200;
  var BACK_TO_TOP_THRESHOLD = 300;
  var ticking = false;

  function updateOnScroll() {
    var scrollY = window.scrollY || window.pageYOffset;

    if (stickyHeader) {
      if (scrollY > STICKY_THRESHOLD) {
        stickyHeader.classList.add('active');
        stickyHeader.setAttribute('aria-hidden', 'false');
      } else {
        stickyHeader.classList.remove('active');
        stickyHeader.setAttribute('aria-hidden', 'true');
      }
    }

    if (backToTopDesktop) {
      backToTopDesktop.classList.toggle('active', scrollY > BACK_TO_TOP_THRESHOLD);
    }

    ticking = false;
  }

  window.addEventListener('scroll', function() {
    if (!ticking) {
      window.requestAnimationFrame(updateOnScroll);
      ticking = true;
    }
  }, { passive: true });
  updateOnScroll();

  // ============================================
  // 9. Bouton "remonter en haut"
  // ============================================
  function scrollToTop() {
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    window.scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' });
  }
  if (backToTopDesktop) backToTopDesktop.addEventListener('click', scrollToTop);
  if (backToTopMobile) backToTopMobile.addEventListener('click', scrollToTop);

  // ============================================
  // 10. Marquage du lien actif (depuis data-slug)
  // ============================================
  function getCurrentSlug() {
    return window.location.pathname.replace(/^\/+|\/+$/g, '').toLowerCase();
  }

  function setActiveLinks() {
    var currentSlug = getCurrentSlug();
    var selector = '.tirea-nav-pill a, .tirea-sticky-nav-pill a, ' +
                   '.tirea-dropdown a, .tirea-sticky-dropdown a, ' +
                   '.tirea-mobile-link, .tirea-mobile-sublink';
    document.querySelectorAll(selector).forEach(function(link) {
      var linkSlug = link.getAttribute('data-slug');
      if (linkSlug === null) return;
      if (linkSlug === currentSlug) link.classList.add('active');
      else link.classList.remove('active');
    });
  }
  setActiveLinks();

})();