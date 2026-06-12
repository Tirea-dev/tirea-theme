<?php
/**
 * Template Résultat Tirea
 * 
 * Rendu via shortcode [tirea_result].
 * Bloc "Résultat instantané" avec slider avant/après interactif.
 * Utilisé sur la page d'accueil ET dans tirea-product-selector.php (Section 4).
 * 
 * CSS principal dans tirea-product.css (styles communs).
 * CSS spécifique du slider dans tirea-result.css.
 */

if (!defined('ABSPATH')) exit;

// ============================================
// CONFIGURATION
// Modifie ici pour changer le contenu sans toucher au markup
// ============================================

$tirea_result_overline = "Un résultat instantané";
$tirea_result_title    = 'Une efficacité <span class="tirea-accent">indiscutable</span>';
$tirea_result_subtitle = "L'élasticité durable et l'adhérence continue : deux atouts qui permettent de garantir un <strong>maintien discret, sans effort</strong>.";

$tirea_result_image_before = [
    'url' => 'https://tirea.fr/wp-content/uploads/2026/06/sans-ajusteur-tirea.webp',
    'alt' => 'Sans l\'ajusteur TIREA',
];

$tirea_result_image_after = [
    'url' => 'https://tirea.fr/wp-content/uploads/2026/06/avec-ajusteur-tirea.webp',
    'alt' => 'Avec l\'ajusteur TIREA',
];

$tirea_result_label_before = "Sans Tirea";
$tirea_result_label_after  = "Avec Tirea";

$tirea_result_quote = "<em>C'est l'accessoire qu'on remarque…</em> uniquement quand il manque.";
?>

<section class="tirea-result-section" aria-labelledby="tirea-result-title">
  <p class="tirea-section-overline"><?php echo esc_html($tirea_result_overline); ?></p>
  <h2 id="tirea-result-title" class="tirea-section-title">
    <?php echo wp_kses_post($tirea_result_title); ?>
  </h2>
  <p class="tirea-section-subtitle">
    <?php echo wp_kses_post($tirea_result_subtitle); ?>
  </p>

  <div class="tirea-result-wrapper">
    <div class="tirea-result-labels">
      <?php // Pastille AVEC (bleue) à gauche, pastille SANS (grise) à droite ?>
      <div class="tirea-result-label after"><?php echo esc_html($tirea_result_label_after); ?></div>
      <div class="tirea-result-label before"><?php echo esc_html($tirea_result_label_before); ?></div>
    </div>

    <div class="tirea-result-image">
      <?php // Slider avant/après interactif ?>
      <div class="tirea-result-slider"
           role="slider"
           tabindex="0"
           aria-label="Curseur avant / après TIREA"
           aria-valuemin="0"
           aria-valuemax="100"
           aria-valuenow="50">

        <?php // Image SANS (fond, visible à droite). Classe -after conservée pour le mécanisme du slider. ?>
        <img class="tirea-result-img tirea-result-img-after"
             src="<?php echo esc_url($tirea_result_image_before['url']); ?>"
             alt="<?php echo esc_attr($tirea_result_image_before['alt']); ?>"
             loading="lazy"
             decoding="async">

        <?php // Image AVEC (overlay, clippée par le curseur, visible à gauche). Classes -before conservées pour le mécanisme du slider. ?>
        <div class="tirea-result-before-wrap" aria-hidden="true">
          <img class="tirea-result-img tirea-result-img-before"
               src="<?php echo esc_url($tirea_result_image_after['url']); ?>"
               alt=""
               loading="lazy"
               decoding="async">
        </div>

        <?php // Poignée du curseur ?>
        <div class="tirea-result-handle" aria-hidden="true">
          <span class="tirea-result-handle-circle">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="9 6 3 12 9 18"></polyline>
              <polyline points="15 6 21 12 15 18"></polyline>
            </svg>
          </span>
        </div>
      </div>
    </div>
  </div>

  <p class="tirea-section-quote">
    <?php echo wp_kses_post($tirea_result_quote); ?>
  </p>
</section>