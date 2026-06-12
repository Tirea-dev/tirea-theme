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

// Photos (modifiables ici) — le champ "name" sert d'alt (SEO + accessibilité)
$tirea_review_photos = [
    ['url' => 'https://tirea.fr/wp-content/uploads/2026/06/insta-5.webp', 'name' => "Client portant l'Ajusteur Tirea™"],
    ['url' => 'https://tirea.fr/wp-content/uploads/2026/06/insta-2.webp', 'name' => "L'Ajusteur Tirea™ porté en conditions réelles"],
    ['url' => 'https://tirea.fr/wp-content/uploads/2026/06/insta-1.webp', 'name' => "Tenue ajustée grâce à l'Ajusteur Tirea™"],
    ['url' => 'https://tirea.fr/wp-content/uploads/2026/06/insta-3.webp', 'name' => "L'Ajusteur Tirea™ en situation, photo client"],
    ['url' => 'https://tirea.fr/wp-content/uploads/2026/06/insta-4.webp', 'name' => "Silhouette ajustée avec l'Ajusteur Tirea™"],
    ['url' => 'https://tirea.fr/wp-content/uploads/2026/06/insta-6.webp', 'name' => "L'Ajusteur Tirea™ utilisé au quotidien"],
];
?>

<?php /* ============================================
     SECTION TIREA EN ACTION (use cases)
     ============================================ */ ?>
<section class="tirea-reviews-section" id="tireaReviews">
  <div class="tirea-section-overline">Sur le terrain</div>
  <h2 class="tirea-section-title">En conditions <span class="tirea-accent">réelles</span></h2>

  <?php // ===== ZONE AVIS : avis reels SAG si presents, sinon etat "Avis a venir" ===== ?>
  <?php
  $tirea_sag = function_exists('tirea_sag_get_data') ? tirea_sag_get_data() : ['total' => 0, 'average' => 0, 'distribution' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0], 'reviews' => []];
  $tirea_has_reviews = !empty($tirea_sag['total']) && (int) $tirea_sag['total'] > 0;
  ?>
  <?php if ($tirea_has_reviews):
    $tirea_avg     = (float) $tirea_sag['average'];
    $tirea_cnt     = (int) $tirea_sag['total'];
    $tirea_fill    = max(0, min(100, ($tirea_avg / 5) * 100));
    $tirea_dist    = $tirea_sag['distribution'];
    $tirea_initial = 8;
    $tirea_max     = defined('TIREA_SAG_MAX_DISPLAY') ? TIREA_SAG_MAX_DISPLAY : 30;
    $tirea_list    = array_slice($tirea_sag['reviews'], 0, $tirea_max);
  ?>
  <section id="avis-tirea" class="tirea-avis-real" aria-labelledby="tireaAvisRealLabel">
    <h3 id="tireaAvisRealLabel" class="tirea-avis-real-title">Avis clients <span class="tirea-accent">vérifiés</span></h3>

    <div class="tirea-avis-summary">
      <div class="tirea-avis-summary-score">
        <span class="tirea-avis-summary-avg"><?php echo esc_html(number_format($tirea_avg, 1, ',', '')); ?></span>
        <span class="tirea-avis-summary-out">/5</span>
        <span class="tirea-stars-precise" aria-hidden="true">
          <span class="tirea-stars-bg">★★★★★</span>
          <span class="tirea-stars-fg" style="width: <?php echo esc_attr($tirea_fill); ?>%;">★★★★★</span>
        </span>
        <span class="tirea-avis-summary-count"><?php echo esc_html(sprintf('%d avis vérifiés', $tirea_cnt)); ?></span>
      </div>
      <div class="tirea-avis-summary-bars">
        <?php foreach ($tirea_dist as $tirea_star => $tirea_n):
          $tirea_bar = $tirea_cnt > 0 ? round(($tirea_n / $tirea_cnt) * 100) : 0; ?>
          <div class="tirea-avis-bar-row">
            <span class="tirea-avis-bar-label"><?php echo esc_html($tirea_star); ?>★</span>
            <span class="tirea-avis-bar"><span class="tirea-avis-bar-fill" style="width: <?php echo esc_attr($tirea_bar); ?>%;"></span></span>
            <span class="tirea-avis-bar-n"><?php echo esc_html($tirea_n); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <ul class="tirea-avis-list" data-initial="<?php echo esc_attr($tirea_initial); ?>">
      <?php foreach ($tirea_list as $tirea_i => $tirea_rv):
        $tirea_rfill = max(0, min(100, (((int) $tirea_rv['rate']) / 5) * 100));
        $tirea_is_hidden = $tirea_i >= $tirea_initial; ?>
        <li class="tirea-avis-card"<?php echo $tirea_is_hidden ? ' hidden' : ''; ?>>
          <div class="tirea-avis-card-head">
            <span class="tirea-avis-card-name"><?php echo esc_html($tirea_rv['name'] !== '' ? $tirea_rv['name'] : 'Client vérifié'); ?></span>
            <span class="tirea-stars-precise tirea-stars-small" aria-hidden="true">
              <span class="tirea-stars-bg">★★★★★</span>
              <span class="tirea-stars-fg" style="width: <?php echo esc_attr($tirea_rfill); ?>%;">★★★★★</span>
            </span>
          </div>
          <?php if (!empty($tirea_rv['date'])): ?>
            <span class="tirea-avis-card-date"><?php echo esc_html(tirea_sag_format_date($tirea_rv['date'])); ?></span>
          <?php endif; ?>
          <?php if (!empty($tirea_rv['text'])): ?>
            <p class="tirea-avis-card-text"><?php echo esc_html($tirea_rv['text']); ?></p>
          <?php endif; ?>
          <?php if (!empty($tirea_rv['reply'])): ?>
            <div class="tirea-avis-card-reply">
              <span class="tirea-avis-card-reply-label">Réponse de Tirea</span>
              <p class="tirea-avis-card-reply-text"><?php echo esc_html($tirea_rv['reply']); ?></p>
            </div>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <?php if (count($tirea_list) > $tirea_initial): ?>
      <button type="button" class="tirea-avis-more" aria-expanded="false">Voir plus d'avis</button>
    <?php endif; ?>

    <p class="tirea-avis-source">Avis collectés, vérifiés et publiés par un organisme tiers indépendant (Société des Avis Garantis).</p>
  </section>
  <?php else: ?>
  <section id="avis-tirea" class="tirea-avis-explain" aria-labelledby="tireaAvisExplainLabel">
    <span class="tirea-avis-explain-stars" aria-hidden="true"><span>★★★★★</span></span>
    <p id="tireaAvisExplainLabel" class="tirea-avis-explain-overline">Avis à venir, 100% vérifiés</p>
    <p class="tirea-avis-explain-text">Tirea, c'est pas nouveau. Lancée en 2019, la marque a expédié plus de 1000 commandes avec moins de 1% de retour. On relance aujourd'hui la boutique officielle, et on a fait un choix simple sur les avis : on les confie à un organisme tiers français indépendant qui ne publie que des avis d'acheteurs vérifiés. Chaque avis vient d'un client ayant réellement commandé, vérifié et contrôlé en dehors de chez nous, pour une information la plus objective possible.</p>
  </section>
  <?php endif; ?>

  <div class="tirea-photos-mention">Photos issues de nos réseaux (@Tirea.fr)</div>

  <?php // Carrousel photos ?>
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

  <?php // Roulette use cases avec flèches manuelles (sorties sous le cadre) ?>
  <div class="tirea-reviews-roulette-wrap">
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
    </div>

    <div class="tirea-reviews-controls">
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
  </div>
</section>

<?php // Lightbox photos ?>
<div class="tirea-lightbox" id="tireaLightbox" role="dialog" aria-modal="true" aria-label="Photo agrandie">
  <button type="button" class="tirea-lightbox-close" aria-label="Fermer">×</button>
  <img class="tirea-lightbox-img" src="" alt="">
</div>