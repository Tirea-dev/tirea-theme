<?php
/**
 * Template page d'accueil Tirea — front-page.php
 *
 * Sort la home d'Elementor : rend les sections via shortcodes PHP directs,
 * sans le wrapper .elementor-shortcode qui imposait des règles CSS parasites
 * (.elementor img, .elementor *) et empêchait de décharger le CSS Elementor
 * et FontAwesome sur la page la plus importante du site.
 *
 * Modèle calqué sur woocommerce/single-product.php :
 *   header natif (header.php) → conteneur pleine largeur → enchaînement des
 *   shortcodes Tirea → footer natif (footer.php).
 *
 * Chaque section gère DÉJÀ en interne son propre centrage/max-width et son
 * breakout 100vw : le <main> est donc volontairement pleine largeur,
 * sans max-width ni padding latéral imposés, pour les laisser fonctionner.
 *
 * Réversibilité : supprimer ce fichier → la home Elementor (toujours
 * conservée en base comme filet de sécurité) reprend la main à l'identique.
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main class="tirea-home" style="width:100%;max-width:none;margin:0;padding:0;">
    <?php
    echo do_shortcode('[tirea_hero]');
    echo do_shortcode('[tirea_reassurance_pill]');
    echo do_shortcode('[tirea_ajusteur]');
    echo do_shortcode('[tirea_result]');
    echo do_shortcode('[tirea_guide variant="light"]');
    echo do_shortcode('[tirea_storytelling]');
    echo do_shortcode('[tirea_mini_selector id="758"]');
    echo do_shortcode('[tirea_faq]');
    echo do_shortcode('[tirea_reassurance_card]');
    ?>
</main>

<?php
get_footer();