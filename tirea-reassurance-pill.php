<?php
/**
 * Template Réassurance Pilule Tirea
 * 
 * Rendu via shortcode [tirea_reassurance_pill].
 * Bandeau défilant infini de pilules de réassurance (stock, paiement, livraison...).
 * Desktop : 1 ligne avec les 6 items. Mobile : 2 lignes (impaires + paires) défilant en sens inverse.
 * 
 * Pause au survol (CSS), respect prefers-reduced-motion via CSS.
 */

if (!defined('ABSPATH')) exit;

// ============================================
// LISTE BLANCHE D'ÉCHAPPEMENT POUR LES SVG
// Utilisée par wp_kses() pour sortir les SVG inline en toute sécurité.
// Couvre les balises/attributs réellement présents dans $tirea_reassurance_items.
// ============================================

$tirea_svg_allowed = [
    'svg' => [
        'class'         => true,
        'viewbox'       => true,
        'fill'          => true,
        'stroke'        => true,
        'stroke-width'  => true,
        'stroke-linecap'=> true,
        'stroke-linejoin'=> true,
        'aria-hidden'   => true,
    ],
    'path' => [
        'd'             => true,
        'fill'          => true,
        'stroke'        => true,
        'stroke-width'  => true,
        'stroke-linecap'=> true,
        'stroke-linejoin'=> true,
        'transform'     => true,
    ],
    'circle' => [
        'cx'            => true,
        'cy'            => true,
        'r'             => true,
        'fill'          => true,
        'stroke'        => true,
        'stroke-width'  => true,
        'transform'     => true,
    ],
    'rect' => [
        'x'             => true,
        'y'             => true,
        'width'         => true,
        'height'        => true,
        'rx'            => true,
        'ry'            => true,
        'fill'          => true,
        'stroke'        => true,
        'stroke-width'  => true,
        'transform'     => true,
    ],
    'line' => [
        'x1'            => true,
        'y1'            => true,
        'x2'            => true,
        'y2'            => true,
        'stroke'        => true,
        'stroke-width'  => true,
        'stroke-linecap'=> true,
        'transform'     => true,
    ],
    'polyline' => [
        'points'        => true,
        'fill'          => true,
        'stroke'        => true,
        'stroke-width'  => true,
        'stroke-linecap'=> true,
        'stroke-linejoin'=> true,
        'transform'     => true,
    ],
    'polygon' => [
        'points'        => true,
        'fill'          => true,
        'stroke'        => true,
        'stroke-width'  => true,
        'stroke-linecap'=> true,
        'stroke-linejoin'=> true,
        'transform'     => true,
    ],
    'g' => [
        'fill'          => true,
        'stroke'        => true,
        'stroke-width'  => true,
        'transform'     => true,
    ],
];

// ============================================
// CONFIGURATION DES ITEMS
// Modifie ici l'ordre, le texte ou les SVG pour changer le contenu
// ============================================

$tirea_reassurance_items = [
    [
        'label' => 'Stock en France',
        'svg'   => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
    ],
    [
        'label' => 'Paiement sécurisé',
        'svg'   => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
    ],
    [
        'label' => 'Expédié sous 24h',
        'svg'   => '<path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>',
    ],
    [
        'label' => 'Livraison en 48h',
        'svg'   => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
    ],
    [
        'label' => 'Satisfait ou remboursé',
        'svg'   => '<path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/>',
    ],
    [
        'label' => 'SAV réponse sous 24h',
        'svg'   => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
    ],
];

/**
 * Génère le markup d'une pilule
 * @param array $item ['label' => string, 'svg' => string]
 */
$tirea_render_pill = function($item) use ($tirea_svg_allowed) {
    ?>
    <div class="tirea-reassurance-item">
      <svg class="tirea-reassurance-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <?php echo wp_kses($item['svg'], $tirea_svg_allowed); ?>
      </svg>
      <span class="tirea-reassurance-text"><strong><?php echo esc_html($item['label']); ?></strong></span>
    </div>
    <?php
};

// Pré-calcul des sous-ensembles pour la version mobile
// Indices impairs (0, 2, 4) = items 1/3/5 = ligne 1 mobile
// Indices pairs   (1, 3, 5) = items 2/4/6 = ligne 2 mobile
$tirea_reassurance_odd  = [$tirea_reassurance_items[0], $tirea_reassurance_items[2], $tirea_reassurance_items[4]];
$tirea_reassurance_even = [$tirea_reassurance_items[1], $tirea_reassurance_items[3], $tirea_reassurance_items[5]];
?>

<?php // role="region" + aria-label pour décrire la zone aux lecteurs d'écran ?>
<section class="tirea-reassurance" role="region" aria-label="Nos garanties">

  <?php // ===== DESKTOP / TABLETTE : 1 ligne avec tous les items, dupliqués pour la boucle infinie ===== ?>
  <div class="tirea-reassurance-row tirea-reassurance-row-desktop" aria-hidden="true">
    <?php // 2 itérations pour assurer le défilement infini (translateX -50% = retour au début) ?>
    <?php for ($i = 0; $i < 2; $i++): ?>
      <?php foreach ($tirea_reassurance_items as $item) $tirea_render_pill($item); ?>
    <?php endfor; ?>
  </div>

  <?php // ===== MOBILE LIGNE 1 : items impairs (Stock, Expédié, Satisfait) ===== ?>
  <div class="tirea-reassurance-row tirea-reassurance-row-mobile" aria-hidden="true">
    <?php for ($i = 0; $i < 2; $i++): ?>
      <?php foreach ($tirea_reassurance_odd as $item) $tirea_render_pill($item); ?>
    <?php endfor; ?>
  </div>

  <?php // ===== MOBILE LIGNE 2 : items pairs (Paiement, Livraison, SAV), défile en sens inverse ===== ?>
  <div class="tirea-reassurance-row tirea-reassurance-row-mobile" aria-hidden="true">
    <?php for ($i = 0; $i < 2; $i++): ?>
      <?php foreach ($tirea_reassurance_even as $item) $tirea_render_pill($item); ?>
    <?php endfor; ?>
  </div>

  <?php // ===== Version texte accessible pour SR/SEO (lue une seule fois, pas dupliquée) ===== ?>
  <ul class="tirea-reassurance-sr">
    <?php foreach ($tirea_reassurance_items as $item): ?>
      <li><?php echo esc_html($item['label']); ?></li>
    <?php endforeach; ?>
  </ul>

</section>