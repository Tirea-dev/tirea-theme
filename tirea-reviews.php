<?php
/**
 * Template — Bloc "Tirea en action" (use cases)
 * Carrousel photos horizontal + roulette use cases verticale + lightbox
 */
if (!defined('ABSPATH')) exit;

// Use cases (modifiables ici)
$tirea_usecases = [
    [
        'badge' => 'EFFET SUR-MESURE',
        'title' => 'Valorisez votre silhouette',
        'text'  => "Plus besoin de passer chez le tailleur pour ajuster vos vêtements. L'ajusteur plaque le tissu au plus près du corps pour un rendu athlétique, propre et moderne de manière instantanée.",
    ],
    [
        'badge' => 'TOUS TEXTILES',
        'title' => 'Compatible avec tout votre vestiaire',
        'text'  => "Que ce soit pour une chemise en lin légère, un t-shirt ou un pull fin, le système d'accroche adhère efficacement à tous les types de tissus sans jamais risquer de les abîmer.",
    ],
    [
        'badge' => 'LE GRAND JOUR',
        'title' => 'Des souvenirs parfaits pour la vie',
        'text'  => "Un costume ou une chemise de marié se doit d'être impeccable. Tirea élimine les plis disgracieux et maintient un cintrage parfait pour des photos souvenirs sans défaut.",
    ],
    [
        'badge' => 'COUPLE PARFAIT',
        'title' => 'Un accessoire pour deux dressings',
        'text'  => "Conçu pour être totalement universel. Le Pack Duo est aussi pensé pour les couples : il permet de cintrer aussi bien les chemises ajustées de monsieur que les hauts de madame.",
    ],
    [
        'badge' => 'IDÉE CADEAU',
        'title' => 'Pour ceux qui ont déjà tout',
        'text'  => "Trouver un cadeau masculin original est un vrai casse-tête. Tirea est la surprise parfaite : un outil pratique, innovant et élégant que l'on est certain de ne pas retrouver en double sous le sapin ou pour un anniversaire.",
    ],
    [
        'badge' => 'PREMIER DATE',
        'title' => 'Commencer sur une note parfaite',
        'text'  => "Soyez totalement libre de vos mouvements pour ce premier tête-à-tête. Que vous vous asseyiez au restaurant ou partagiez une balade, votre tenue reste parfaitement en place. Zéro stress, zéro ajustement, juste vous à votre avantage.",
    ],
    [
        'badge' => 'MODE SOIRÉE',
        'title' => 'Stylé sur la piste de danse',
        'text'  => "Les soirées de gala, les verres en rooftop ou les nuits en club mettent les tenues à rude épreuve. Dansez, levez les bras et bougez librement : votre haut reste ancré dans votre pantalon.",
    ],
    [
        'badge' => 'INVITÉ DE MARIAGE',
        'title' => 'Le plus chic du vin d\'honneur',
        'text'  => "Les mariages durent des heures, entre la chaleur du cocktail et les chorégraphies improvisées. Restez élégant toute la journée sans jamais avoir à vous soucier de replacer votre chemise dans le pantalon toutes les dix minutes.",
    ],
    [
        'badge' => 'GRANDE OCCASION',
        'title' => 'Prêt pour l\'étape supérieure',
        'text'  => "Une étape de vie importante mérite une tenue mémorable. Montez sur l'estrade avec fierté et repartez avec des photos souvenirs parfaites, le buste et la silhouette idéalement mis en valeur.",
    ],
];

// Photos clients (modifiables ici)
$tirea_review_photos = [
    ['url' => 'https://tirea.fr/wp-content/uploads/2026/05/test-avis-5.webp', 'name' => 'Photo 1'],
    ['url' => 'https://tirea.fr/wp-content/uploads/2026/05/test-avis-2.webp', 'name' => 'Photo 2'],
    ['url' => 'https://tirea.fr/wp-content/uploads/2026/05/test-avis-IMG.webp', 'name' => 'Photo 3'],
    ['url' => 'https://tirea.fr/wp-content/uploads/2026/05/test-avis-3.webp', 'name' => 'Photo 4'],
    ['url' => 'https://tirea.fr/wp-content/uploads/2026/05/test-avis-4.webp', 'name' => 'Photo 5'],
    ['url' => 'https://tirea.fr/wp-content/uploads/2026/05/test-avis-6.webp', 'name' => 'Photo 6'],
];

// Note globale (récupérée depuis functions.php)
$tirea_avg_rating = defined('TIREA_GLOBAL_RATING') ? TIREA_GLOBAL_RATING : 4.5;
$tirea_fill_percent = ($tirea_avg_rating / 5) * 100;
$tirea_show_count = defined('TIREA_GLOBAL_SHOW_COUNT') ? TIREA_GLOBAL_SHOW_COUNT : false;
$tirea_total_count = defined('TIREA_GLOBAL_COUNT') ? TIREA_GLOBAL_COUNT : 0;
?>

<!-- ============================================
     SECTION TIREA EN ACTION (use cases)
     ============================================ -->
<section class="tirea-reviews-section" id="tireaReviews">
  <div class="tirea-section-overline">Sur le terrain</div>
  <h2 class="tirea-section-title">En conditions <span class="tirea-accent">réelles</span></h2>

  <!-- Note globale avec dégradé d'étoiles précis -->
  <div class="tirea-reviews-summary">
    <span class="tirea-stars-precise">
      <span class="tirea-stars-bg">★★★★★</span>
      <span class="tirea-stars-fg" style="width: <?php echo esc_attr($tirea_fill_percent); ?>%;">★★★★★</span>
    </span>
    <span class="tirea-reviews-avg"><?php echo number_format($tirea_avg_rating, 1, ',', ''); ?></span>
    <?php if ($tirea_show_count): ?>
      <span class="tirea-reviews-count">· <?php echo $tirea_total_count; ?> avis</span>
    <?php endif; ?>
  </div>

  <div class="tirea-reviews-mention">Score de satisfaction historique · Plus de 1000 produits expédiés</div>
  <div class="tirea-photos-mention">Photos issues de nos réseaux (@Tirea.fr)</div>

  <!-- Carrousel photos -->
  <div class="tirea-photos-carousel" data-paused="false" data-auto="true">
    <div class="tirea-photos-track">
      <?php
      $tirea_photos_loop = array_merge($tirea_review_photos, $tirea_review_photos);
      foreach ($tirea_photos_loop as $tirea_photo): ?>
        <div class="tirea-photo-item">
          <?php if (!empty($tirea_photo['url'])): ?>
            <img src="<?php echo esc_url($tirea_photo['url']); ?>" alt="<?php echo esc_attr($tirea_photo['name']); ?>" loading="lazy">
          <?php else: ?>
            <div class="tirea-photo-placeholder">📷</div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="tirea-photos-fade tirea-photos-fade-left"></div>
    <div class="tirea-photos-fade tirea-photos-fade-right"></div>
  </div>

  <!-- Roulette use cases avec flèches manuelles -->
  <div class="tirea-reviews-roulette" data-paused="false">
    <div class="tirea-reviews-track">
      <?php
      $tirea_usecases_loop = array_merge($tirea_usecases, $tirea_usecases);
      foreach ($tirea_usecases_loop as $tirea_usecase): ?>
        <div class="tirea-usecase-card">
          <div class="tirea-usecase-badge"><?php echo esc_html($tirea_usecase['badge']); ?></div>
          <div class="tirea-usecase-title"><?php echo esc_html($tirea_usecase['title']); ?></div>
          <div class="tirea-usecase-text"><?php echo esc_html($tirea_usecase['text']); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="tirea-reviews-fade tirea-reviews-fade-top"></div>
    <div class="tirea-reviews-fade tirea-reviews-fade-bottom"></div>

    <button class="tirea-reviews-arrow tirea-reviews-arrow-up" aria-label="Précédent">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="18 15 12 9 6 15"/>
      </svg>
    </button>
    <button class="tirea-reviews-arrow tirea-reviews-arrow-down" aria-label="Suivant">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="6 9 12 15 18 9"/>
      </svg>
    </button>
  </div>
</section>

<!-- Lightbox photos -->
<div class="tirea-lightbox" id="tireaLightbox" role="dialog" aria-modal="true" aria-label="Photo agrandie">
  <button type="button" class="tirea-lightbox-close" aria-label="Fermer">×</button>
  <img class="tirea-lightbox-img" src="" alt="">
</div>