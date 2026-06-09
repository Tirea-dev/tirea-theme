<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'hello-elementor','hello-elementor-theme-style','hello-elementor-header-footer' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

/**
 * NOTE GLOBALE TIREA — modifiable ici, se répercute partout
 */
if (!defined('TIREA_GLOBAL_RATING')) {
    define('TIREA_GLOBAL_RATING', 4.5);
    define('TIREA_GLOBAL_SHOW_COUNT', false);
    define('TIREA_GLOBAL_COUNT', 0);
}

/**
 * AVIS VERIFIES : Societe des Avis Garantis (API publique, sans cle privee).
 * Recupere note + avis, met en cache (transient) pour ne pas appeler l'API a chaque vue.
 * API en panne OU zero avis => etat vide conserve, aucun AggregateRating emis (jamais de fausse note).
 */
if (!defined('TIREA_SAG_PUBLIC_KEY')) {
    define('TIREA_SAG_PUBLIC_KEY', 'beae07cf5d99603174a3236c03bb4708');
}
if (!defined('TIREA_SAG_SCOPE')) {
    // Mono-produit : 'site' = avis collectes sur la boutique (= avis du produit).
    // Passe a l'ID produit (ex. '758') si tu actives la collecte par produit chez SAG.
    define('TIREA_SAG_SCOPE', 'site');
}
if (!defined('TIREA_SAG_CACHE')) {
    define('TIREA_SAG_CACHE', 12 * HOUR_IN_SECONDS); // duree du cache quand il y a des avis
}
if (!defined('TIREA_SAG_MAX_DISPLAY')) {
    define('TIREA_SAG_MAX_DISPLAY', 30); // nb max d'avis rendus dans la liste
}

function tirea_sag_get_data($scope = '') {
    $scope = $scope !== '' ? $scope : TIREA_SAG_SCOPE;
    $empty = ['total' => 0, 'average' => 0.0, 'distribution' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0], 'reviews' => []];

    if (TIREA_SAG_PUBLIC_KEY === '') {
        return $empty;
    }

    $cache_key = 'tirea_sag_' . md5($scope);
    $cached = get_transient($cache_key);
    if (is_array($cached)) {
        return $cached;
    }

    $url = 'https://api.guaranteed-reviews.com/public/v3/reviews/'
         . rawurlencode(TIREA_SAG_PUBLIC_KEY) . '/' . rawurlencode($scope);

    $response = wp_remote_get($url, [
        'timeout' => 8,
        'headers' => ['Accept' => 'application/json'],
    ]);

    if (is_wp_error($response) || (int) wp_remote_retrieve_response_code($response) !== 200) {
        set_transient($cache_key, $empty, 5 * MINUTE_IN_SECONDS); // repli court : on retentera vite
        return $empty;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (!is_array($body) || !isset($body['ratings'])) {
        set_transient($cache_key, $empty, 5 * MINUTE_IN_SECONDS);
        return $empty;
    }

    $ratings = $body['ratings'];
    $dist_src = isset($ratings['distribution']) && is_array($ratings['distribution']) ? $ratings['distribution'] : [];
    $distribution = [];
    for ($i = 5; $i >= 1; $i--) {
        if (isset($dist_src[$i])) {
            $distribution[$i] = (int) $dist_src[$i];
        } elseif (isset($dist_src[(string) $i])) {
            $distribution[$i] = (int) $dist_src[(string) $i];
        } else {
            $distribution[$i] = 0;
        }
    }

    $reviews = [];
    if (!empty($body['reviews']) && is_array($body['reviews'])) {
        foreach ($body['reviews'] as $rv) {
            if (!is_array($rv)) {
                continue;
            }
            $reviews[] = [
                'name'  => isset($rv['c']) ? (string) $rv['c'] : '',
                'rate'  => isset($rv['r']) ? (int) $rv['r'] : 0,
                'text'  => isset($rv['txt']) ? (string) $rv['txt'] : '',
                'date'  => isset($rv['date']) ? (string) $rv['date'] : '',
                'reply' => isset($rv['reply']) ? (string) $rv['reply'] : '',
                'rdate' => isset($rv['rdate']) ? (string) $rv['rdate'] : '',
            ];
        }
    }

    $data = [
        'total'        => isset($ratings['total']) ? (int) $ratings['total'] : count($reviews),
        'average'      => isset($ratings['average']) ? (float) $ratings['average'] : 0.0,
        'distribution' => $distribution,
        'reviews'      => $reviews,
    ];

    $ttl = ($data['total'] > 0) ? TIREA_SAG_CACHE : HOUR_IN_SECONDS; // court tant qu'il n'y a aucun avis
    set_transient($cache_key, $data, $ttl);
    return $data;
}

function tirea_sag_format_date($raw) {
    $ts = strtotime((string) $raw);
    if (!$ts) {
        return '';
    }
    return date_i18n('j F Y', $ts);
}

/**
 * Injecte un AggregateRating REEL dans le schema Produit de Rank Math,
 * uniquement sur la fiche produit ET seulement s'il existe au moins un avis verifie.
 */
function tirea_sag_product_schema($entity) {
    if (!function_exists('is_product') || !is_product()) {
        return $entity;
    }
    $data = tirea_sag_get_data();
    if (empty($data['total']) || (int) $data['total'] < 1) {
        return $entity;
    }
    $entity['aggregateRating'] = [
        '@type'       => 'AggregateRating',
        'ratingValue' => round((float) $data['average'], 1),
        'reviewCount' => (int) $data['total'],
        'bestRating'  => '5',
        'worstRating' => '1',
    ];
    return $entity;
}
add_filter('rank_math/snippet/rich_snippet_product_entity', 'tirea_sag_product_schema');

// END ENQUEUE PARENT ACTION
// ============================================
// TIREA — Sélecteur de packs WooCommerce
// ============================================

function tirea_enqueue_product_assets() {
    if (!is_product() && !is_front_page()) return;

    $css_path = get_stylesheet_directory() . '/assets/css/tirea-product.css';
    $js_path  = get_stylesheet_directory() . '/assets/js/tirea-product.js';

    wp_enqueue_style(
        'tirea-product-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-product.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );

    wp_enqueue_script(
        'tirea-product-js',
        get_stylesheet_directory_uri() . '/assets/js/tirea-product.js',
        ['jquery'],
        file_exists($js_path) ? filemtime($js_path) : null,
        true
    );

    wp_localize_script('tirea-product-js', 'tireaData', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'checkout_url' => wc_get_checkout_url(),
        'cart_url' => wc_get_cart_url(),
        'nonce' => wp_create_nonce('tirea_add_to_cart'),
    ]);
}

add_action('wp_enqueue_scripts', 'tirea_enqueue_product_assets');

function tirea_defer_product_js($tag, $handle) {
    if ('tirea-product-js' === $handle) {
        return str_replace(' src=', ' defer src=', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'tirea_defer_product_js', 10, 2);

// ============================================
// TIREA - Zone note "Avis a venir" (fiche produit)
// ============================================
function tirea_enqueue_avis_assets() {
    if (!is_product() && !is_front_page()) return;

    $css_path = get_stylesheet_directory() . '/assets/css/tirea-avis.css';
    $js_path  = get_stylesheet_directory() . '/assets/js/tirea-avis.js';

    wp_enqueue_style(
        'tirea-avis-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-avis.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );

    wp_enqueue_script(
        'tirea-avis-js',
        get_stylesheet_directory_uri() . '/assets/js/tirea-avis.js',
        [],
        file_exists($js_path) ? filemtime($js_path) : null,
        true
    );
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_avis_assets');

function tirea_defer_avis_js($tag, $handle) {
    if ('tirea-avis-js' === $handle) {
        return str_replace(' src=', ' defer src=', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'tirea_defer_avis_js', 10, 2);

function tirea_product_selector_shortcode($atts) {
    $atts = shortcode_atts(['id' => 0], $atts);
    $product_id = intval($atts['id']);
    if (!$product_id) return '';

    $product = wc_get_product($product_id);
    if (!$product || !$product->is_type('variable')) return '';

    ob_start();
    $tirea_product = $product;
    include get_stylesheet_directory() . '/tirea-product-selector.php';
    return ob_get_clean();
}
add_shortcode('tirea_product_selector', 'tirea_product_selector_shortcode');

function tirea_ajax_add_to_cart() {
    check_ajax_referer('tirea_add_to_cart', 'nonce');
    $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if (!$variation_id || !$product_id) {
        wp_send_json_error(['message' => 'Données manquantes']);
    }

    $variation = wc_get_product($variation_id);
    if (!$variation) {
        wp_send_json_error(['message' => 'Variation introuvable']);
    }

    $variation_attributes = $variation->get_variation_attributes();

    $added = WC()->cart->add_to_cart(
        $product_id,
        $quantity,
        $variation_id,
        $variation_attributes
    );

    if ($added) {
        wp_send_json_success([
            'redirect' => wc_get_checkout_url(),
            'cart_count' => WC()->cart->get_cart_contents_count(),
        ]);
    } else {
        wp_send_json_error(['message' => 'Erreur lors de l\'ajout au panier']);
    }
}
add_action('wp_ajax_tirea_add_to_cart', 'tirea_ajax_add_to_cart');
add_action('wp_ajax_nopriv_tirea_add_to_cart', 'tirea_ajax_add_to_cart');

function tirea_mini_selector_shortcode($atts) {
    $atts = shortcode_atts(['id' => 0], $atts);
    $product_id = intval($atts['id']);
    if (!$product_id) return '';

    $product = wc_get_product($product_id);
    if (!$product || !$product->is_type('variable')) return '';

    ob_start();
    $tirea_product = $product;
    include get_stylesheet_directory() . '/tirea-mini-selector.php';
    return ob_get_clean();
}
add_shortcode('tirea_mini_selector', 'tirea_mini_selector_shortcode');

if (!function_exists('tirea_reviews_shortcode')) {
    function tirea_reviews_shortcode($atts) {
        ob_start();
        include get_stylesheet_directory() . '/tirea-reviews.php';
        return ob_get_clean();
    }
    add_shortcode('tirea_reviews', 'tirea_reviews_shortcode');
}

// ============================================
// TIREA — Design tokens (global, chargé en premier)
// ============================================

function tirea_enqueue_tokens() {
    $css_path = get_stylesheet_directory() . '/assets/css/tirea-tokens.css';
    wp_enqueue_style(
        'tirea-tokens-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-tokens.css',
        [],
        file_exists($css_path) ? filemtime($css_path) : null
    );
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_tokens', 5); // priorité 5 = avant les sections (défaut 10)

// ============================================
// TIREA — Header
// ============================================

function tirea_enqueue_header_assets() {
    $css_path = get_stylesheet_directory() . '/assets/css/tirea-header.css';
    $js_path  = get_stylesheet_directory() . '/assets/js/tirea-header.js';

    wp_enqueue_style(
        'tirea-header-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-header.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );

    wp_enqueue_script(
        'tirea-header-js',
        get_stylesheet_directory_uri() . '/assets/js/tirea-header.js',
        [],
        file_exists($js_path) ? filemtime($js_path) : null,
        true
    );
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_header_assets');

function tirea_defer_header_js($tag, $handle) {
    if ('tirea-header-js' === $handle) {
        return str_replace(' src=', ' defer src=', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'tirea_defer_header_js', 10, 2);

function tirea_header_shortcode() {
    ob_start();
    include get_stylesheet_directory() . '/tirea-header.php';
    return ob_get_clean();
}
add_shortcode('tirea_header', 'tirea_header_shortcode');

// ============================================
// TIREA — Hero (page d'accueil)
// ============================================

/**
 * Source UNIQUE des URLs d'images du hero.
 * Modifie ICI pour changer les images : aucune autre URL à toucher ailleurs.
 * Utilisé par : tirea_hero_preload() (preload <head>) + tirea-hero.php (vars CSS + <img> sémantique).
 */
function tirea_hero_images() {
    return [
        'desktop' => 'https://tirea.fr/wp-content/uploads/2026/05/ajusteur-tirea-homme-femme.webp',
        'mobile'  => 'https://tirea.fr/wp-content/uploads/2026/05/ajusteur-tirea-unisexe.webp',
    ];
}

/**
 * Preload LCP du hero — injecté dans le <head> via wp_head.
 * Deux balises avec media query : le navigateur ne télécharge que celle qui matche.
 * Conditionné à is_front_page() pour ne pas polluer les autres pages.
 */
function tirea_hero_preload() {
    if (!is_front_page()) return;

    $imgs = tirea_hero_images();
    ?>
    <link rel="preload" as="image"
          href="<?php echo esc_url($imgs['desktop']); ?>"
          media="(min-width: 641px)"
          fetchpriority="high">
    <link rel="preload" as="image"
          href="<?php echo esc_url($imgs['mobile']); ?>"
          media="(max-width: 640px)"
          fetchpriority="high">
    <?php
}
add_action('wp_head', 'tirea_hero_preload', 1);

function tirea_enqueue_hero_assets() {
    if (!is_front_page()) return;

    $css_path = get_stylesheet_directory() . '/assets/css/tirea-hero.css';
    wp_enqueue_style(
        'tirea-hero-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-hero.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_hero_assets');

function tirea_hero_shortcode() {
    ob_start();
    include get_stylesheet_directory() . '/tirea-hero.php';
    return ob_get_clean();
}
add_shortcode('tirea_hero', 'tirea_hero_shortcode');

// ============================================
// TIREA — Réassurance pilule
// ============================================

function tirea_enqueue_reassurance_pill_assets() {
    if (!is_front_page()) return;

    $css_path = get_stylesheet_directory() . '/assets/css/tirea-reassurance-pill.css';
    wp_enqueue_style(
        'tirea-reassurance-pill-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-reassurance-pill.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_reassurance_pill_assets');

function tirea_reassurance_pill_shortcode() {
    ob_start();
    include get_stylesheet_directory() . '/tirea-reassurance-pill.php';
    return ob_get_clean();
}
add_shortcode('tirea_reassurance_pill', 'tirea_reassurance_pill_shortcode');

// ============================================
// TIREA — Réassurance card
// ============================================

function tirea_enqueue_reassurance_card_assets() {
    if (!is_front_page()) return;

    $css_path = get_stylesheet_directory() . '/assets/css/tirea-reassurance-card.css';
    wp_enqueue_style(
        'tirea-reassurance-card-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-reassurance-card.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_reassurance_card_assets');

function tirea_reassurance_card_shortcode() {
    ob_start();
    include get_stylesheet_directory() . '/tirea-reassurance-card.php';
    return ob_get_clean();
}
add_shortcode('tirea_reassurance_card', 'tirea_reassurance_card_shortcode');

// ============================================
// TIREA — Ajusteur
// ============================================

function tirea_enqueue_ajusteur_assets() {
    if (!is_front_page() && !is_product()) return;

    $css_path = get_stylesheet_directory() . '/assets/css/tirea-ajusteur.css';
    $js_path  = get_stylesheet_directory() . '/assets/js/tirea-ajusteur.js';

    wp_enqueue_style(
        'tirea-ajusteur-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-ajusteur.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );

    wp_enqueue_script(
        'tirea-ajusteur-js',
        get_stylesheet_directory_uri() . '/assets/js/tirea-ajusteur.js',
        [],
        file_exists($js_path) ? filemtime($js_path) : null,
        true
    );
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_ajusteur_assets');

function tirea_defer_ajusteur_js($tag, $handle) {
    if ('tirea-ajusteur-js' === $handle) {
        return str_replace(' src=', ' defer src=', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'tirea_defer_ajusteur_js', 10, 2);

function tirea_ajusteur_shortcode($atts) {
    $atts = shortcode_atts(['show_cta' => '1'], $atts);
    $show_cta = ($atts['show_cta'] === '1');

    ob_start();
    include get_stylesheet_directory() . '/tirea-ajusteur.php';
    return ob_get_clean();
}
add_shortcode('tirea_ajusteur', 'tirea_ajusteur_shortcode');

// ============================================
// TIREA — Footer (global, toutes pages)
// ============================================

function tirea_enqueue_footer_assets() {
    $css_path = get_stylesheet_directory() . '/assets/css/tirea-footer.css';
    $js_path  = get_stylesheet_directory() . '/assets/js/tirea-footer.js';

    wp_enqueue_style(
        'tirea-footer-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-footer.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );

    wp_enqueue_script(
        'tirea-footer-js',
        get_stylesheet_directory_uri() . '/assets/js/tirea-footer.js',
        [],
        file_exists($js_path) ? filemtime($js_path) : null,
        true
    );
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_footer_assets');

function tirea_defer_footer_js($tag, $handle) {
    if ('tirea-footer-js' === $handle) {
        return str_replace(' src=', ' defer src=', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'tirea_defer_footer_js', 10, 2);

function tirea_footer_shortcode() {
    ob_start();
    include get_stylesheet_directory() . '/tirea-footer.php';
    return ob_get_clean();
}
add_shortcode('tirea_footer', 'tirea_footer_shortcode');

// ============================================
// TIREA — Result (bloc "résultat instantané" réutilisable)
// ============================================

function tirea_enqueue_result_assets() {
    if (!is_front_page() && !is_product()) return;

    $css_path = get_stylesheet_directory() . '/assets/css/tirea-result.css';
    $js_path  = get_stylesheet_directory() . '/assets/js/tirea-result.js';

    wp_enqueue_style(
        'tirea-result-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-result.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );

    wp_enqueue_script(
        'tirea-result-js',
        get_stylesheet_directory_uri() . '/assets/js/tirea-result.js',
        [],
        file_exists($js_path) ? filemtime($js_path) : null,
        true
    );
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_result_assets');

function tirea_defer_result_js($tag, $handle) {
    if ('tirea-result-js' === $handle) {
        return str_replace(' src=', ' defer src=', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'tirea_defer_result_js', 10, 2);

function tirea_result_shortcode() {
    ob_start();
    include get_stylesheet_directory() . '/tirea-result.php';
    return ob_get_clean();
}
add_shortcode('tirea_result', 'tirea_result_shortcode');

// ============================================
// TIREA — Storytelling (animation "Un look ___")
// ============================================

/**
 * Enqueue CSS + JS du storytelling — uniquement sur la home
 */
function tirea_enqueue_storytelling_assets() {
    if (!is_front_page()) return;

    $css_path = get_stylesheet_directory() . '/assets/css/tirea-storytelling.css';
    $js_path  = get_stylesheet_directory() . '/assets/js/tirea-storytelling.js';

    wp_enqueue_style(
        'tirea-storytelling-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-storytelling.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );

    wp_enqueue_script(
        'tirea-storytelling-js',
        get_stylesheet_directory_uri() . '/assets/js/tirea-storytelling.js',
        [],
        file_exists($js_path) ? filemtime($js_path) : null,
        true
    );
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_storytelling_assets');

/**
 * Ajoute defer au JS du storytelling (chargement non-bloquant)
 */
function tirea_defer_storytelling_js($tag, $handle) {
    if ('tirea-storytelling-js' === $handle) {
        return str_replace(' src=', ' defer src=', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'tirea_defer_storytelling_js', 10, 2);

/**
 * Shortcode [tirea_storytelling]
 */
function tirea_storytelling_shortcode() {
    ob_start();
    include get_stylesheet_directory() . '/tirea-storytelling.php';
    return ob_get_clean();
}
add_shortcode('tirea_storytelling', 'tirea_storytelling_shortcode');

// ============================================
// TIREA — Guide d'utilisation (factorisé, variantes light/full)
// ============================================

function tirea_enqueue_guide_assets() {
    if (!is_product() && !is_front_page()) return;

    $css_path = get_stylesheet_directory() . '/assets/css/tirea-guide.css';
    wp_enqueue_style(
        'tirea-guide-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-guide.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_guide_assets');

function tirea_guide_shortcode($atts) {
    $atts = shortcode_atts(['variant' => 'full'], $atts);
    $tirea_guide_variant = ($atts['variant'] === 'light') ? 'light' : 'full';

    ob_start();
    include get_stylesheet_directory() . '/tirea-guide.php';
    return ob_get_clean();
}
add_shortcode('tirea_guide', 'tirea_guide_shortcode');

// ============================================
// TIREA — FAQ
// ============================================

function tirea_enqueue_faq_assets() {
    // Version allégée sur la home + FAQ complète sur la page /faq
    if (!is_front_page() && !is_page('faq') && !is_product()) return;

    $css_path = get_stylesheet_directory() . '/assets/css/tirea-faq.css';
    $js_path  = get_stylesheet_directory() . '/assets/js/tirea-faq.js';

    wp_enqueue_style(
        'tirea-faq-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-faq.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );

    wp_enqueue_script(
        'tirea-faq-js',
        get_stylesheet_directory_uri() . '/assets/js/tirea-faq.js',
        [],
        file_exists($js_path) ? filemtime($js_path) : null,
        true
    );

    wp_localize_script('tirea-faq-js', 'tireaFaqData', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_faq_assets');

function tirea_defer_faq_js($tag, $handle) {
    if ('tirea-faq-js' === $handle) {
        return str_replace(' src=', ' defer src=', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'tirea_defer_faq_js', 10, 2);

function tirea_faq_shortcode($atts) {
    // mode    : "home" (allégé, défaut) ou "full" (page /faq : tout affiché + JSON-LD)
    // contact : "on" (défaut) affiche le formulaire de contact ; "off" le masque
    // more    : "on" (défaut) affiche le lien "Voir toutes les questions" ; "off" le masque
    // badge   : "on" (défaut) affiche le badge "Centre d'aide" ; "off" le masque
    $atts = shortcode_atts([
        'mode'    => 'home',
        'contact' => 'on',
        'more'    => 'on',
        'badge'   => 'on',
    ], $atts, 'tirea_faq');

    $tirea_faq_mode         = ($atts['mode'] === 'full') ? 'full' : 'home';
    $tirea_faq_show_contact = ($atts['contact'] !== 'off');
    $tirea_faq_show_more    = ($atts['more'] !== 'off');
    $tirea_faq_show_badge   = ($atts['badge'] !== 'off');

    ob_start();
    include get_stylesheet_directory() . '/tirea-faq.php';
    return ob_get_clean();
}
add_shortcode('tirea_faq', 'tirea_faq_shortcode');

// Page /faq : forcer le gabarit PHP automatiquement, par slug (modèle /suivi)
add_filter('template_include', function($template) {
    if (is_page('faq')) {
        $tpl = get_stylesheet_directory() . '/tirea-faq-page.php';
        if (file_exists($tpl)) return $tpl;
    }
    return $template;
});

/**
 * Handler AJAX — envoi du formulaire de contact FAQ via wp_mail()
 */
function tirea_faq_contact_handler() {
    // Vérif nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tirea_faq_contact')) {
        wp_send_json_error(['message' => 'Session expirée, rechargez la page.']);
    }

    // Honeypot : si rempli, on simule un succès silencieux (bot)
    if (!empty($_POST['website'])) {
        wp_send_json_success(['message' => 'Message envoyé ! Nous vous répondons sous 24h.']);
    }

    // Anti-abus : max 3 envois / 10 min par IP (même helper que les formulaires légaux)
    if (!tirea_form_rate_limit('faq_contact')) {
        wp_send_json_error(['message' => 'Trop de tentatives. Réessayez dans quelques minutes.']);
    }

    $name    = isset($_POST['name'])    ? sanitize_text_field(wp_unslash($_POST['name']))    : '';
    $email   = isset($_POST['email'])   ? sanitize_email(wp_unslash($_POST['email']))        : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';

    if (!$name || !is_email($email) || !$message) {
        wp_send_json_error(['message' => 'Merci de remplir tous les champs correctement.']);
    }

    $to      = 'contact@tirea.fr';
    $subject = 'Question FAQ — ' . $name;
    $body    = "Nouveau message depuis la FAQ Tirea :\n\n"
             . "Nom : $name\n"
             . "Email : $email\n\n"
             . "Message :\n$message\n";
    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    ];

    $sent = wp_mail($to, $subject, $body, $headers);

    if ($sent) {
        wp_send_json_success(['message' => 'Message envoyé ! Nous vous répondons sous 24h.']);
    } else {
        wp_send_json_error(['message' => "L'envoi a échoué. Réessayez ou écrivez-nous à contact@tirea.fr."]);
    }
}
add_action('wp_ajax_tirea_faq_contact', 'tirea_faq_contact_handler');
add_action('wp_ajax_nopriv_tirea_faq_contact', 'tirea_faq_contact_handler');

// ============================================
// TIREA — Optimisation perf : dequeue conditionnel
// ============================================

/**
 * Détermine si la page courante est réellement construite avec Elementor.
 * Exception : produits WooCommerce → rendus par shortcode dans notre thème,
 * on force `false` même si Elementor déclare un document fantôme pour eux.
 */
function tirea_page_uses_elementor() {
    if (!class_exists('\Elementor\Plugin')) return false;

    // Fiche produit : toujours en pur shortcode chez nous, jamais Elementor
    if (function_exists('is_product') && is_product()) return false;

    // Home : rendue par front-page.php (shortcodes purs), jamais Elementor
    if (is_front_page()) return false;

    // Pages légales : rendues par template-tirea-legal.php (shortcode pur), jamais Elementor
    if (is_page(tirea_legal_slugs())) return false;

    // Page FAQ : rendue par tirea-faq-page.php (shortcode pur), jamais Elementor
    if (is_page('faq')) return false;

    $post_id = get_queried_object_id();
    if (!$post_id) return false;

    $document = \Elementor\Plugin::$instance->documents->get($post_id);
    if (!$document) return false;

    return $document->is_built_with_elementor();
}

/**
 * Dequeue des assets Elementor sur les pages qui ne l'utilisent pas.
 * IMPORTANT : on utilise wp_dequeue_* SEUL (pas de wp_deregister_*), pour ne pas
 * casser les scripts qui déclarent ces handles comme dépendance.
 * On ne touche PAS à : lodash, swiper (dépendances potentielles de Woo / du slider avis).
 */
function tirea_dequeue_elementor_when_unused() {
    if (is_admin()) return;

    if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->preview->is_preview_mode()) return;

    if (tirea_page_uses_elementor()) return;

    // CSS Elementor + Hello Elementor + Font Awesome à virer (sûrs à dequeue)
    $elementor_styles = [
        'elementor-frontend',
        'elementor-frontend-legacy',
        'elementor-post',
        'elementor-icons',
        'elementor-animations',
        'widget-icon-list',
        'widget-social-icons',
        'header-footer-elementor',
        'font-awesome-5-all',
        'font-awesome-5-brands',
        'font-awesome-5-solid',
        'font-awesome-brands',
        'font-awesome-solid',
        'font-awesome',
        'fontawesome',
        // Elementor base layout — non utilisé par nos templates PHP (home + fiche produit)
        'base-desktop',
        // UAE (Ultimate Addons for Elementor / Header Footer Elementor) — non utilisé sur la home
        'hfe-style',
        'hfe-widgets-style',
        'hfe-woo-product-grid',
        'hfe-elementor-icons',
        'hfe-icons-list',
        'hfe-social-icons',
        'hfe-social-share-icons-brands',
        'hfe-social-share-icons-fontawesome',
        'hfe-nav-menu-icons',
        // CSS per-post Elementor (anciens header/footer UAE — IDs 10 et 19)
        'elementor-post-10',
        'elementor-post-19',
    ];
    foreach ($elementor_styles as $handle) {
        wp_dequeue_style($handle);
    }

    // JS Elementor à virer — SANS lodash ni swiper (dépendances risquées)
    $elementor_scripts = [
        'elementor-frontend',
        'elementor-frontend-modules',
        'elementor-waypoints',
        'elementor-dialog',
        'share-link',
        'swiper',
        'e-swiper',
        'lodash',
    ];
    foreach ($elementor_scripts as $handle) {
        wp_dequeue_script($handle);
    }
}
add_action('wp_enqueue_scripts', 'tirea_dequeue_elementor_when_unused', 99);

/**
 * Décharge Gutenberg block-library (contenu front géré par templates PHP + shortcodes).
 * EXCEPTION : panier / checkout / compte sont rendus par WooCommerce Blocks et
 * dépendent de ces styles — on ne décharge JAMAIS sur le tunnel d'achat.
 */
function tirea_dequeue_gutenberg_styles() {
    if (is_admin()) return;
    if (function_exists('is_cart') && (is_cart() || is_checkout() || is_account_page())) return;

    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'tirea_dequeue_gutenberg_styles', 99);

/**
 * Restreint le SDK Stripe + scripts WooPayments aux pages d'achat.
 * IMPORTANT : wp_dequeue_* SEUL (pas de deregister) pour ne casser aucune dépendance.
 */
function tirea_restrict_woopayments_assets() {
    if (is_admin()) return;
    if (!function_exists('is_product')) return;

    $needs_stripe = is_product() || is_cart() || is_checkout() || is_account_page();
    if ($needs_stripe) return;

    $woopayments_handles_js = [
        'wcpay-express-checkout',
        'wcpay-express-checkout-ece',
        'wc-payments-checkout',
        'wcpay-checkout',
    ];
    foreach ($woopayments_handles_js as $handle) {
        wp_dequeue_script($handle);
    }

    $woopayments_handles_css = [
        'wcpay-express-checkout',
        'wcpay-express-checkout-style',
        'wc-payments-checkout',
    ];
    foreach ($woopayments_handles_css as $handle) {
        wp_dequeue_style($handle);
    }
}
add_action('wp_enqueue_scripts', 'tirea_restrict_woopayments_assets', 99);

/**
 * Dequeue conservateur des CSS Woo render-blocking inutiles sur NOTRE fiche produit custom.
 * Notre template woocommerce/single-product.php ne déclenche aucun composant Woo natif
 * (pas de galerie, pas de tabs, pas de related, pas de bouton .button) : ces styles sont du déchet.
 * Rappel : wp_dequeue_style SEUL — JAMAIS wp_deregister_style (casse les dépendances checkout).
 */
function tirea_dequeue_woo_assets_on_product() {
    if (is_admin()) return;
    if (!function_exists('is_product') || !is_product()) return;

    // Galerie zoom Woo (Photoswipe) — slider Tirea custom, aucune dépendance lib externe
    wp_dequeue_style('photoswipe');
    wp_dequeue_style('photoswipe-default-skin');

    // Styles des blocs Gutenberg WooCommerce — fiche rendue par shortcode PHP, aucun bloc Woo
    wp_dequeue_style('wc-blocks-style');
    wp_dequeue_style('wc-blocks-vendors-style');
    wp_dequeue_style('wc-block-style');
}
add_action('wp_enqueue_scripts', 'tirea_dequeue_woo_assets_on_product', 99);

/**
 * Désactive la galerie WooCommerce native (zoom, lightbox Photoswipe, slider).
 * Notre fiche produit utilise un slider custom (tirea-product-selector.php),
 * donc la galerie Woo injecte un <div class="pswp"> inutile dans le DOM
 * et fait charger photoswipe.js/css pour rien.
 * Priorité 100 = on passe APRÈS Hello Elementor qui déclare ces supports.
 */
function tirea_remove_woo_gallery_support() {
    remove_theme_support('wc-product-gallery-zoom');
    remove_theme_support('wc-product-gallery-lightbox');
    remove_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'tirea_remove_woo_gallery_support', 100);

/* ==========================================================================
   TIREA — Pages légales (CGV, mentions, confidentialité, livraison, retours, contact, histoire)
   Shortcode : [tirea_legal_page slug="cgv"]
   ========================================================================== */

// Slugs des pages WordPress qui utilisent le système légal
function tirea_legal_slugs() {
    return ['cgv', 'contact', 'livraison', 'mentions-legales', 'notre-histoire', 'confidentialite', 'retours'];
}

// Enqueue CSS/JS — uniquement sur les pages légales
function tirea_enqueue_legal_assets() {
    if (!is_page(tirea_legal_slugs())) return;

    $css_path = get_stylesheet_directory() . '/assets/css/tirea-legal.css';
    $js_path  = get_stylesheet_directory() . '/assets/js/tirea-legal.js';

    wp_enqueue_style(
        'tirea-legal-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-legal.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );

    wp_enqueue_script(
        'tirea-legal-js',
        get_stylesheet_directory_uri() . '/assets/js/tirea-legal.js',
        [],
        file_exists($js_path) ? filemtime($js_path) : null,
        true
    );

    // Passe les infos AJAX au JS (URL admin-ajax + nonce)
    wp_localize_script('tirea-legal-js', 'tireaLegalAjax', [
        'url'   => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tirea_legal_form'),
    ]);
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_legal_assets');

// Pages légales : forcer le gabarit PHP automatiquement, par slug (zéro assignation manuelle)
add_filter('template_include', function($template) {
    if (is_page(tirea_legal_slugs())) {
        $tpl = get_stylesheet_directory() . '/template-tirea-legal.php';
        if (file_exists($tpl)) return $tpl;
    }
    return $template;
});

// JS en defer
function tirea_defer_legal_js($tag, $handle) {
    if ($handle === 'tirea-legal-js') {
        return str_replace(' src=', ' defer src=', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'tirea_defer_legal_js', 10, 2);

// Shortcode principal
function tirea_legal_page_shortcode($atts) {
    $atts = shortcode_atts(['slug' => ''], $atts, 'tirea_legal_page');
    ob_start();
    include get_stylesheet_directory() . '/tirea-legal.php';
    return ob_get_clean();
}
add_shortcode('tirea_legal_page', 'tirea_legal_page_shortcode');

/* ----------- Handlers AJAX des formulaires ----------- */

// Helper : rate limiting basique (max 3 envois / 10 min par IP)
function tirea_form_rate_limit($key) {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : 'unknown';
    $transient = 'tirea_rl_' . $key . '_' . md5($ip);
    $count = (int) get_transient($transient);
    if ($count >= 3) return false;
    set_transient($transient, $count + 1, 10 * MINUTE_IN_SECONDS);
    return true;
}

// Helper : vérifie honeypot + nonce
function tirea_form_security_check() {
    // Nonce
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'tirea_legal_form')) {
        wp_send_json_error(['message' => 'Session expirée, rechargez la page.']);
    }
    // Honeypot — si rempli, on simule un succès silencieux pour ne pas alerter le bot
    if (!empty($_POST['website'])) {
        wp_send_json_success();
    }
    // Anti-abus : max 3 envois / 10 min par IP (même helper que les formulaires légaux)
    if (!tirea_form_rate_limit('faq')) {
        wp_send_json_error(['message' => 'Trop de tentatives. Réessayez dans quelques minutes.']);
    }
}

// ===== Handler RETOUR (rétractation) =====
function tirea_handle_form_retour() {
    tirea_form_security_check();

    if (!tirea_form_rate_limit('retour')) {
        wp_send_json_error(['message' => 'Trop de tentatives. Réessayez dans 10 minutes.']);
    }

    // Sanitize
    $commande      = isset($_POST['commande'])       ? sanitize_text_field(wp_unslash($_POST['commande']))       : '';
    $date_commande = isset($_POST['date_commande'])  ? sanitize_text_field(wp_unslash($_POST['date_commande']))  : '';
    $date_reception= isset($_POST['date_reception']) ? sanitize_text_field(wp_unslash($_POST['date_reception'])) : '';
    $nom           = isset($_POST['nom'])            ? sanitize_text_field(wp_unslash($_POST['nom']))            : '';
    $email         = isset($_POST['email'])          ? sanitize_email(wp_unslash($_POST['email']))               : '';
    $adresse       = isset($_POST['adresse'])        ? sanitize_text_field(wp_unslash($_POST['adresse']))        : '';
    $article       = isset($_POST['article'])        ? sanitize_text_field(wp_unslash($_POST['article']))        : '';
    $motif         = isset($_POST['motif'])          ? sanitize_textarea_field(wp_unslash($_POST['motif']))      : '';

    // Validation
    if (empty($commande) || empty($date_commande) || empty($date_reception) ||
        empty($nom) || empty($email) || empty($adresse) || empty($article)) {
        wp_send_json_error(['message' => 'Champs obligatoires manquants.']);
    }
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Adresse email invalide.']);
    }

    // ----- Mail à TIREA (sav@tirea.fr) -----
    $to_admin = 'sav@tirea.fr';
    $subject_admin = '[TIREA] Demande de rétractation — Commande ' . $commande;
    $body_admin = "Nouvelle demande de rétractation reçue via tirea.fr\n\n"
                . "--- INFORMATIONS CLIENT ---\n"
                . "Nom : {$nom}\n"
                . "Email : {$email}\n"
                . "Adresse : {$adresse}\n\n"
                . "--- COMMANDE ---\n"
                . "Numéro : {$commande}\n"
                . "Commandé le : {$date_commande}\n"
                . "Reçu le : {$date_reception}\n"
                . "Article(s) : {$article}\n\n"
                . "--- MOTIF ---\n"
                . ($motif !== '' ? $motif : '(non précisé)') . "\n\n"
                . "--- ENVOI ---\n"
                . "Date d'envoi : " . current_time('d/m/Y H:i') . "\n"
                . "IP : " . (isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '—') . "\n";

    $headers_admin = [
        'From: TIREA <noreply@tirea.fr>',
        'Reply-To: ' . $nom . ' <' . $email . '>',
        'Content-Type: text/plain; charset=UTF-8',
    ];

    $sent_admin = wp_mail($to_admin, $subject_admin, $body_admin, $headers_admin);

    // ----- Mail copie au client (preuve de notification) -----
    $subject_client = 'Confirmation de votre demande de rétractation — TIREA';
    $body_client = "Bonjour {$nom},\n\n"
                 . "Nous avons bien reçu votre demande de rétractation pour la commande {$commande}.\n"
                 . "Conformément à l'article L.221-18 du Code de la consommation, votre notification est désormais enregistrée.\n\n"
                 . "Notre équipe vous recontactera sous 24 heures avec l'adresse postale à laquelle expédier le ou les articles.\n\n"
                 . "--- RÉCAPITULATIF DE VOTRE DEMANDE ---\n"
                 . "Numéro de commande : {$commande}\n"
                 . "Commandé le : {$date_commande}\n"
                 . "Reçu le : {$date_reception}\n"
                 . "Article(s) : {$article}\n"
                 . "Motif : " . ($motif !== '' ? $motif : '(non précisé)') . "\n\n"
                 . "Pour toute question, écrivez-nous à sav@tirea.fr.\n\n"
                 . "À bientôt,\n"
                 . "L'équipe TIREA\n"
                 . "https://tirea.fr\n";

    $headers_client = [
        'From: TIREA <sav@tirea.fr>',
        'Content-Type: text/plain; charset=UTF-8',
    ];

    wp_mail($email, $subject_client, $body_client, $headers_client);

    if ($sent_admin) {
        wp_send_json_success(['message' => 'Demande envoyée.']);
    } else {
        wp_send_json_error(['message' => 'L\'envoi a échoué. Réessayez ou contactez-nous directement.']);
    }
}
add_action('wp_ajax_tirea_form_retour', 'tirea_handle_form_retour');
add_action('wp_ajax_nopriv_tirea_form_retour', 'tirea_handle_form_retour');

// ===== Handler CONTACT =====
function tirea_handle_form_contact() {
    tirea_form_security_check();

    if (!tirea_form_rate_limit('contact')) {
        wp_send_json_error(['message' => 'Trop de tentatives. Réessayez dans 10 minutes.']);
    }

    // Sanitize
    $prenom   = isset($_POST['prenom'])   ? sanitize_text_field(wp_unslash($_POST['prenom']))      : '';
    $nom      = isset($_POST['nom'])      ? sanitize_text_field(wp_unslash($_POST['nom']))         : '';
    $email    = isset($_POST['email'])    ? sanitize_email(wp_unslash($_POST['email']))            : '';
    $commande = isset($_POST['commande']) ? sanitize_text_field(wp_unslash($_POST['commande']))    : '';
    $sujet    = isset($_POST['sujet'])    ? sanitize_text_field(wp_unslash($_POST['sujet']))       : 'Autre';
    $message  = isset($_POST['message'])  ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';

    // Validation
    if (empty($prenom) || empty($nom) || empty($email) || empty($message)) {
        wp_send_json_error(['message' => 'Champs obligatoires manquants.']);
    }
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Adresse email invalide.']);
    }

    // ----- Mail à TIREA -----
    $to_admin = 'contact@tirea.fr';
    $subject_admin = '[TIREA] Contact — ' . $sujet . ($commande !== '' ? ' (Cmd ' . $commande . ')' : '');
    $body_admin = "Nouveau message reçu via le formulaire de contact tirea.fr\n\n"
                . "--- EXPÉDITEUR ---\n"
                . "Prénom : {$prenom}\n"
                . "Nom : {$nom}\n"
                . "Email : {$email}\n"
                . ($commande !== '' ? "N° commande : {$commande}\n" : '')
                . "\n--- SUJET ---\n"
                . $sujet . "\n\n"
                . "--- MESSAGE ---\n"
                . $message . "\n\n"
                . "--- ENVOI ---\n"
                . "Date : " . current_time('d/m/Y H:i') . "\n"
                . "IP : " . (isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '—') . "\n";

    $headers_admin = [
        'From: TIREA <noreply@tirea.fr>',
        'Reply-To: ' . $prenom . ' ' . $nom . ' <' . $email . '>',
        'Content-Type: text/plain; charset=UTF-8',
    ];

    $sent_admin = wp_mail($to_admin, $subject_admin, $body_admin, $headers_admin);

    // ----- Mail accusé de réception au client -----
    $subject_client = 'Nous avons bien reçu votre message — TIREA';
    $body_client = "Bonjour {$prenom},\n\n"
                 . "Nous vous confirmons la bonne réception de votre message.\n"
                 . "Notre équipe française vous répond personnellement, par e-mail, sous 24 heures.\n\n"
                 . "--- RÉCAPITULATIF DE VOTRE MESSAGE ---\n"
                 . "Sujet : {$sujet}\n"
                 . ($commande !== '' ? "N° commande : {$commande}\n" : '')
                 . "\nMessage :\n{$message}\n\n"
                 . "À très vite,\n"
                 . "L'équipe TIREA\n"
                 . "https://tirea.fr\n";

    $headers_client = [
        'From: TIREA <contact@tirea.fr>',
        'Content-Type: text/plain; charset=UTF-8',
    ];

    wp_mail($email, $subject_client, $body_client, $headers_client);

    if ($sent_admin) {
        wp_send_json_success(['message' => 'Message envoyé.']);
    } else {
        wp_send_json_error(['message' => 'L\'envoi a échoué. Réessayez ou contactez-nous directement.']);
    }
}
add_action('wp_ajax_tirea_form_contact', 'tirea_handle_form_contact');
add_action('wp_ajax_nopriv_tirea_form_contact', 'tirea_handle_form_contact');

// ============================================
// TIREA — Wording WooCommerce Blocks (panier + checkout)
// ============================================

function tirea_enqueue_woo_wording() {
    if (!is_cart() && !is_checkout()) return;

    $js_path = get_stylesheet_directory() . '/assets/js/tirea-woo-wording.js';

    wp_enqueue_script(
        'tirea-woo-wording-js',
        get_stylesheet_directory_uri() . '/assets/js/tirea-woo-wording.js',
        ['wp-hooks', 'wp-i18n'],
        file_exists($js_path) ? filemtime($js_path) : null,
        true
    );
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_woo_wording');

/* ==========================================================================
   TIREA — Page Suivi de commande (API Suivi La Poste)
   ========================================================================== */

// Enqueue — uniquement sur la page "suivi" (modèle tirea_enqueue_legal_assets)
function tirea_enqueue_suivi_assets() {
    if (!is_page('suivi')) return;

    $css_path = get_stylesheet_directory() . '/assets/css/tirea-suivi.css';
    $js_path  = get_stylesheet_directory() . '/assets/js/tirea-suivi.js';

    wp_enqueue_style(
        'tirea-suivi-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-suivi.css',
        ['tirea-tokens-css'],
        file_exists($css_path) ? filemtime($css_path) : null
    );

    wp_enqueue_script(
        'tirea-suivi-js',
        get_stylesheet_directory_uri() . '/assets/js/tirea-suivi.js',
        [],
        file_exists($js_path) ? filemtime($js_path) : null,
        true
    );

    wp_localize_script('tirea-suivi-js', 'tireaSuiviData', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_suivi_assets');

// Routage par slug (zéro assignation manuelle, comme le légal)
add_filter('template_include', function($template) {
    if (is_page('suivi')) {
        $tpl = get_stylesheet_directory() . '/tirea-suivi.php';
        if (file_exists($tpl)) return $tpl;
    }
    return $template;
});

function tirea_defer_suivi_js($tag, $handle) {
    if ($handle === 'tirea-suivi-js') {
        return str_replace(' src=', ' defer src=', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'tirea_defer_suivi_js', 10, 2);

// Handler AJAX — API Suivi La Poste (côté serveur)
function tirea_suivi_track() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tirea_suivi')) {
        wp_send_json_error(['message' => 'Session expirée, rechargez la page.']);
    }
    if (!empty($_POST['website'])) {
        wp_send_json_error(['message' => 'Aucun colis trouvé pour ce numéro.']);
    }

    $ip   = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : 'unknown';
    $rl   = 'tirea_rl_suivi_' . md5($ip);
    $hits = (int) get_transient($rl);
    if ($hits >= 15) {
        wp_send_json_error(['message' => 'Trop de recherches. Réessayez dans quelques minutes.']);
    }
    set_transient($rl, $hits + 1, 10 * MINUTE_IN_SECONDS);

    if (!defined('TIREA_LAPOSTE_API_KEY') || TIREA_LAPOSTE_API_KEY === '') {
        wp_send_json_error(['message' => 'Service de suivi momentanément indisponible.']);
    }

    $tracking = isset($_POST['tracking'])
        ? strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) wp_unslash($_POST['tracking'])))
        : '';
    if (strlen($tracking) < 8 || strlen($tracking) > 20) {
        wp_send_json_error(['message' => "Numéro de suivi invalide. Vérifiez votre e-mail de confirmation d'expédition."]);
    }

    $public_link = 'https://www.laposte.fr/outils/suivre-vos-envois?code=' . rawurlencode($tracking);

    $response = wp_remote_get('https://api.laposte.fr/suivi/v1/' . rawurlencode($tracking), [
        'timeout' => 12,
        'headers' => [
            'Accept'      => 'application/json',
            'X-Okapi-Key' => TIREA_LAPOSTE_API_KEY,
        ],
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error(['message' => 'Service de suivi injoignable. Réessayez dans un instant.']);
    }

    $http = (int) wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);

    if ($http === 404 || (isset($body['returnCode']) && (int) $body['returnCode'] === 404)) {
        wp_send_json_error(['message' => "Aucun colis trouvé pour ce numéro. Le suivi peut mettre 24 à 48 h à s'activer après l'expédition."]);
    }
    if ($http !== 200 || empty($body) || empty($body['message'])) {
        wp_send_json_error(['message' => 'Service de suivi momentanément indisponible. Réessayez plus tard.']);
    }

    $status_raw = isset($body['status']) ? strtoupper((string) $body['status']) : '';
    $delivered  = (strpos($status_raw, 'LIVRE') !== false || strpos($status_raw, 'DISTRIBUE') !== false);

    wp_send_json_success([
        'state'    => $delivered ? 'delivered' : 'transit',
        'label'    => wp_strip_all_tags($body['message']),
        'date'     => !empty($body['date']) ? sanitize_text_field($body['date']) : '',
        'tracking' => $tracking,
        'link'     => !empty($body['link']) ? esc_url_raw($body['link']) : $public_link,
    ]);
}
add_action('wp_ajax_tirea_suivi_track', 'tirea_suivi_track');
add_action('wp_ajax_nopriv_tirea_suivi_track', 'tirea_suivi_track');

// ============================================
// TIREA - Réseaux sociaux (composant partagé : footer + page contact)
// Source UNIQUE des liens + icônes. Modifie ICI, ça se répercute partout.
// ============================================
function tirea_render_socials() {
    $socials = [
        [
            'url'   => 'https://www.instagram.com/tirea.fr',
            'label' => 'Instagram',
            'svg'   => '<path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41-.56-.22-.96-.48-1.38-.9-.42-.42-.68-.82-.9-1.38-.16-.42-.36-1.06-.41-2.23-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41 1.27-.06 1.65-.07 4.85-.07M12 0C8.74 0 8.33.01 7.05.07 5.78.13 4.9.33 4.14.63c-.79.31-1.46.72-2.13 1.39C1.35 2.68.94 3.35.63 4.14.33 4.9.13 5.78.07 7.05.01 8.33 0 8.74 0 12c0 3.26.01 3.67.07 4.95.06 1.27.26 2.15.56 2.91.31.79.72 1.46 1.39 2.13.67.67 1.34 1.08 2.13 1.39.76.3 1.64.5 2.91.56C8.33 23.99 8.74 24 12 24c3.26 0 3.67-.01 4.95-.07 1.27-.06 2.15-.26 2.91-.56.79-.31 1.46-.72 2.13-1.39.67-.67 1.08-1.34 1.39-2.13.3-.76.5-1.64.56-2.91.06-1.28.07-1.69.07-4.95 0-3.26-.01-3.67-.07-4.95-.06-1.27-.26-2.15-.56-2.91-.31-.79-.72-1.46-1.39-2.13C21.32 1.35 20.65.94 19.86.63c-.76-.3-1.64-.5-2.91-.56C15.67.01 15.26 0 12 0zm0 5.84c-3.4 0-6.16 2.76-6.16 6.16s2.76 6.16 6.16 6.16 6.16-2.76 6.16-6.16S15.4 5.84 12 5.84zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.85-10.41c0 .8-.64 1.44-1.44 1.44s-1.44-.65-1.44-1.44.65-1.44 1.44-1.44 1.44.65 1.44 1.44z"/>',
        ],
        [
            'url'   => 'https://www.facebook.com/Tirea4Epingles',
            'label' => 'Facebook',
            'svg'   => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>',
        ],
        [
            'url'   => 'https://www.tiktok.com/@tirea.fr',
            'label' => 'TikTok',
            'svg'   => '<path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5.8 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.84 0z"/>',
        ],
        [
            'url'   => 'https://youtube.com/@tirea',
            'label' => 'YouTube',
            'svg'   => '<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>',
        ],
    ];

    // Liste blanche pour les SVG sociaux (path + attribut d uniquement)
    $svg_allowed = ['path' => ['d' => true]];
    ?>
    <ul class="tirea-socials" aria-label="Nos réseaux sociaux">
      <?php foreach ($socials as $social): ?>
        <li>
          <a href="<?php echo esc_url($social['url']); ?>"
             target="_blank"
             rel="noopener noreferrer"
             aria-label="<?php echo esc_attr($social['label']); ?> (nouvel onglet)">
            <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <?php echo wp_kses($social['svg'], $svg_allowed); ?>
            </svg>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php
}