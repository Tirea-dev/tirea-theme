<?php // Layout spécifique de la page Contact : hero + intents + form + info cards
if (!defined('ABSPATH')) exit;
$tirea_legal_dir = get_stylesheet_directory() . '/legal'; ?>

<div class="tirea-contact-page">

  <?php // Hero (réutilise la structure standard) ?>
  <?php include $tirea_legal_dir . '/partials/hero.php'; ?>

  <?php // INTENTS — 3 cards d'orientation ?>
  <div class="tirea-contact-intents">
    <a class="tirea-contact-intent" href="<?php echo esc_url(home_url('/mon-compte/')); ?>">
      <span class="tirea-contact-intent-num">01</span>
      <span class="tirea-contact-intent-h">Ma commande</span>
      <span class="tirea-contact-intent-sub">Suivi · livraison · modification</span>
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
      <span class="tirea-contact-intent-sub">14 jours · sans justification</span>
      <span class="tirea-contact-intent-link">Lancer un retour →</span>
    </a>
  </div>

  <?php // FORM ?>
  <div class="tirea-contact-form-wrap">
    <?php include $tirea_legal_dir . '/modules/form-contact.php'; ?>
  </div>

  <?php // INFO CARDS — 3 colonnes ?>
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
        <div class="tirea-contact-big">Bientôt disponible</div>
        <div class="tirea-contact-tiny">Ligne pro en cours d'attribution</div>
      </div>
    </div>

    <div class="tirea-contact-info-card">
      <span class="tirea-contact-label">Horaires service client</span>
      <div class="tirea-contact-hours">
        <div><span>Lundi – Vendredi</span><strong>9h → 19h</strong></div>
        <div><span>Samedi</span><strong>10h → 18h</strong></div>
        <div><span>Dimanche</span><strong>14h → 18h</strong></div>
      </div>
      <div class="tirea-contact-status">Réponse moyenne · 4h</div>
    </div>

    <div class="tirea-contact-info-card">
      <span class="tirea-contact-label">Suivez TIREA</span>
      <p class="tirea-contact-social-intro">Coulisses, nouveautés et conseils style.</p>
      <div class="tirea-contact-social">
        <a href="#" aria-label="Instagram TIREA">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
        </a>
        <a href="#" aria-label="Facebook TIREA">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.5 22v-8h2.7l.4-3.2h-3.1V8.7c0-.9.3-1.6 1.7-1.6h1.5V4.2c-.3 0-1.2-.1-2.3-.1-2.3 0-3.9 1.4-3.9 4v2.7H8v3.2h2.5V22h3z"/></svg>
        </a>
        <a href="#" aria-label="TikTok TIREA">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19.3 8.3a5.6 5.6 0 0 1-3.4-1.1 5.6 5.6 0 0 1-2.2-3.7h-3v12.2a2.5 2.5 0 1 1-2.5-2.5c.3 0 .5 0 .8.1V10a5.6 5.6 0 0 0-.8-.1 5.6 5.6 0 1 0 5.6 5.6V9.7a8.5 8.5 0 0 0 5 1.6V8.3z"/></svg>
        </a>
      </div>
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