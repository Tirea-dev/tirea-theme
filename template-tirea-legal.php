<?php
/**
 * Template Name: Tirea — Page Légale
 *
 * Gabarit assignable aux 7 pages légales (cgv, contact, livraison,
 * mentions-legales, notre-histoire, confidentialite, retours).
 *
 * On affiche DIRECTEMENT le shortcode [tirea_legal_page] (qui détecte
 * le slug de la page courante tout seul), et NON get_the_content() :
 * certaines pages contiennent encore de l'ancien texte collé après le
 * shortcode, qui s'affichait en double et non stylé. Rendre le shortcode
 * seul ignore ce résidu, sur toutes les pages.
 *
 * Le shortcode rend déjà <div class="tirea-legal-wrapper">…</div> avec son
 * propre breakout pleine largeur : aucun conteneur à ajouter ici.
 *
 * Réversibilité : réassigner l'ancien modèle côté admin → retour à l'état actuel.
 */

if (!defined('ABSPATH')) exit;

get_header();

if (have_posts()) {
    while (have_posts()) {
        the_post();
        echo do_shortcode('[tirea_legal_page]');
    }
}

get_footer();