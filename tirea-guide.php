<?php
/**
 * Template Guide d'utilisation Tirea
 *
 * Rendu via shortcode [tirea_guide variant="full|light"].
 * - variant="full"  : fiche produit (étapes I/II/III détaillées + GIF animés + textes techniques).
 * - variant="light" : page d'accueil (étapes I/II/III, version teasing, sans GIF, textes courts).
 *
 * Inclut le bloc final "C'est prêt" (flèche ↓ + image circulaire bordée de bleu).
 *
 * CSS dans tirea-guide.css (deux skins : .tirea-guide--full / .tirea-guide--light).
 * Variable $tirea_guide_variant transmise par le shortcode (functions.php).
 */

if (!defined('ABSPATH')) exit;

// ============================================
// CONFIGURATION
// Modifie ici pour changer le contenu sans toucher au markup
// ============================================

// Variante de rendu (transmise par le shortcode, "full" par défaut)
$tirea_guide_variant = (isset($tirea_guide_variant) && $tirea_guide_variant === 'light') ? 'light' : 'full';

// --- Textes d'en-tête selon la variante ---
$tirea_guide_headers = [
    'full' => [
        'overline' => "Le guide d'utilisation",
        'title'    => 'Simple comme bonjour',
        'subtitle' => 'Une <strong>simplicité imbattable</strong>. En quelques secondes, votre silhouette est parfaitement ajustée.',
    ],
    'light' => [
        'overline' => "Comment ça marche",
        'title'    => 'Simple comme bonjour',
        'subtitle' => "L'élégance ne devrait jamais être compliquée. En quelques secondes, votre silhouette est parfaitement ajustée.",
    ],
];

// --- Étapes selon la variante ---
// 'gif' n'est utilisé qu'en variante full.
$tirea_guide_steps = [
    'full' => [
        ['label' => 'Étape I',   'title' => 'Ancrez',      'img' => 'https://tirea.fr/wp-content/uploads/2026/05/etape1_enfilez.jpg',  'gif' => 'https://tirea.fr/wp-content/uploads/2026/05/IMG_3069.gif', 'action' => 'Insérez',   'text' => "l'accroche dans <strong>le dernier bouton</strong> de la chemise."],
        ['label' => 'Étape II',  'title' => "Verrouillez", 'img' => 'https://tirea.fr/wp-content/uploads/2026/05/etape2_refermez.jpg', 'gif' => 'https://tirea.fr/wp-content/uploads/2026/05/IMG_3070.gif', 'action' => 'Insérez',   'text' => "la boucle dans <strong>l'accroche en acier</strong>."],
        ['label' => 'Étape III', 'title' => 'Ajustez',   'img' => 'https://tirea.fr/wp-content/uploads/2026/05/etape3_ajustez.jpg',  'gif' => 'https://tirea.fr/wp-content/uploads/2026/05/IMG_3071.gif', 'action' => 'Adaptez', 'text' => "à votre taille pour <strong>une efficacité maximale</strong>."],
    ],
    'light' => [
        ['label' => 'Étape I',   'title' => 'Ancrez',  'img' => 'https://tirea.fr/wp-content/uploads/2026/05/etape1_enfilez.jpg',  'gif' => '', 'action' => '', 'text' => ''],
        ['label' => 'Étape II',  'title' => 'Verrouillez', 'img' => 'https://tirea.fr/wp-content/uploads/2026/05/etape2_refermez.jpg', 'gif' => '', 'action' => '', 'text' => ''],
        ['label' => 'Étape III', 'title' => 'Ajustez',  'img' => 'https://tirea.fr/wp-content/uploads/2026/05/etape3_ajustez.jpg',  'gif' => '', 'action' => '', 'text' => ''],
    ],
];

// --- Bloc final "C'est prêt" selon la variante ---
$tirea_guide_result = [
    'full' => [
        'label'    => 'Résultat',
        'title'    => "C'est prêt.",
        'img'      => 'https://tirea.fr/wp-content/uploads/2026/05/resultat.jpg',
        'subtitle' => 'Une chemise <strong>impeccable au quotidien</strong>.<br>Ne laissez plus jamais la place au hasard.',
        'tagline'  => "L'essayer, c'est l'adopter.",
    ],
    'light' => [
        'label'    => 'Résultat',
        'title'    => "C'est prêt.",
        'img'      => 'https://tirea.fr/wp-content/uploads/2026/05/resultat.jpg',
        'subtitle' => 'Une silhouette ajustée, toute la journée, sans même y penser.',
        'tagline'  => '',
    ],
];

// Résolution des données pour la variante active
$tirea_g_head   = $tirea_guide_headers[$tirea_guide_variant];
$tirea_g_steps  = $tirea_guide_steps[$tirea_guide_variant];
$tirea_g_result = $tirea_guide_result[$tirea_guide_variant];
$tirea_g_is_full = ($tirea_guide_variant === 'full');
?>

<section class="tirea-guide-section tirea-guide--<?php echo esc_attr($tirea_guide_variant); ?>" aria-labelledby="tirea-guide-title">

  <?php // ===== En-tête ===== ?>
  <p class="tirea-section-overline"><?php echo esc_html($tirea_g_head['overline']); ?></p>
  <h2 id="tirea-guide-title" class="tirea-section-title"><?php echo wp_kses_post($tirea_g_head['title']); ?></h2>
  <p class="tirea-section-subtitle"><?php echo wp_kses_post($tirea_g_head['subtitle']); ?></p>

  <?php // ===== Les 3 étapes (pointillé qui les relie via ::before) ===== ?>
  <div class="tirea-guide-steps">
    <?php foreach ($tirea_g_steps as $step): ?>
      <div class="tirea-guide-step">
        <div class="tirea-guide-step-illustration">
          <img src="<?php echo esc_url($step['img']); ?>" alt="<?php echo esc_attr($step['title']); ?>" loading="lazy" decoding="async">
        </div>

        <?php if ($tirea_g_is_full && !empty($step['gif'])): ?>
          <div class="tirea-guide-step-gif">
            <img src="<?php echo esc_url($step['gif']); ?>" alt="Animation : <?php echo esc_attr($step['title']); ?>" loading="lazy" decoding="async">
          </div>
        <?php endif; ?>

        <div class="tirea-guide-step-label"><?php echo esc_html($step['label']); ?></div>
        <h3 class="tirea-guide-step-title"><?php echo esc_html($step['title']); ?></h3>

        <?php if ($tirea_g_is_full && !empty($step['text'])): ?>
          <p class="tirea-guide-step-text"><span class="tirea-accent"><?php echo esc_html($step['action']); ?></span> <?php echo wp_kses_post($step['text']); ?></p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <?php // ===== Bloc final "C'est prêt" (flèche ↓ + cercle bleu) ===== ?>
  <div class="tirea-guide-result">
    <div class="tirea-guide-arrow" aria-hidden="true"></div>

    <p class="tirea-guide-result-label"><?php echo esc_html($tirea_g_result['label']); ?></p>
    <h3 class="tirea-guide-result-title"><?php echo wp_kses_post($tirea_g_result['title']); ?></h3>

    <div class="tirea-guide-result-image">
      <img src="<?php echo esc_url($tirea_g_result['img']); ?>" alt="Résultat final" loading="lazy" decoding="async">
      <span class="tirea-guide-result-badge" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
      </span>
    </div>

    <p class="tirea-guide-result-subtitle"><?php echo wp_kses_post($tirea_g_result['subtitle']); ?></p>

    <?php if (!empty($tirea_g_result['tagline'])): ?>
      <div class="tirea-guide-result-tagline"><?php echo esc_html($tirea_g_result['tagline']); ?></div>
    <?php endif; ?>
  </div>

</section>