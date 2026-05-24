<?php
/**
 * Header du thème enfant Tirea
 * 
 * Override du header.php parent (Hello Elementor).
 * Affiche le <head>, ouvre <body>, puis injecte le shortcode [tirea_header].
 * 
 * Remplace l'usage d'UAE Theme Builder qui forçait le chargement d'Elementor
 * sur toutes les pages juste pour render ce shortcode.
 */

if (!defined('ABSPATH')) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php echo do_shortcode('[tirea_header]'); ?>