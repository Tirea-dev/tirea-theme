<?php
/**
 * Template fiche produit Tirea — override WooCommerce
 *
 * Ce fichier remplace le template par défaut de WooCommerce
 * pour afficher notre sélecteur custom à la place.
 */
if (!defined('ABSPATH')) exit;

get_header('shop');

while (have_posts()) :
    the_post();
    global $product;
    if (!$product || !$product->is_type('variable')) {
        // Si ce n'est pas un produit variable, on utilise le template WooCommerce standard
        wc_get_template_part('content', 'single-product');
    } else {
        // Notre template custom
        echo do_shortcode('[tirea_product_selector id="' . $product->get_id() . '"]');
        echo do_shortcode('[tirea_faq contact="off" more="off" badge="off"]');
    }
endwhile;

get_footer('shop');