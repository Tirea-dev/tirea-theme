/**
 * TIREA Footer — Logique newsletter
 * 
 * Gère la soumission du formulaire newsletter.
 * Si le form a une action (Brevo configuré), laisse passer la soumission normale.
 * Sinon, intercepte et affiche un message "bientôt disponible".
 */

(function () {
  'use strict';

  const form = document.querySelector('[data-tirea-newsletter]');
  if (!form) return;

  const feedback = form.parentElement.querySelector('.tirea-newsletter-feedback');
  const input = form.querySelector('input[type="email"]');

  form.addEventListener('submit', function (event) {
    // Si pas d'action définie, mode "inactif" — on bloque et on affiche un message
    if (!form.action || form.action === window.location.href) {
      event.preventDefault();

      // Validation HTML5 basique de l'email
      if (!input.checkValidity()) {
        showFeedback("Merci d'entrer une adresse email valide.", 'is-error');
        input.focus();
        return;
      }

      showFeedback("Merci ! Inscription bientôt disponible — restez connecté.", 'is-success');
      input.value = '';
    }
    // Sinon : on laisse passer la soumission normale vers Brevo
  });

  /**
   * Affiche un message de feedback sous le form
   * @param {string} message - Texte à afficher
   * @param {string} className - Classe de style ('is-success' ou 'is-error')
   */
  function showFeedback(message, className) {
    if (!feedback) return;
    feedback.textContent = message;
    feedback.className = 'tirea-newsletter-feedback ' + className;
  }
})();