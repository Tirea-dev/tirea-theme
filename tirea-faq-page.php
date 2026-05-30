<?php
/**
 * Template page FAQ Tirea — tirea-faq-page.php
 *
 * Gabarit de la page /faq (slug "faq", page WordPress ID 25, vide), routé
 * automatiquement par template_include dans functions.php (modèle identique
 * à /suivi : zéro assignation manuelle côté admin).
 *
 * Affiche la FAQ COMPLÈTE via [tirea_faq mode="full"] : toutes les questions,
 * la recherche et le formulaire de contact. La section .tirea-faq gère elle-même
 * son centrage (max-width interne) : le <main> est donc volontairement pleine
 * largeur, sans max-width ni padding latéral imposés.
 *
 * Réversibilité : retirer le routage dans functions.php → la page /faq (vide)
 * reprend le gabarit par défaut du thème.
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main class="tirea-faq-page-main" style="width:100%;max-width:none;margin:0;padding:0;">
    <?php echo do_shortcode('[tirea_faq mode="full"]'); ?>
</main>

<?php
get_footer();