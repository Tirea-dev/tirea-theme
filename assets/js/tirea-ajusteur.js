/**
 * TIREA — Animation au scroll de la section ajusteur
 * 
 * Observe chaque .tirea-ajusteur-visual sur la page et ajoute/retire la classe
 * 'in-view' selon la visibilité. Supporte plusieurs instances (home + produit).
 * 
 * Respecte prefers-reduced-motion : si l'utilisateur a désactivé les animations,
 * la classe 'in-view' est ajoutée immédiatement sans observer (état final visible
 * sans transition CSS pour rester WCAG 2.3.3 compliant).
 */
(function() {
    'use strict';

    // Lance dès que le DOM est prêt (script en defer = DOM déjà parsé)
    var visuals = document.querySelectorAll('.tirea-ajusteur-visual');
    if (!visuals.length) return;

    // Détection du mode "réduire les animations"
    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Fallback si IntersectionObserver indisponible (très vieux navigateurs)
    // OU si l'utilisateur veut moins d'animations → on affiche directement l'état final
    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        visuals.forEach(function(el) {
            el.classList.add('in-view');
        });
        return;
    }

    var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting) {
            entry.target.classList.add('in-view');
        } else {
            entry.target.classList.remove('in-view');
        }
    });
}, {
    threshold: 0.5,
    rootMargin: '0px 0px -100px 0px'
});

    visuals.forEach(function(el) {
        observer.observe(el);
    });
})();