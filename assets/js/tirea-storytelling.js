/**
 * TIREA Storytelling — Animation des mots au scroll
 * 
 * Déclenche l'animation cyclique des mots quand la section entre dans la vue.
 * Reset si la section sort de la vue (pour rejouer au re-scroll).
 * Respecte prefers-reduced-motion : pas d'animation.
 */

(function () {
  'use strict';

  const section = document.querySelector('.tirea-storytelling');
  if (!section) return;

  const words = section.querySelectorAll('.tirea-story-word');
  if (!words.length) return;

  // Respect du choix utilisateur : pas d'animation
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  let animationStarted = false;
  let timeouts = [];
  const DELAY_BETWEEN_WORDS = 1500;

  function startAnimation() {
    if (animationStarted) return;
    animationStarted = true;

    words.forEach((word, index) => {
      const t = setTimeout(() => {
        words.forEach(w => w.classList.remove('visible'));
        word.classList.add('visible');
      }, index * DELAY_BETWEEN_WORDS);
      timeouts.push(t);
    });
  }

  function resetAnimation() {
    animationStarted = false;
    timeouts.forEach(t => clearTimeout(t));
    timeouts = [];
    words.forEach(w => w.classList.remove('visible'));
  }

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        startAnimation();
      } else {
        resetAnimation();
      }
    });
  }, {
    threshold: 0.5,
  });

  observer.observe(section);
})();