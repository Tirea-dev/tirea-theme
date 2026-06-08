<?php
// Template principal des pages légales — rend le shortcode [tirea_legal_page slug="xxx"]
if (!defined('ABSPATH')) exit;

// Récupère le slug passé en attribut ; si absent, on tente celui de la page courante
$tirea_legal_slug = isset($atts['slug']) ? sanitize_key($atts['slug']) : '';
if (!$tirea_legal_slug && is_page()) {
    $tirea_legal_slug = get_post_field('post_name');
}

// Charge la config globale
$tirea_legal_config = require get_stylesheet_directory() . '/legal/config.php';

if (!isset($tirea_legal_config[$tirea_legal_slug])) {
    return;
}

$tirea_legal_page = $tirea_legal_config[$tirea_legal_slug];
$tirea_legal_dir  = get_stylesheet_directory() . '/legal';
$tirea_legal_type = isset($tirea_legal_page['type']) ? $tirea_legal_page['type'] : 'standard';
?>

<div class="tirea-legal-wrapper">
  <main class="tirea-legal-page">

    <?php if ($tirea_legal_type === 'contact') : ?>

      <?php include $tirea_legal_dir . '/partials/contact-page.php'; ?>

    <?php elseif ($tirea_legal_type === 'histoire') : ?>

      <?php include $tirea_legal_dir . '/partials/histoire-page.php'; ?>

    <?php else : ?>

      <?php // Layout standard (CGV, Mentions, Confidentialité, Livraison, Retours) ?>
      <?php include $tirea_legal_dir . '/partials/hero.php'; ?>

      <div class="tirea-legal-layout">

        <?php include $tirea_legal_dir . '/partials/toc-desktop.php'; ?>
        <?php include $tirea_legal_dir . '/partials/toc-mobile.php'; ?>

        <article class="tirea-legal-content">

          <?php
          $tirea_content_file = $tirea_legal_dir . '/contents/' . $tirea_legal_slug . '.php';
          if (file_exists($tirea_content_file)) {
              include $tirea_content_file;
          }
          ?>

          <?php include $tirea_legal_dir . '/partials/cta-card.php'; ?>

        </article>
      </div>

      <?php // Modal partagée pour les forms (page Retours) ?>
      <?php if (!empty($tirea_legal_page['module'])) : ?>
        <div class="tirea-legal-modal" id="tirea-legal-modal" role="dialog" aria-modal="true" aria-labelledby="tirea-legal-modal-title" aria-hidden="true">
          <div class="tirea-legal-modal-box">
            <button type="button" class="tirea-legal-modal-close" aria-label="Fermer">&times;</button>
            <div class="tirea-legal-modal-icon">
              <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
            </div>
            <span class="tirea-legal-modal-tag" id="tirea-legal-modal-tag">Demande envoyée</span>
            <h2 id="tirea-legal-modal-title">Merci, <em>c'est bien parti.</em></h2>
            <p id="tirea-legal-modal-msg">Votre demande a bien été reçue. Une copie vous a été envoyée par email.</p>
            <button type="button" class="tirea-legal-modal-btn" data-modal-close>Fermer</button>
          </div>
        </div>
      <?php endif; ?>

    <?php endif; ?>

  </main>
</div>

/* ==========================================================================
   Réseaux sociaux : composant partagé, même rendu que le footer
   ========================================================================== */
.tirea-socials{
  display: flex;
  gap: 14px;
  list-style: none;
  padding: 0;
  margin: 0;
}
.tirea-socials li{
  list-style: none;
  margin: 0;
  padding: 0;
  background: none;
}
.tirea-socials li::before,
.tirea-socials li::marker{
  content: none;
  display: none;
}
.tirea-socials a{
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: #fff;
  border: 1px solid #e5e5e5;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #333;
  text-decoration: none;
  transition: background-color .2s, border-color .2s, color .2s;
}
.tirea-socials a:hover,
.tirea-socials a:focus-visible{
  background: var(--tirea-color-primary);
  border-color: var(--tirea-color-primary);
  color: #fff;
  outline: none;
}
.tirea-socials svg{
  width: 18px;
  height: 18px;
}
/* Espacement dans la carte contact (repris de l'ancien .tirea-contact-social) */
.tirea-contact-info-card .tirea-socials{ margin-top: 18px; }