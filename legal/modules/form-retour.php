<?php // Formulaire de rétractation AJAX (page Retours, section 08)
if (!defined('ABSPATH')) exit; ?>

<div class="tirea-legal-form-card" data-form-type="retour">
  <form class="tirea-legal-form" id="tirea-form-retour" novalidate>

    <div class="tirea-legal-form-grid">
      <div class="tirea-legal-form-group">
        <label for="fr-commande">Numéro de commande *</label>
        <input type="text" id="fr-commande" name="commande" required placeholder="ex : #12345" aria-required="true">
      </div>
      <div class="tirea-legal-form-group">
        <label for="fr-date-cmd">Commandé le *</label>
        <input type="date" id="fr-date-cmd" name="date_commande" required aria-required="true">
      </div>
      <div class="tirea-legal-form-group">
        <label for="fr-date-rcpt">Reçu le *</label>
        <input type="date" id="fr-date-rcpt" name="date_reception" required aria-required="true">
      </div>
      <div class="tirea-legal-form-group">
        <label for="fr-nom">Nom du consommateur *</label>
        <input type="text" id="fr-nom" name="nom" required placeholder="Prénom Nom" aria-required="true">
      </div>
      <div class="tirea-legal-form-group tirea-legal-form-full">
        <label for="fr-email">Adresse email *</label>
        <input type="email" id="fr-email" name="email" required placeholder="vous@exemple.fr" aria-required="true">
      </div>
      <div class="tirea-legal-form-group tirea-legal-form-full">
        <label for="fr-adresse">Adresse du consommateur *</label>
        <input type="text" id="fr-adresse" name="adresse" required placeholder="Adresse complète" aria-required="true">
      </div>
      <div class="tirea-legal-form-group tirea-legal-form-full">
        <label for="fr-article">Article(s) concerné(s) *</label>
        <input type="text" id="fr-article" name="article" required placeholder="Nom ou référence de l'article" aria-required="true">
      </div>
      <div class="tirea-legal-form-group tirea-legal-form-full">
        <label for="fr-motif">Motif (facultatif)</label>
        <textarea id="fr-motif" name="motif" rows="3" placeholder="Décrivez la raison de votre retour…"></textarea>
      </div>
    </div>

    <?php // Honeypot anti-spam — invisible aux humains, rempli par les bots ?>
    <div class="tirea-legal-honeypot" aria-hidden="true">
      <label>Ne pas remplir <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
    </div>

    <p class="tirea-legal-form-note">(*) Champs obligatoires. En soumettant ce formulaire, je notifie TIREA de ma rétractation du contrat portant sur la vente du bien ci-dessus. Une copie de votre demande vous sera envoyée par email.</p>

    <div class="tirea-legal-form-submit">
      <button type="submit" class="tirea-legal-btn">
        Envoyer ma demande
        <span class="tirea-legal-arrow" aria-hidden="true"></span>
      </button>
    </div>

    <div class="tirea-legal-form-feedback" role="status" aria-live="polite"></div>
  </form>
</div>