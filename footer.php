<?php
/**
 * Footer du thème enfant Tirea
 * 
 * Override du footer.php parent (Hello Elementor).
 * Injecte le shortcode [tirea_footer], puis ferme </body></html>.
 * 
 * Remplace l'usage d'UAE Theme Builder qui forçait le chargement d'Elementor
 * sur toutes les pages juste pour render ce shortcode.
 */

if (!defined('ABSPATH')) exit;
?>

<?php echo do_shortcode('[tirea_footer]'); ?>

<?php wp_footer(); ?>
</body>
</html>