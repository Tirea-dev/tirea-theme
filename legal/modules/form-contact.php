<?php // Formulaire de contact AJAX (page Contact)
if (!defined('ABSPATH')) exit; ?>

<div class="tirea-legal-form-card tirea-contact-form-card" data-form-type="contact">
  <form class="tirea-legal-form" id="tirea-form-contact" novalidate>

    <span class="tirea-legal-pill">Réponse sous 24h</span>
    <h2 class="tirea-contact-form-title">
      Une question ? <em>Posez-la nous.</em>
    </h2>
    <p class="tirea-contact-form-lead">Notre équipe vous répond personnellement par e-mail, tous les jours, sous 24&nbsp;heures.</p>

    <div class="tirea-legal-form-grid">
      <div class="tirea-legal-form-group">
        <label for="fc-prenom">Prénom *</label>
        <input type="text" id="fc-prenom" name="prenom" required placeholder="Prénom" aria-required="true">
      </div>
      <div class="tirea-legal-form-group">
        <label for="fc-nom">Nom *</label>
        <input type="text" id="fc-nom" name="nom" required placeholder="Nom" aria-required="true">
      </div>
      <div class="tirea-legal-form-group tirea-legal-form-full">
        <label for="fc-email">Adresse email *</label>
        <input type="email" id="fc-email" name="email" required placeholder="vous@exemple.fr" aria-required="true">
      </div>
      <div class="tirea-legal-form-group tirea-legal-form-full">
        <label for="fc-commande">N° de commande *</label>
        <input type="text" id="fc-commande" name="commande" placeholder="ex : #12345">
      </div>
    </div>

    <div class="tirea-contact-subjects-wrap">
      <span class="tirea-contact-label">Sujet</span>
      <div class="tirea-contact-subjects" role="radiogroup" aria-label="Sujet de votre message">
        <button type="button" class="tirea-contact-chip is-active" data-subject="Ma commande" role="radio" aria-checked="true">Ma commande</button>
        <button type="button" class="tirea-contact-chip" data-subject="Le produit" role="radio" aria-checked="false">Le produit</button>
        <button type="button" class="tirea-contact-chip" data-subject="Retour" role="radio" aria-checked="false">Retour</button>
        <button type="button" class="tirea-contact-chip" data-subject="Pro &amp; presse" role="radio" aria-checked="false">Pro &amp; presse</button>
        <button type="button" class="tirea-contact-chip" data-subject="Autre" role="radio" aria-checked="false">Autre</button>
      </div>
      <input type="hidden" name="sujet" value="Ma commande">
    </div>

    <div class="tirea-legal-form-group tirea-legal-form-full" style="margin-top:18px;">
      <label for="fc-message" class="tirea-sr-only">Votre message</label>
      <textarea id="fc-message" name="message" required rows="6" placeholder="Votre message…" aria-required="true"></textarea>
    </div>

    <?php // Honeypot anti-spam ?>
    <div class="tirea-legal-honeypot" aria-hidden="true">
      <label>Ne pas remplir <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
    </div>

    <div class="tirea-contact-form-foot">
      <span class="tirea-contact-fine">Vos informations restent confidentielles.</span>
      <button type="submit" class="tirea-legal-btn">
        Envoyer ma question
        <span class="tirea-legal-arrow" aria-hidden="true"></span>
      </button>
    </div>

    <div class="tirea-legal-form-feedback" role="status" aria-live="polite"></div>
  </form>
</div>