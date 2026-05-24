/**
 * TIREA Header — interactions
 * Dropdown desktop, drawer mobile, overlay recherche, sticky, bouton remonter,
 * lien actif, focus-trap des modales.
 */
(function() {
  'use strict';

  // ============================================
  // 1. Sélecteurs partagés
  // ============================================
  var body = document.body;
  var mobileMenu = document.getElementById('tireaMobileMenu');
  var searchOverlay = document.getElementById('tireaSearchOverlay');
  var searchInput = document.getElementById('tireaSearchInput');

  // ============================================
  // 2. Focus-trap factorisé (drawer mobile + overlay recherche)
  // ============================================
  var lastFocusedByModal = {};
  var activeTrapHandler = null;

  var FOCUSABLE_SELECTOR = 'a[href], button:not([disabled]), input:not([disabled]), ' +
                          'select:not([disabled]), textarea:not([disabled]), ' +
                          '[tabindex]:not([tabindex="-1"])';

  function getFocusable(container) {
    var nodes = container.querySelectorAll(FOCUSABLE_SELECTOR);
    var result = [];
    for (var i = 0; i < nodes.length; i++) {
      if (nodes[i].offsetParent !== null) result.push(nodes[i]);
    }
    return result;
  }

  function activateTrap(container, initialFocusEl) {
    if (!container) return;
    lastFocusedByModal[container.id] = document.activeElement;
    deactivateTrap();

    var target = initialFocusEl;
    if (!target) {
      var focusables = getFocusable(container);
      target = focusables[0] || container;
    }
    if (target === container && !container.hasAttribute('tabindex')) {
      container.setAttribute('tabindex', '-1');
    }
    setTimeout(function() { if (target) target.focus(); }, 50);

    activeTrapHandler = function(e) {
      if (e.key !== 'Tab') return;
      var focusables = getFocusable(container);
      if (focusables.length === 0) { e.preventDefault(); return; }
      var first = focusables[0];
      var last = focusables[focusables.length - 1];
      var active = document.activeElement;

      if (!container.contains(active)) {
        e.preventDefault();
        first.focus();
        return;
      }
      if (e.shiftKey && active === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && active === last) {
        e.preventDefault();
        first.focus();
      }
    };
    document.addEventListener('keydown', activeTrapHandler);
  }

  function deactivateTrap(containerId) {
    if (activeTrapHandler) {
      document.removeEventListener('keydown', activeTrapHandler);
      activeTrapHandler = null;
    }
    if (containerId && lastFocusedByModal[containerId]) {
      var trigger = lastFocusedByModal[containerId];
      lastFocusedByModal[containerId] = null;
      requestAnimationFrame(function() {
        if (trigger && typeof trigger.focus === 'function') trigger.focus();
      });
    }
  }

  // ============================================
  // 3. Dropdowns "Informations" (desktop)
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

  // ============================================
  // 4. Drawer mobile
  // ============================================
  function openMobileMenu(burger) {
    if (!mobileMenu) return;
    if (burger) {
      burger.classList.add('active');
      burger.setAttribute('aria-expanded', 'true');
    }
    mobileMenu.classList.add('active');
    mobileMenu.setAttribute('aria-hidden', 'false');
    body.classList.add('tirea-menu-open');
    activateTrap(mobileMenu);
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
    deactivateTrap('tireaMobileMenu');
  }

  function openSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.add('active');
    searchOverlay.setAttribute('aria-hidden', 'false');
    body.classList.add('tirea-search-open');
    activateTrap(searchOverlay, searchInput);
  }

  function closeSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.remove('active');
    searchOverlay.setAttribute('aria-hidden', 'true');
    body.classList.remove('tirea-search-open');
    if (searchInput) searchInput.value = '';
    deactivateTrap('tireaSearchOverlay');
  }

  // ============================================
  // 5. Branchements dropdowns
  // ============================================
  toggleDropdown(document.getElementById('tireaBurgerDesktop'), document.getElementById('tireaDropdown'));
  toggleDropdown(document.getElementById('tireaStickyBurgerDesktop'), document.getElementById('tireaStickyDropdown'));

  // ============================================
  // 6. Branchements drawer mobile
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

  // Clic sur le fond sombre (hors panneau) → ferme
  if (mobileMenu) {
    mobileMenu.addEventListener('click', function(e) {
      if (e.target === mobileMenu) closeMobileMenu();
    });
  }

  // Clic sur un lien du menu → ferme
  document.querySelectorAll('.tirea-mobile-nav a, .tirea-mobile-sublink').forEach(function(link) {
    link.addEventListener('click', closeMobileMenu);
  });

  // ============================================
  // 7. Accordéon "Légal"
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
  // 8. Recherche
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
  // 9. Touche Échap
  // ============================================
  document.addEventListener('keydown', function(e) {
    if (e.key !== 'Escape') return;
    if (mobileMenu && mobileMenu.classList.contains('active')) closeMobileMenu();
    if (searchOverlay && searchOverlay.classList.contains('active')) closeSearch();

    var dropdown = document.getElementById('tireaDropdown');
    var burgerD = document.getElementById('tireaBurgerDesktop');
    if (dropdown && dropdown.classList.contains('active')) {
      dropdown.classList.remove('active');
      if (burgerD) { burgerD.classList.remove('active'); burgerD.setAttribute('aria-expanded', 'false'); }
    }
    var stickyDropdown = document.getElementById('tireaStickyDropdown');
    var stickyBurgerD = document.getElementById('tireaStickyBurgerDesktop');
    if (stickyDropdown && stickyDropdown.classList.contains('active')) {
      stickyDropdown.classList.remove('active');
      if (stickyBurgerD) { stickyBurgerD.classList.remove('active'); stickyBurgerD.setAttribute('aria-expanded', 'false'); }
    }
  });

  // ============================================
  // 10. Sticky header + bouton remonter
  // ============================================
  var stickyHeader = document.getElementById('tireaStickyHeader');
  var backToTopDesktop = document.getElementById('tireaBackToTopDesktop');

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
    if (!ticking) { window.requestAnimationFrame(updateOnScroll); ticking = true; }
  }, { passive: true });
  updateOnScroll();

  // ============================================
  // 11. Bouton remonter
  // ============================================
  function scrollToTop() {
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    window.scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' });
  }
  var backToTopMobile = document.getElementById('tireaBackToTopMobile');
  if (backToTopDesktop) backToTopDesktop.addEventListener('click', scrollToTop);
  if (backToTopMobile) backToTopMobile.addEventListener('click', scrollToTop);

  // ============================================
  // 12. Lien actif
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