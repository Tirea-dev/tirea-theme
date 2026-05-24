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

// Page demandée introuvable → on n'affiche rien (évite plantage)
if (!isset($tirea_legal_config[$tirea_legal_slug])) {
    return;
}

$tirea_legal_page = $tirea_legal_config[$tirea_legal_slug];
$tirea_legal_dir  = get_stylesheet_directory() . '/legal';
?>

<div class="tirea-legal-wrapper">
  <main class="tirea-legal-page">

    <?php include $tirea_legal_dir . '/partials/hero.php'; ?>

    <div class="tirea-legal-layout">

      <?php include $tirea_legal_dir . '/partials/toc-desktop.php'; ?>
      <?php include $tirea_legal_dir . '/partials/toc-mobile.php'; ?>

      <article class="tirea-legal-content">

        <?php
        // Charge le contenu spécifique à la page (sections numérotées)
        $tirea_content_file = $tirea_legal_dir . '/contents/' . $tirea_legal_slug . '.php';
        if (file_exists($tirea_content_file)) {
            include $tirea_content_file;
        }
        ?>

        <?php include $tirea_legal_dir . '/partials/cta-card.php'; ?>

      </article>
    </div>

  </main>
</div>