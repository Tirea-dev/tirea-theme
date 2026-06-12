<?php
/**
 * Template Hero Tirea
 * 
 * Rendu via shortcode [tirea_hero] depuis Elementor.
 * Section d'accroche de la page d'accueil avec image de fond,
 * titre, sous-titre, double CTA, preuve sociale et badge flottant.
 * 
 * ⚠️ URLs des images du hero : centralisées dans functions.php > tirea_hero_images()
 * Le preload LCP est injecté dans le <head> via functions.php > tirea_hero_preload().
 */

if (!defined('ABSPATH')) exit;

// ============================================
// CONFIGURATION DU HERO
// Modifie ici pour changer le contenu sans toucher au markup
// (Les URLs des IMAGES sont dans functions.php > tirea_hero_images())
// ============================================

$tirea_hero_badge_text = "LA MARQUE FRANÇAISE QUI REDÉFINIT L'ÉLÉGANCE";

// Le titre est sémantiquement UN SEUL h1 — découpé visuellement via <span>
$tirea_hero_title_line1 = "L'élégance";
$tirea_hero_title_line2 = "Invisible.";

$tirea_hero_subtitle = "L'accessoire indispensable pour une allure irréprochable. Ajustez votre chemise à tout moment, d'un simple geste.";

$tirea_hero_cta_primary = [
    'label' => 'Commander Maintenant',
    'url'   => '/produit/lajusteur-tirea/',
];
$tirea_hero_cta_secondary = [
    'label' => 'Découvrir le concept',
    'url'   => '#mode-emploi',
];

$tirea_hero_image_alt = "L'Ajusteur TIREA porté sur une chemise homme et femme";

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

// Récupération des URLs d'images depuis la source unique
$tirea_hero_imgs = tirea_hero_images();
?>

<?php // Injection des URLs vers le CSS via variables CSS — lecture depuis la source unique ?>
<style>
  .tirea-hero {
    --tirea-hero-bg: url('<?php echo esc_url($tirea_hero_imgs['desktop']); ?>');
    --tirea-hero-bg-mobile: url('<?php echo esc_url($tirea_hero_imgs['mobile']); ?>');
  }
</style>

<section class="tirea-hero" aria-labelledby="tirea-hero-title">

  <?php // Image hero en <img> sémantique cachée (lue par lecteurs d'écran + SEO) ?>
  <?php // Pas de fetchpriority : le preload dans <head> est le seul signal de priorité LCP ?>
  <?php // <picture> : en ≤640px on sert la version mobile — MÊME URL que le fond CSS et le preload mobile, donc réutilisée depuis le cache (aucun téléchargement en plus). Breakpoint aligné sur tirea_hero_preload() + le @media du fond. ?>
  <picture>
    <source media="(max-width: 640px)" srcset="<?php echo esc_url($tirea_hero_imgs['mobile']); ?>">
    <img src="<?php echo esc_url($tirea_hero_imgs['desktop']); ?>"
         alt="<?php echo esc_attr($tirea_hero_image_alt); ?>"
         class="tirea-hero-img-sr"
         width="1920" height="1080">
  </picture>

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
          Plus de <strong><?php echo number_format($tirea_hero_proof_count, 0, ',', ' '); ?> clients</strong> nous ont déjà fait confiance
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