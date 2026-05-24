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

// END ENQUEUE PARENT ACTION
// ============================================
// TIREA — Sélecteur de packs WooCommerce
// ============================================

function tirea_enqueue_product_assets() {
    if (is_product() || is_front_page()) {
        wp_enqueue_style(
            'tirea-product-css',
            get_stylesheet_directory_uri() . '/assets/css/tirea-product.css',
            ['tirea-tokens-css'],
            '1.0.0'
        );
    }

    wp_enqueue_script(
        'tirea-product-js',
        get_stylesheet_directory_uri() . '/assets/js/tirea-product.js',
        ['jquery'],
        '1.0.0',
        true
    );

    wp_localize_script('tirea-product-js', 'tireaData', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'checkout_url' => wc_get_checkout_url(),
        'cart_url' => wc_get_cart_url(),
    ]);
}
add_action('wp_enqueue_scripts', 'tirea_enqueue_product_assets');

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
    wp_enqueue_style(
        'tirea-header-css',
        get_stylesheet_directory_uri() . '/assets/css/tirea-header.css',
        ['tirea-tokens-css'],
        '1.0.0'
    );

    wp_enqueue_script(
        'tirea-header-js',
        get_stylesheet_directory_uri() . '/assets/js/tirea-header.js',
        [],
        '1.0.0',
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
    if (!is_front_page()) return;

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

function tirea_faq_shortcode() {
    ob_start();
    include get_stylesheet_directory() . '/tirea-faq.php';
    return ob_get_clean();
}
add_shortcode('tirea_faq', 'tirea_faq_shortcode');

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
 */
function tirea_page_uses_elementor() {
    if (!class_exists('\Elementor\Plugin')) return false;

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
 */
function tirea_dequeue_gutenberg_styles() {
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