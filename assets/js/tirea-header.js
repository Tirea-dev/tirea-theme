/**
 * TIREA Header — interactions
 * Gère : dropdown desktop, drawer mobile, overlay recherche, 
 * sticky au scroll, bouton remonter, état actif des liens,
 * focus-trap des modales (drawer mobile + overlay recherche).
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
  // 2. Focus-trap factorisé (drawer mobile + overlay recherche)
  // ============================================
  // Mémorise l'élément déclencheur pour chaque modale (clé = id de la modale)
  var lastFocusedByModal = {};
  // Référence du handler keydown actif (pour pouvoir le détacher à la fermeture)
  var activeTrapHandler = null;
  var activeTrapContainer = null;

  var FOCUSABLE_SELECTOR = 'a[href], button:not([disabled]), input:not([disabled]), ' +
                          'select:not([disabled]), textarea:not([disabled]), ' +
                          '[tabindex]:not([tabindex="-1"])';

  // Récupère les éléments focusables réellement visibles à l'instant t
  // (filtre les éléments cachés type accordéon fermé via offsetParent)
  function getFocusable(container) {
    var nodes = container.querySelectorAll(FOCUSABLE_SELECTOR);
    var result = [];
    for (var i = 0; i < nodes.length; i++) {
      if (nodes[i].offsetParent !== null) result.push(nodes[i]);
    }
    return result;
  }

  // Active le piège : mémorise le déclencheur, focus dans la modale, branche le Tab wrap
  function activateTrap(container, initialFocusEl) {
    if (!container) return;

    // Mémorise l'élément qui avait le focus avant l'ouverture
    lastFocusedByModal[container.id] = document.activeElement;

    // Détache un éventuel trap précédent (cas où on switche menu mobile → recherche)
    deactivateTrap();

    // Focus initial : soit l'élément explicite (champ recherche), soit le 1er focusable
    var target = initialFocusEl;
    if (!target) {
      var focusables = getFocusable(container);
      target = focusables[0] || container;
    }
    // Si le container reçoit le focus, on le rend programmatiquement focusable
    if (target === container && !container.hasAttribute('tabindex')) {
      container.setAttribute('tabindex', '-1');
    }
    // setTimeout aligné sur l'anim CSS de la modale
    setTimeout(function() { if (target) target.focus(); }, 50);

    // Handler Tab : wrap premier ↔ dernier
    activeTrapContainer = container;
    activeTrapHandler = function(e) {
      if (e.key !== 'Tab') return;
      var focusables = getFocusable(container);
      if (focusables.length === 0) {
        e.preventDefault();
        return;
      }
      var first = focusables[0];
      var last = focusables[focusables.length - 1];
      var active = document.activeElement;

      // Si le focus est hors de la modale (cas limite), on le ramène
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

  // Désactive le piège : détache le handler, rend le focus au déclencheur
  function deactivateTrap(containerId) {
    if (activeTrapHandler) {
      document.removeEventListener('keydown', activeTrapHandler);
      activeTrapHandler = null;
    }
    activeTrapContainer = null;

    // Restaure le focus sur le déclencheur mémorisé
    if (containerId && lastFocusedByModal[containerId]) {
      var trigger = lastFocusedByModal[containerId];
      lastFocusedByModal[containerId] = null;
      // requestAnimationFrame pour éviter conflit avec d'autres focus
      requestAnimationFrame(function() {
        if (trigger && typeof trigger.focus === 'function') trigger.focus();
      });
    }
  }

  // ============================================
  // 3. Helpers
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
    // Active le focus-trap
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
    // Désactive le focus-trap et rend le focus au déclencheur
    deactivateTrap('tireaMobileMenu');
  }

  function openSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.add('active');
    searchOverlay.setAttribute('aria-hidden', 'false');
    body.classList.add('tirea-search-open');
    // Focus initial direct sur le champ de recherche
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
  // 4. Dropdowns "Informations" (desktop normal + sticky)
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
  // 5. Drawer mobile (depuis burger normal OU sticky)
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
  // 6. Accordéon "Légal" dans menu mobile
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
  // 7. Recherche : ouverture/fermeture
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
  // 8. Touche Échap → ferme ce qui est ouvert
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
  // 9. Sticky header + bouton remonter au scroll
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
  // 10. Bouton "remonter en haut"
  // ============================================
  function scrollToTop() {
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    window.scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' });
  }
  if (backToTopDesktop) backToTopDesktop.addEventListener('click', scrollToTop);
  if (backToTopMobile) backToTopMobile.addEventListener('click', scrollToTop);

  // ============================================
  // 11. Marquage du lien actif (depuis data-slug)
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