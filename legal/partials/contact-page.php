<?php // Layout spécifique de la page Contact : hero + intents + form + info cards
if (!defined('ABSPATH')) exit;
$tirea_legal_dir = get_stylesheet_directory() . '/legal'; ?>

<div class="tirea-contact-page">

  <?php // Hero (réutilise la structure standard) ?>
  <?php include $tirea_legal_dir . '/partials/hero.php'; ?>

  <?php // INTENTS - 3 cards d'orientation ?>
  <div class="tirea-contact-intents">
    <a class="tirea-contact-intent" href="<?php echo esc_url(home_url('/suivi/')); ?>">
      <span class="tirea-contact-intent-num">01</span>
      <span class="tirea-contact-intent-h">Ma commande</span>
      <span class="tirea-contact-intent-sub">Suivi · livraison</span>
      <span class="tirea-contact-intent-link">Suivre →</span>
    </a>
    <a class="tirea-contact-intent" href="<?php echo esc_url(home_url('/#faq')); ?>">
      <span class="tirea-contact-intent-num">02</span>
      <span class="tirea-contact-intent-h">Le produit</span>
      <span class="tirea-contact-intent-sub">Taille · usage · entretien</span>
      <span class="tirea-contact-intent-link">Voir la FAQ →</span>
    </a>
    <a class="tirea-contact-intent" href="<?php echo esc_url(home_url('/retours/')); ?>">
      <span class="tirea-contact-intent-num">03</span>
      <span class="tirea-contact-intent-h">Retour</span>
      <span class="tirea-contact-intent-sub">30 jours · sans justification</span>
      <span class="tirea-contact-intent-link">Lancer un retour →</span>
    </a>
  </div>

  <?php // FORM ?>
  <div class="tirea-contact-form-wrap">
    <?php include $tirea_legal_dir . '/modules/form-contact.php'; ?>
  </div>

  <?php // INFO CARDS - 3 colonnes ?>
  <div class="tirea-contact-info">

    <div class="tirea-contact-info-card">
      <span class="tirea-contact-label">Nous joindre directement</span>
      <div class="tirea-contact-info-section">
        <span class="tirea-contact-sublabel">Email</span>
        <a class="tirea-contact-big tirea-contact-mail tirea-contact-email-link" href="#" data-user="contact" data-host="tirea.fr">contact <span aria-hidden="true">[at]</span> tirea.fr</a>
        <div class="tirea-contact-tiny">Réponse sous 24h · 7j/7</div>
      </div>
      <div class="tirea-contact-info-section">
        <span class="tirea-contact-sublabel">Téléphone</span>
        <div class="tirea-contact-big">07 75 77 56 16</div>
        <div class="tirea-contact-tiny">Appel ou SMS · réponse rapide</div>
      </div>
    </div>

    <div class="tirea-contact-info-card">
      <span class="tirea-contact-label">Horaires service client</span>
      <div class="tirea-contact-hours">
        <div><span>Lundi – Vendredi</span><strong>10h → 18h</strong></div>
        <div><span>Samedi</span><strong>10h → 14h</strong></div>
        <div><span>Dimanche</span><strong>14h → 16h</strong></div>
      </div>
      <div class="tirea-contact-status">Réponse moyenne · 30min</div>
    </div>

    <div class="tirea-contact-info-card">
      <span class="tirea-contact-label">Suivez TIREA</span>
      <p class="tirea-contact-social-intro">Coulisses, nouveautés et conseils style.</p>
      <?php tirea_render_socials(); ?>
    </div>

  </div>

  <?php // Modal de confirmation (succès / erreur) ?>
  <div class="tirea-legal-modal" id="tirea-legal-modal" role="dialog" aria-modal="true" aria-labelledby="tirea-legal-modal-title" aria-hidden="true">
    <div class="tirea-legal-modal-box">
      <button type="button" class="tirea-legal-modal-close" aria-label="Fermer">&times;</button>
      <div class="tirea-legal-modal-icon">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
      </div>
      <span class="tirea-legal-modal-tag" id="tirea-legal-modal-tag">Message envoyé</span>
      <h2 id="tirea-legal-modal-title">Merci, <em>c'est bien parti.</em></h2>
      <p id="tirea-legal-modal-msg">Notre équipe française a bien reçu votre message et vous répond personnellement, par e-mail, sous 24&nbsp;heures.</p>
      <button type="button" class="tirea-legal-modal-btn" data-modal-close>Fermer</button>
    </div>
  </div>

</div>