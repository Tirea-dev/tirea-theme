<?php
/**
 * Template MINI sélecteur de packs Tirea
 * Version page d'accueil
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

// Lien vers la fiche produit complète
$product_url = get_permalink($product_id);

$pack_meta = [
    "L'Essentiel"     => ['detail' => '1 ajusteur · Pour découvrir',      'badge' => null,           'badge_class' => ''],
    "Le Quotidien"    => ['detail' => '2 ajusteurs · Recommandé',         'badge' => 'BEST SELLER',  'badge_class' => ''],
    "L'Indispensable" => ['detail' => '3 ajusteurs · Jamais au dépourvu', 'badge' => '-25€ OFFERTS', 'badge_class' => 'discount'],
];
$default_pack_name = "Le Quotidien";

// Toutes les images (principale + galerie + variations)
$all_images = [['url' => $main_image_url, 'alt' => $product->get_name()]];

// Images de la galerie produit, insérées entre l'image principale et les packs
$tirea_gallery_count = 0;
foreach ($product->get_gallery_image_ids() as $tirea_gallery_id) {
    $tirea_gallery_url = wp_get_attachment_image_url($tirea_gallery_id, 'large');
    if (!$tirea_gallery_url) { continue; }
    $tirea_gallery_alt = get_post_meta($tirea_gallery_id, '_wp_attachment_image_alt', true);
    if ($tirea_gallery_alt === '') { $tirea_gallery_alt = $product->get_name(); }
    $all_images[] = ['url' => $tirea_gallery_url, 'alt' => $tirea_gallery_alt];
    $tirea_gallery_count++;
}
foreach ($variations as $index => $variation) {
    $var_img = !empty($variation['image']['url']) ? $variation['image']['url'] : $main_image_url;
    $pack_name = isset($variation['attributes']['attribute_pack']) ? $variation['attributes']['attribute_pack'] : 'Pack ' . ($index + 1);
    $all_images[] = ['url' => $var_img, 'alt' => $product->get_name() . ' — pack ' . $pack_name];
}
?>

<?php /* ============================================
     MINI SÉLECTEUR DE PACKS (Page d'accueil)
     ============================================ */ ?>
<section class="tirea-product-section tirea-mini-section" data-product-id="<?php echo esc_attr($product_id); ?>">
  <div class="tirea-product-grid">

    <div class="tirea-gallery">
      <div class="tirea-main-image">
        <?php foreach ($all_images as $i => $img): ?>
          <img class="tirea-slide <?php echo $i === 0 ? 'active' : ''; ?>"
               src="<?php echo esc_url($img['url']); ?>"
               alt="<?php echo esc_attr($img['alt']); ?>"
               data-slide-index="<?php echo esc_attr($i); ?>"
               <?php echo $i > 0 ? 'loading="lazy"' : ''; ?>>
        <?php endforeach; ?>

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
      <h2 class="tirea-product-title"><?php echo esc_html($product->get_name()); ?></h2>
      
      <?php // Note SAG : reelle si avis verifies, sinon "Avis a venir" (sans scroll ni "En savoir plus") ?>
      <?php
      $tirea_sel_sag = function_exists('tirea_sag_get_data') ? tirea_sag_get_data() : ['total' => 0, 'average' => 0];
      $tirea_sel_has = !empty($tirea_sel_sag['total']) && (int) $tirea_sel_sag['total'] > 0;
      ?>
      <?php if ($tirea_sel_has):
        $tirea_sel_avg  = (float) $tirea_sel_sag['average'];
        $tirea_sel_fill = max(0, min(100, ($tirea_sel_avg / 5) * 100));
        $tirea_sel_cnt  = (int) $tirea_sel_sag['total'];
      ?>
        <div class="tirea-rating tirea-rating-mini">
          <span class="tirea-stars-precise tirea-stars-small" aria-hidden="true">
            <span class="tirea-stars-bg">★★★★★</span>
            <span class="tirea-stars-fg" style="width: <?php echo esc_attr($tirea_sel_fill); ?>%;">★★★★★</span>
          </span>
          <span class="tirea-rating-value"><?php echo esc_html(number_format($tirea_sel_avg, 1, ',', '')); ?></span>
          <span class="tirea-rating-count">(<?php echo esc_html($tirea_sel_cnt); ?> avis vérifiés)</span>
        </div>
      <?php else: ?>
        <?php // ===== NOTE HISTORIQUE EN DUR (temporaire). A retirer quand Trustpilot renvoie des avis. ===== ?>
        <div class="tirea-rating tirea-rating-mini">
          <span class="tirea-stars-precise tirea-stars-small" aria-hidden="true">
            <span class="tirea-stars-bg">★★★★★</span>
            <span class="tirea-stars-fg" style="width: 96%;">★★★★★</span>
          </span>
          <span class="tirea-rating-value">4,8</span>
        </div>

        <?php // ===== ANCIEN ETAT "AVIS A VENIR" conserve et desactive. Pour revenir dessus : supprimer le bloc 4,8 ci-dessus, puis passer if(false) a if(true). ===== ?>
        <?php if ( false ) : ?>
        <div class="tirea-rating tirea-rating-mini" data-tirea-rating="empty">
          <span class="tirea-rating-stars" aria-hidden="true"><span>★★★★★</span></span>
          <span class="tirea-rating-label">Avis à venir</span>
          <button type="button" class="tirea-rating-help" aria-label="En savoir plus sur nos avis" aria-expanded="false" aria-controls="tireaAvisBubbleMini">?</button>
          <div class="tirea-rating-bubble" id="tireaAvisBubbleMini" role="note" hidden>
            <p class="tirea-rating-bubble-text">Nos avis sont désormais vérifiés par Trustpilot, un organisme tiers indépendant : on repart de zéro pour montrer uniquement du 100% vérifié. En attendant : plus de 1000 commandes et moins de 1% de retour.</p>
          </div>
        </div>
        <?php endif; ?>
      <?php endif; ?>
     
      <div class="tirea-pack-label">Choisissez votre pack</div>

      <div class="tirea-packs">
        <?php foreach ($variations as $index => $variation):
            // Nom littéral de l'attribut "pack" de cette variation (ex. "Le Quotidien")
            $attr_pack = isset($variation['attributes']['attribute_pack']) ? $variation['attributes']['attribute_pack'] : '';

            // Résolution du meta par NOM d'attribut (stable même si l'ordre change dans l'admin)
            if (isset($pack_meta[$attr_pack])) {
                $meta = $pack_meta[$attr_pack];
                $meta['name'] = $attr_pack;
            } else {
                $meta = [
                    'name'        => $attr_pack !== '' ? $attr_pack : 'Pack ' . ($index + 1),
                    'detail'      => '',
                    'badge'       => null,
                    'badge_class' => '',
                ];
            }

            $var_id = $variation['variation_id'];
            $price_html = $variation['display_price'];
            $regular_price = $variation['display_regular_price'];
            $on_sale = $price_html < $regular_price;
            $is_selected = ($attr_pack === $default_pack_name);
            $img_index = $index + 1 + $tirea_gallery_count;
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

      <?php // Bloc stock + réception (ligne) ?>
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

      <?php // Total + CTA ?>
      <div class="tirea-total-card">
        <div class="tirea-cart-error" role="alert" aria-live="polite" hidden></div>
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

      <?php // Paiements acceptés ?>
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
      
      <?php // ===== GARANTIE MEILLEUR PRIX (avant la description) ===== ?>
      <details class="tirea-price-guarantee">
        <summary class="tirea-price-guarantee-summary">
          <span class="tirea-price-guarantee-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
              <path d="M9 12l2 2 4-4"/>
            </svg>
          </span>
          <span class="tirea-price-guarantee-text">
            <strong>Garantie meilleur prix.</strong> Vu moins cher en France ? On vous rembourse la différence.
          </span>
          <span class="tirea-price-guarantee-toggle">Voir les conditions</span>
        </summary>
        <div class="tirea-price-guarantee-conditions">
          <p>Garantie valable sur le produit neuf et identique, vendu par un site marchand professionnel établi en France, en direct sur son propre site officiel (mentions légales et SIRET valides), au prix public affiché TTC, frais de port inclus. Sont exclus le déstockage, les ventes privées, les offres promotionnelles temporaires, ainsi que les produits d'occasion ou reconditionnés. Remboursement de la différence sur présentation d'une preuve datée (lien ou capture) fournie sous 14 jours après la commande.</p>
        </div>
      </details>

      <?php if ($product->get_short_description()): ?>
        <div class="tirea-product-description"><?php echo wp_kses_post(wpautop($product->get_short_description())); ?></div>
      <?php endif; ?>

      <?php // Mini badges réassurance ?>
      <div class="tirea-mini-badges">
        <div class="tirea-mini-badge">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
          </svg>
          Satisfait ou remboursé 30j
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

      <?php // Lien vers fiche complète ?>
      <div class="tirea-full-link">
        <a href="<?php echo esc_url($product_url); ?>">Voir la fiche complète →</a>
      </div>

    </div>
  </div>
</section>