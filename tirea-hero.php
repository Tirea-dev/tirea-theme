<?php
/**
 * Template Hero Tirea
 * 
 * Rendu via shortcode [tirea_hero] depuis Elementor.
 * Section d'accroche de la page d'accueil avec image de fond,
 * titre, sous-titre, double CTA, preuve sociale et badge flottant.
 */

if (!defined('ABSPATH')) exit;

// ============================================
// CONFIGURATION DU HERO
// Modifie ici pour changer le contenu sans toucher au markup
// ============================================

$tirea_hero_badge_text = "LA MARQUE FRANÇAISE QUI REDÉFINIT L'ÉLÉGANCE";

// Le titre est sémantiquement UN SEUL h1 — découpé visuellement via <span>
$tirea_hero_title_line1 = "L'élégance";
$tirea_hero_title_line2 = "Invisible.";

$tirea_hero_subtitle = "L'accessoire qui sublime votre silhouette. Gardez votre chemise parfaitement ajustée, toute la journée.";

$tirea_hero_cta_primary = [
    'label' => 'Commander Maintenant',
    'url'   => '/produit/lajusteur-tirea/',
];
$tirea_hero_cta_secondary = [
    'label' => 'Découvrir le concept',
    'url'   => '#mode-emploi',
];

$tirea_hero_image_desktop = 'https://tirea.fr/wp-content/uploads/2026/05/ajusteur-tirea-homme-femme.webp';
$tirea_hero_image_mobile  = 'https://tirea.fr/wp-content/uploads/2026/05/ajusteur-tirea-unisexe.webp';
$tirea_hero_image_alt     = "L'Ajusteur TIREA porté sur une chemise homme et femme";

// Preuve sociale
$tirea_hero_proof_count = 1000;
$tirea_hero_proof_avatars = [
    '/wp-content/uploads/2026/05/proof-5.webp',
    '/wp-content/uploads/2026/05/proof-4.webp',
    '/wp-content/uploads/2026/05/proof-2.webp',
    '/wp-content/uploads/2026/05/proof-1.webp',
];

// Badge flottant
$tirea_hero_card_label = 'RÉSULTAT INSTANTANÉ';
$tirea_hero_card_text  = 'Maintien parfait, même en mouvement.';
?>

<?php // Préload LCP — une balise par breakpoint, le navigateur ne télécharge que celle qui matche ?>
<link rel="preload" as="image"
      href="<?php echo esc_url($tirea_hero_image_desktop); ?>"
      media="(min-width: 641px)"
      fetchpriority="high">
<link rel="preload" as="image"
      href="<?php echo esc_url($tirea_hero_image_mobile); ?>"
      media="(max-width: 640px)"
      fetchpriority="high">

<?php // Injection des URLs CONFIG vers le CSS via variables CSS — une seule source de vérité ?>
<style>
  .tirea-hero {
    --tirea-hero-bg: url('<?php echo esc_url($tirea_hero_image_desktop); ?>');
    --tirea-hero-bg-mobile: url('<?php echo esc_url($tirea_hero_image_mobile); ?>');
  }
</style>

<section class="tirea-hero" aria-labelledby="tirea-hero-title">

  <?php // Image hero en <img> sémantique cachée (lue par lecteurs d'écran + SEO) ?>
  <?php // Pas de fetchpriority : le preload ci-dessus est le seul signal de priorité LCP ?>
  <img src="<?php echo esc_url($tirea_hero_image_desktop); ?>"
       alt="<?php echo esc_attr($tirea_hero_image_alt); ?>"
       class="tirea-hero-img-sr"
       width="1920" height="1080">

  <div class="tirea-hero-content">
    <div class="tirea-hero-text">

      <p class="tirea-badge">
        <span class="tirea-badge-dot" aria-hidden="true"></span>
        <?php echo esc_html($tirea_hero_badge_text); ?>
      </p>

      <?php // Un seul h1 sémantique, découpage visuel via 2 spans ?>
      <h1 id="tirea-hero-title" class="tirea-title">
        <span class="tirea-title-line"><?php echo esc_html($tirea_hero_title_line1); ?></span>
        <span class="tirea-title-line tirea-title-italic"><?php echo esc_html($tirea_hero_title_line2); ?></span>
      </h1>

      <div class="tirea-subtitle">
        <p><?php echo esc_html($tirea_hero_subtitle); ?></p>
      </div>

      <div class="tirea-cta-group">
        <a href="<?php echo esc_url($tirea_hero_cta_primary['url']); ?>"
           class="tirea-btn tirea-btn-primary">
          <?php echo esc_html($tirea_hero_cta_primary['label']); ?>
          <span aria-hidden="true"> →</span>
        </a>
        <a href="<?php echo esc_url($tirea_hero_cta_secondary['url']); ?>"
           class="tirea-btn tirea-btn-secondary">
          <?php echo esc_html($tirea_hero_cta_secondary['label']); ?>
        </a>
      </div>

      <div class="tirea-social-proof">
        <div class="tirea-avatars" aria-hidden="true">
          <?php // alt="" car ces avatars sont décoratifs (pas de vraies photos clients identifiables) ?>
          <?php foreach ($tirea_hero_proof_avatars as $i => $avatar_url): ?>
            <img src="<?php echo esc_url($avatar_url); ?>"
                 alt=""
                 class="tirea-avatar tirea-avatar-<?php echo ($i + 1); ?>"
                 width="36" height="36"
                 loading="lazy">
          <?php endforeach; ?>
        </div>
        <span class="tirea-social-text">
          Plus de <strong><?php echo number_format($tirea_hero_proof_count, 0, ',', ' '); ?> clients</strong> nous font déjà confiance
        </span>
      </div>

    </div>
  </div>

  <?php // Badge flottant — aside car contenu lié mais autonome ?>
  <aside class="tirea-floating-card" aria-label="Garantie produit">
    <p class="tirea-card-header">
      <span class="tirea-card-dot" aria-hidden="true"></span>
      <span class="tirea-card-label"><?php echo esc_html($tirea_hero_card_label); ?></span>
    </p>
    <p class="tirea-card-text"><?php echo esc_html($tirea_hero_card_text); ?></p>
  </aside>

</section>