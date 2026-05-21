<?php
/**
 * Template Ajusteur Tirea
 * 
 * Rendu via shortcode [tirea_ajusteur].
 * Section d'accroche : overline + h2 + sous-titre + animation 2 moitiés + citation + CTA optionnel.
 * Utilisée sur la page d'accueil ET intégrée dans tirea-product-selector.php (sans CTA).
 * 
 * Le CTA s'affiche/se masque via l'attribut shortcode :
 *   [tirea_ajusteur]               → avec CTA  (défaut, page d'accueil)
 *   [tirea_ajusteur show_cta="0"]  → sans CTA  (page produit, depuis tirea-product-selector.php)
 */

if (!defined('ABSPATH')) exit;

// ============================================
// CONFIGURATION
// Modifie ici les textes sans toucher au markup.
// ============================================

$tirea_ajusteur_overline = "L'Ajusteur TIREA™";

$tirea_ajusteur_title    = "Un accessoire indispensable";

// strong = mots-clés mis en avant visuellement
$tirea_ajusteur_subtitle = "Améliore la silhouette de votre chemise avec <strong>un effet cintré</strong> en toute circonstance. Conçu pour être <strong>invisible sous vos vêtements</strong>, <strong>modulable</strong> et s'adapter à <strong>toutes les morphologies</strong>.";

$tirea_ajusteur_image_left  = [
    'url' => 'https://tirea.fr/wp-content/uploads/2026/05/zoom_left-e1778206828884.png',
    'alt' => 'Boucle métallique TIREA',
];
$tirea_ajusteur_image_right = [
    'url' => 'https://tirea.fr/wp-content/uploads/2026/05/zoom_right-e1778206809607.png',
    'alt' => 'Élastique et cuir antidérapant TIREA',
];

// Citation finale — supporte du HTML (strong/em) via wp_kses_post au rendu
$tirea_ajusteur_quote = '<strong>Tension calibrée.</strong> Confort absolu. <strong>Invisible.</strong>';

// CTA (affiché selon $show_cta passé par le shortcode)
$tirea_ajusteur_cta = [
    'label' => 'Découvrir le produit',
    'url'   => '/produit/lajusteur-tirea/',
];

// $show_cta est défini par le wrapper shortcode dans functions.php (défaut: true)
$show_cta = isset($show_cta) ? (bool) $show_cta : true;
?>

<section class="tirea-ajusteur-section" aria-labelledby="tirea-ajusteur-title">

  <?php // Overline — un <p> sémantique avec classe d'accent visuel (pas un h3 car pas un vrai titre) ?>
  <p class="tirea-section-overline"><?php echo esc_html($tirea_ajusteur_overline); ?></p>

  <h2 id="tirea-ajusteur-title" class="tirea-section-title">
    <?php echo esc_html($tirea_ajusteur_title); ?>
  </h2>

  <p class="tirea-section-subtitle">
    <?php echo wp_kses_post($tirea_ajusteur_subtitle); ?>
  </p>

  <?php // Conteneur de l'anim — classe seule (pas d'ID) pour supporter N instances ?>
  <div class="tirea-ajusteur-visual">
    <div class="tirea-ajusteur-half tirea-ajusteur-left">
      <img src="<?php echo esc_url($tirea_ajusteur_image_left['url']); ?>"
           alt="<?php echo esc_attr($tirea_ajusteur_image_left['alt']); ?>"
           loading="lazy"
           decoding="async">
    </div>
    <div class="tirea-ajusteur-half tirea-ajusteur-right">
      <img src="<?php echo esc_url($tirea_ajusteur_image_right['url']); ?>"
           alt="<?php echo esc_attr($tirea_ajusteur_image_right['alt']); ?>"
           loading="lazy"
           decoding="async">
    </div>
  </div>

  <p class="tirea-section-quote">
    <?php echo wp_kses_post($tirea_ajusteur_quote); ?>
  </p>

  <?php if ($show_cta): ?>
    <div class="tirea-ajusteur-cta">
      <a href="<?php echo esc_url($tirea_ajusteur_cta['url']); ?>" class="tirea-ajusteur-btn">
        <?php echo esc_html($tirea_ajusteur_cta['label']); ?>
        <span aria-hidden="true"> →</span>
      </a>
    </div>
  <?php endif; ?>

</section>