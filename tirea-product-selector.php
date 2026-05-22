<?php
/**
 * Template fiche produit Tirea — version complète
 */
if (!defined('ABSPATH')) exit;

$product = $tirea_product;
$product_id = $product->get_id();
$variations = $product->get_available_variations();

// Calcule le stock total disponible
$total_stock = 0;
$has_stock_management = false;
foreach ($variations as $variation) {
    $var_obj = wc_get_product($variation['variation_id']);
    if ($var_obj && $var_obj->managing_stock()) {
        $has_stock_management = true;
        $total_stock += max(0, intval($var_obj->get_stock_quantity()));
    }
}

$main_image_id = $product->get_image_id();
$main_image_url = $main_image_id ? wp_get_attachment_image_url($main_image_id, 'large') : wc_placeholder_img_src('large');

// Métadonnées packs
$pack_meta = [
    0 => ['name' => "L'Essentiel",      'detail' => '1 ajusteur · Pour découvrir',      'badge' => null,            'badge_class' => ''],
    1 => ['name' => "Le Quotidien",     'detail' => '2 ajusteurs · Recommandé',         'badge' => 'BEST SELLER',   'badge_class' => ''],
    2 => ['name' => "L'Indispensable",  'detail' => '3 ajusteurs · Jamais au dépourvu', 'badge' => '-25€ OFFERTS',  'badge_class' => 'discount'],
];
$default_index = 1;

// Construction du tableau de toutes les images (principale + variations)
$all_images = [['url' => $main_image_url, 'alt' => $product->get_name()]];
foreach ($variations as $index => $variation) {
    $var_img = !empty($variation['image']['url']) ? $variation['image']['url'] : $main_image_url;
    $all_images[] = ['url' => $var_img, 'alt' => 'Pack ' . ($index + 1)];
}

// Composants (matériaux)
$components = [
    ['img' => 'https://tirea.fr/wp-content/uploads/2026/05/ajusteur-blank.png',  'name' => 'Ajusteur',         'detail' => 'en acier'],
    ['img' => 'https://tirea.fr/wp-content/uploads/2026/05/elastique-blank.png', 'name' => 'Élastique',        'detail' => 'gros grain'],
    ['img' => 'https://tirea.fr/wp-content/uploads/2026/05/cuir-blank.png',      'name' => 'Cuir skyvertex',   'detail' => 'antidérapant'],
    ['img' => 'https://tirea.fr/wp-content/uploads/2026/05/boucle-blank.png',    'name' => 'Boucle',           'detail' => 'en acier'],
    ['img' => 'https://tirea.fr/wp-content/uploads/2026/05/accroche-blank.png',  'name' => 'Accroche',         'detail' => 'en cuir'],
];

// Étapes guide
$steps = [
    ['num' => 'I',   'title' => 'Ancrage à la chemise',     'img' => 'https://tirea.fr/wp-content/uploads/2026/05/etape1_enfilez.jpg',   'gif' => 'https://tirea.fr/wp-content/uploads/2026/05/IMG_3069.gif', 'action' => 'Insérez',  'text' => "l'accroche dans <strong>le dernier bouton</strong>."],
    ['num' => 'II',  'title' => 'Verrouillage de l\'ensemble','img' => 'https://tirea.fr/wp-content/uploads/2026/05/etape2_refermez.jpg', 'gif' => 'https://tirea.fr/wp-content/uploads/2026/05/IMG_3070.gif', 'action' => 'Insérez',  'text' => "la boucle dans <strong>l'accroche en acier</strong>."],
    ['num' => 'III', 'title' => 'Ajustement de la tension', 'img' => 'https://tirea.fr/wp-content/uploads/2026/05/etape3_ajustez.jpg',   'gif' => 'https://tirea.fr/wp-content/uploads/2026/05/IMG_3071.gif', 'action' => 'Resserrez','text' => "l'ajusteur de taille pour <strong>une efficacité maximale</strong>."],
];
?>

<!-- ============================================
     SECTION 1 — SÉLECTEUR DE PACKS
     ============================================ -->
<section class="tirea-product-section" data-product-id="<?php echo esc_attr($product_id); ?>">
  <div class="tirea-product-grid">

    <div class="tirea-gallery">
      <div class="tirea-main-image">
        <!-- Toutes les images superposées (fade) -->
        <?php foreach ($all_images as $i => $img): ?>
          <img class="tirea-slide <?php echo $i === 0 ? 'active' : ''; ?>"
               src="<?php echo esc_url($img['url']); ?>"
               alt="<?php echo esc_attr($img['alt']); ?>"
               data-slide-index="<?php echo esc_attr($i); ?>"
               <?php echo $i > 0 ? 'loading="lazy"' : ''; ?>>
        <?php endforeach; ?>

        <!-- Flèches navigation (desktop) -->
        <button class="tirea-slider-arrow tirea-slider-prev" aria-label="Image précédente">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
          </svg>
        </button>
        <button class="tirea-slider-arrow tirea-slider-next" aria-label="Image suivante">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </button>
      </div>

      <div class="tirea-thumbnails">
        <?php foreach ($all_images as $i => $img): ?>
          <div class="tirea-thumbnail <?php echo $i === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo esc_attr($i); ?>">
            <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy">
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="tirea-product-info">
      <h1 class="tirea-product-title"><?php echo esc_html($product->get_name()); ?></h1>
      
      <!-- Note globale avec étoiles précises (pilotée depuis functions.php) -->
      <?php
      $tirea_sel_avg = defined('TIREA_GLOBAL_RATING') ? TIREA_GLOBAL_RATING : 4.5;
      $tirea_sel_fill = ($tirea_sel_avg / 5) * 100;
      $tirea_sel_show_count = defined('TIREA_GLOBAL_SHOW_COUNT') ? TIREA_GLOBAL_SHOW_COUNT : false;
      $tirea_sel_count = defined('TIREA_GLOBAL_COUNT') ? TIREA_GLOBAL_COUNT : 0;
      ?>
      <a href="#tireaReviews" class="tirea-rating-link">
        <span class="tirea-stars-precise tirea-stars-small">
          <span class="tirea-stars-bg">★★★★★</span>
          <span class="tirea-stars-fg" style="width: <?php echo esc_attr($tirea_sel_fill); ?>%;">★★★★★</span>
        </span>
        <span class="tirea-rating-value"><?php echo number_format($tirea_sel_avg, 1, ',', ''); ?></span>
        <?php if ($tirea_sel_show_count): ?>
          <span class="tirea-rating-count">(<?php echo $tirea_sel_count; ?> avis)</span>
        <?php endif; ?>
      </a>

      <?php if ($product->get_short_description()): ?>
        <div class="tirea-product-description"><?php echo wp_kses_post(wpautop($product->get_short_description())); ?></div>
      <?php endif; ?>

      <div class="tirea-pack-label">Choisissez votre pack</div>

      <div class="tirea-packs">
        <?php foreach ($variations as $index => $variation):
            $meta = isset($pack_meta[$index]) ? $pack_meta[$index] : ['name' => 'Pack ' . ($index+1), 'detail' => '', 'badge' => null, 'badge_class' => ''];
            $var_id = $variation['variation_id'];
            $price_html = $variation['display_price'];
            $regular_price = $variation['display_regular_price'];
            $on_sale = $price_html < $regular_price;
            $is_selected = ($index === $default_index);
            $var_img = !empty($variation['image']['url']) ? $variation['image']['url'] : $main_image_url;
            // L'index de l'image associée au pack dans le slider (index 0 = principale, donc packs commencent à 1)
            $img_index = $index + 1;
        ?>
          <div class="tirea-pack <?php echo $is_selected ? 'selected' : ''; ?>"
               data-variation-id="<?php echo esc_attr($var_id); ?>"
               data-price="<?php echo esc_attr($price_html); ?>"
               data-slide-index="<?php echo esc_attr($img_index); ?>">
            <?php if ($meta['badge']): ?>
              <div class="tirea-pack-badge <?php echo esc_attr($meta['badge_class']); ?>"><?php echo esc_html($meta['badge']); ?></div>
            <?php endif; ?>
            <div class="tirea-pack-radio"></div>
            <div class="tirea-pack-content">
              <div class="tirea-pack-info">
                <div class="tirea-pack-name"><?php echo esc_html($meta['name']); ?></div>
                <div class="tirea-pack-detail"><?php echo esc_html($meta['detail']); ?></div>
              </div>
              <div class="tirea-pack-pricing">
                <div class="tirea-pack-price"><?php echo wc_price($price_html); ?></div>
                <?php if ($on_sale): ?>
                  <div class="tirea-pack-old-price"><?php echo wc_price($regular_price); ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Bloc stock + réception (ligne) -->
      <div class="tirea-info-row">
        <?php if ($has_stock_management): ?>
        <div class="tirea-stock-indicator <?php echo $total_stock <= 0 ? 'out-of-stock' : ''; ?>">
          <span class="tirea-stock-dot"></span>
          <span class="tirea-stock-text">
            <?php if ($total_stock > 0): ?>
              <strong>En stock</strong><span class="tirea-shipping-inline" style="display: none;">, expédition dans <strong class="tirea-timer-value">--</strong></span>
            <?php else: ?>
              <strong>Rupture</strong>
            <?php endif; ?>
          </span>
        </div>
        <?php endif; ?>

        <div class="tirea-reception">
          <span class="tirea-reception-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
          </span>
          <span class="tirea-reception-text">
            Réception estimée : <strong class="tirea-reception-value">—</strong>
          </span>
        </div>
      </div>

      <!-- Total + CTA -->
      <div class="tirea-total-card">
        <div class="tirea-total-row">
          <span class="tirea-total-label">Total à payer</span>
          <div class="tirea-total-amount">
            <div class="tirea-total-price"></div>
            <span class="tirea-total-shipping">Livraison suivie offerte</span>
          </div>
        </div>
        <button class="tirea-cta-btn">
          <span class="tirea-cta-text">Ajouter au panier</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"/>
            <polyline points="12 5 19 12 12 19"/>
          </svg>
        </button>
      </div>

      <!-- Paiements acceptés -->
      <div class="tirea-payments">
        <div class="tirea-payments-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
          Paiement 100% sécurisé
        </div>
        <div class="tirea-payments-logos">
          <span class="tirea-pay-logo" title="Visa">
            <img src="https://tirea.fr/wp-content/uploads/2026/05/Visa_Inc._logo_2021–present.svg" alt="Visa" loading="lazy">
          </span>
          <span class="tirea-pay-logo" title="Mastercard">
            <img src="https://tirea.fr/wp-content/uploads/2026/05/Mastercard-logo.svg" alt="Mastercard" loading="lazy">
          </span>
          <span class="tirea-pay-logo" title="PayPal">
            <img src="https://tirea.fr/wp-content/uploads/2026/05/PayPal_logo.svg" alt="PayPal" loading="lazy">
          </span>
          <span class="tirea-pay-logo" title="Apple Pay">
            <img src="https://tirea.fr/wp-content/uploads/2026/05/Apple_Pay_logo.svg" alt="Apple Pay" loading="lazy">
          </span>
          <span class="tirea-pay-logo" title="Google Pay">
            <img src="https://tirea.fr/wp-content/uploads/2026/05/Google_Pay_Logo.svg" alt="Google Pay" loading="lazy">
          </span>
          <span class="tirea-pay-logo tirea-pay-more" title="Et plus">+</span>
        </div>
      </div>

      <!-- Mini badges réassurance -->
      <div class="tirea-mini-badges">
        <div class="tirea-mini-badge">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
          </svg>
          Satisfait ou remboursé
        </div>
        <div class="tirea-mini-badge">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
            <path d="M15 18H9"/>
            <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/>
            <circle cx="17" cy="18" r="2"/>
            <circle cx="7" cy="18" r="2"/>
          </svg>
          Expédition sous 24h
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================
     SECTION 2 — AJUSTEUR (factorisé via shortcode)
     ============================================ -->
<?php echo do_shortcode('[tirea_ajusteur show_cta="0"]'); ?>

<!-- ============================================
     SECTION 3 — COMPOSANTS
     ============================================ -->
<section class="tirea-components-section">
  <div class="tirea-section-overline">Zoom sur les composants</div>
  <h2 class="tirea-section-title">Conçu pour durer</h2>

  <div class="tirea-components-grid">
    <?php foreach ($components as $comp): ?>
      <div class="tirea-component">
        <div class="tirea-component-img">
          <img src="<?php echo esc_url($comp['img']); ?>" alt="<?php echo esc_attr($comp['name']); ?>" loading="lazy">
        </div>
        <div class="tirea-component-name"><?php echo esc_html($comp['name']); ?></div>
        <div class="tirea-component-detail"><?php echo esc_html($comp['detail']); ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php // ===== SECTION 4 — RÉSULTAT IMMÉDIAT (factorisé via shortcode) ===== ?>
<?php echo do_shortcode('[tirea_result]'); ?>

<?php // ===== SECTIONS 5 & 6 — GUIDE + RÉSULTAT "C'EST PRÊT" (factorisé via shortcode) ===== ?>
<?php echo do_shortcode('[tirea_guide variant="full"]'); ?>

<!-- ============================================
     SECTION 7 — PHOTO LIFESTYLE FINALE + CTA
     ============================================ -->
<section class="tirea-lifestyle-section">
  <div class="tirea-lifestyle-image">
    <img src="https://tirea.fr/wp-content/uploads/2026/05/ajusteur-tirea-homme-femme.webp" alt="Une silhouette parfaite pour tous" loading="lazy">
  </div>
  <div class="tirea-lifestyle-content">
    <div class="tirea-section-overline">Pour elle. Pour lui.</div>
    <h2 class="tirea-section-title">Une silhouette <span class="tirea-accent">parfaite</span>, pour tous.</h2>
    <p class="tirea-lifestyle-text">
      Conçu pour <strong>toutes les morphologies</strong>, l'Ajusteur Tirea™ s'adapte aussi bien aux chemises masculines qu'aux chemisiers féminins. <strong>Une solution universelle</strong> pour une élégance sans compromis.
    </p>
    <div class="tirea-final-cta">
      <a href="#" class="tirea-final-btn" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
        Choisir mon pack
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="5" y1="12" x2="19" y2="12"/>
          <polyline points="12 5 19 12 12 19"/>
        </svg>
      </a>
    </div>
  </div>
</section>

<!-- ============================================
     SECTION 8 — AVIS CLIENTS
     ============================================ -->
<?php echo do_shortcode('[tirea_reviews]'); ?>