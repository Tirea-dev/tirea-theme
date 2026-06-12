<?php
/**
 * Template Header Tirea
 * 
 * Rendu via shortcode [tirea_header] depuis Elementor/UAE.
 * Contient : banner promo + header normal + header sticky 
 * + drawer mobile + overlay recherche + bouton remonter.
 * 
 * Architecture mutualisée :
 * - Les liens, icônes et SVG sont définis UNE seule fois en haut
 * - Le HTML est généré pour les 2 modes (normal + sticky) via les partials internes
 */

if (!defined('ABSPATH')) exit;

// ============================================
// CONFIGURATION DU MENU
// Modifie ici pour ajouter/retirer/modifier des liens
// ============================================

$tirea_menu_main = [
    ['url' => '/',                          'slug' => '',                          'label' => 'Accueil'],
    ['url' => '/produit/lajusteur-tirea/',  'slug' => 'produit/lajusteur-tirea',   'label' => "L'Ajusteur"],
    ['url' => '/suivi',                     'slug' => 'suivi',                     'label' => 'Suivi'],
    ['url' => '/contact',                   'slug' => 'contact',                   'label' => 'Contact'],
];

$tirea_menu_legal = [
    ['url' => '/notre-histoire',         'slug' => 'notre-histoire',         'label' => 'Notre histoire'],
    ['url' => '/cgv',              'slug' => 'cgv',              'label' => 'Conditions Générales de Vente'],
    ['url' => '/livraison',        'slug' => 'livraison',        'label' => 'Livraison'],
    ['url' => '/retours',          'slug' => 'retours',          'label' => 'Politique de retour'],
    ['url' => '/mentions-legales', 'slug' => 'mentions-legales', 'label' => 'Mentions légales'],
    ['url' => '/confidentialite',  'slug' => 'confidentialite',  'label' => 'Politique de confidentialité'],
];

// Logo (modifie ici pour changer de logo)
$tirea_logo_url = 'https://tirea.fr/wp-content/uploads/2026/05/Logo-Last-5x4-1.png';
$tirea_logo_alt = 'TIREA';

// Bandeau promo (passe à false ou string vide pour le désactiver)
$tirea_promo_text = "EXCLUSIVITÉ BOUTIQUE OFFICIELLE : JUSQU'À -25€ & LIVRAISON 48H OFFERTE !";

// ============================================
// SVG ICONS (centralisés, réutilisés via les partials)
// Stockés dans un tableau pour éviter la duplication HTML
// ============================================

$tirea_svg = [
    'account' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/></svg>',
    'search'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>',
    'cart'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
    'close'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
    'chevron' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>',
    'arrow_up'=> '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>',
];

/**
 * Partial : génère un bloc header (mode normal OU sticky)
 * @param string $mode 'normal' ou 'sticky'
 * @param array  $deps tableau passé par référence avec menus, svg, logo
 */
$tirea_render_header_block = function($mode, $deps) {
    // Préfixe les classes/IDs selon le mode pour que CSS et JS ciblent indépendamment
    $prefix = ($mode === 'sticky') ? 'tirea-sticky-' : 'tirea-';
    $id_pfx = ($mode === 'sticky') ? 'tireaSticky'   : 'tirea';
    $wrapper_class = ($mode === 'sticky') ? 'tirea-sticky-header' : 'tirea-header';
    $wrapper_attrs = ($mode === 'sticky') ? ' id="tireaStickyHeader" aria-hidden="true"' : '';
    // Le logo ne doit jamais concurrencer le vrai LCP de la page :
    // - Home : le LCP est l'image du hero (preload + fetchpriority dans le <head>).
    // - Fiche produit : le LCP est l'image principale du produit (preload + fetchpriority).
    // Sur ces deux pages, le logo perd son signal de priorité. Ailleurs (pages légales,
    // FAQ, suivi...), il reste un candidat LCP légitime et garde sa priorité haute.
    if ($mode === 'normal') {
        $tirea_logo_no_priority = is_front_page() || (function_exists('is_product') && is_product());
        $logo_priority = $tirea_logo_no_priority ? '' : ' fetchpriority="high"';
    } else {
        $logo_priority = ' loading="lazy"';
    }
    ?>
    <<?php echo ($mode === 'normal') ? 'header' : 'div'; ?> class="<?php echo esc_attr($wrapper_class); ?>"<?php echo $wrapper_attrs; ?><?php echo ($mode === 'normal') ? ' role="banner"' : ''; ?>>

      <?php // === COLONNE 1 : LOGO === ?>
      <div class="<?php echo esc_attr($prefix); ?>header-logo<?php echo ($mode === 'sticky') ? ' tirea-sticky-logo' : ''; ?>">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="<?php echo esc_attr($prefix); ?>logo-link" aria-label="Accueil TIREA">
          <img src="<?php echo esc_url($deps['logo_url']); ?>"
               alt="<?php echo esc_attr($deps['logo_alt']); ?>"
               class="<?php echo esc_attr($prefix); ?>logo<?php echo ($mode === 'sticky') ? '-img' : ''; ?>"
               width="85" height="68"<?php echo $logo_priority; ?>>
        </a>
      </div>

      <?php // === COLONNE 2 : MENU CENTRAL === ?>
      <div class="<?php echo esc_attr($prefix); ?>header-<?php echo ($mode === 'sticky') ? 'center' : 'nav'; ?>">
        <nav class="<?php echo esc_attr($prefix); ?>nav-pill" id="<?php echo esc_attr($id_pfx); ?>NavPill" aria-label="Navigation principale">
          <?php foreach ($deps['menu_main'] as $item): ?>
            <a href="<?php echo esc_url($item['url']); ?>" data-slug="<?php echo esc_attr($item['slug']); ?>"><?php echo esc_html($item['label']); ?></a>
          <?php endforeach; ?>
        </nav>

        <?php if ($mode === 'sticky'): // Bouton remonter (visible mobile, centré dans le sticky) ?>
          <button type="button" class="tirea-back-to-top tirea-back-to-top-mobile" id="tireaBackToTopMobile" aria-label="Remonter en haut">
            <?php echo $deps['svg']['arrow_up']; ?>
          </button>
        <?php endif; ?>
      </div>

      <?php // === COLONNE 3 : ICÔNES + BURGERS === ?>
      <div class="<?php echo esc_attr($prefix); ?>header-icons<?php echo ($mode === 'sticky') ? ' tirea-sticky-icons' : ''; ?>">

        <?php // Lien Mon compte ?>
        <a href="<?php echo esc_url('/mon-compte'); ?>" class="<?php echo esc_attr($prefix); ?>icon-link <?php echo esc_attr($prefix); ?>icon-account" aria-label="Mon compte">
          <?php echo $deps['svg']['account']; ?>
        </a>

        <?php // Bouton Recherche ?>
        <button type="button" class="<?php echo esc_attr($prefix); ?>icon-link <?php echo esc_attr($prefix); ?>icon-search" id="<?php echo esc_attr($id_pfx); ?>SearchToggle" aria-label="Ouvrir la recherche">
          <?php echo $deps['svg']['search']; ?>
        </button>

        <?php // Lien Panier (+ pastille de présence, allumée en JS via le cookie Woo) ?>
        <a href="<?php echo esc_url(function_exists('wc_get_cart_url') ? wc_get_cart_url() : '/panier'); ?>" class="<?php echo esc_attr($prefix); ?>icon-link tirea-cart-link" aria-label="Panier">
          <?php echo $deps['svg']['cart']; ?>
          <span class="tirea-cart-dot" aria-hidden="true"></span>
        </a>

        <?php // Burger desktop + dropdown "Informations" ?>
        <div class="<?php echo esc_attr($prefix); ?>burger-desktop-wrapper">
          <button class="<?php echo esc_attr($prefix); ?>burger-desktop" id="<?php echo esc_attr($id_pfx); ?>BurgerDesktop" aria-label="Menu informations" aria-expanded="false" aria-controls="<?php echo esc_attr($id_pfx); ?>Dropdown">
            <span></span><span></span><span></span>
          </button>
          <div class="<?php echo esc_attr($prefix); ?>dropdown" id="<?php echo esc_attr($id_pfx); ?>Dropdown" role="menu">
            <div class="tirea-dropdown-label">Informations</div>
            <?php foreach ($deps['menu_legal'] as $item): ?>
              <a href="<?php echo esc_url($item['url']); ?>" data-slug="<?php echo esc_attr($item['slug']); ?>" role="menuitem"><?php echo esc_html($item['label']); ?></a>
            <?php endforeach; ?>
          </div>
        </div>

        <?php // Burger mobile ?>
        <button class="<?php echo esc_attr($prefix); ?>burger-mobile" id="<?php echo esc_attr($id_pfx); ?>BurgerMobile" aria-label="Menu" aria-expanded="false" aria-controls="tireaMobileMenu">
          <span></span><span></span><span></span>
        </button>

      </div>

    </<?php echo ($mode === 'normal') ? 'header' : 'div'; ?>>
    <?php
};

// On regroupe les dépendances pour les passer au partial
$tirea_deps = [
    'logo_url'   => $tirea_logo_url,
    'logo_alt'   => $tirea_logo_alt,
    'menu_main'  => $tirea_menu_main,
    'menu_legal' => $tirea_menu_legal,
    'svg'        => $tirea_svg,
];
?>

<?php // Skip link (a11y obligatoire WCAG 2.1) ?>
<a class="tirea-skip-link" href="#tirea-main-content">Aller au contenu principal</a>

<?php // === BANNIÈRE PROMO === ?>
<?php if (!empty($tirea_promo_text)): ?>
  <div class="tirea-promo-bar" role="region" aria-label="Offre en cours">
    <?php echo esc_html($tirea_promo_text); ?>
  </div>
<?php endif; ?>

<?php // === HEADER NORMAL === ?>
<?php $tirea_render_header_block('normal', $tirea_deps); ?>

<?php // === HEADER STICKY === ?>
<?php $tirea_render_header_block('sticky', $tirea_deps); ?>

<?php // === BOUTON REMONTER DESKTOP (flottant) === ?>
<button type="button" class="tirea-back-to-top tirea-back-to-top-desktop" id="tireaBackToTopDesktop" aria-label="Remonter en haut de page">
  <?php echo $tirea_svg['arrow_up']; ?>
</button>

<?php // === OVERLAY RECHERCHE === ?>
<div class="tirea-search-overlay" id="tireaSearchOverlay" aria-hidden="true" role="dialog" aria-label="Recherche sur le site" aria-modal="true">
  <form class="tirea-search-form" action="<?php echo esc_url(home_url('/')); ?>" method="get" role="search">
    <button type="button" class="tirea-search-close" id="tireaSearchClose" aria-label="Fermer la recherche">
      <?php echo $tirea_svg['close']; ?>
    </button>
    <div class="tirea-search-inner">
      <label for="tireaSearchInput" class="tirea-search-label">Rechercher sur le site</label>
      <div class="tirea-search-field">
        <span class="tirea-search-icon"><?php echo $tirea_svg['search']; ?></span>
        <input type="search" id="tireaSearchInput" name="s" placeholder="Que recherchez-vous ?" autocomplete="off" required>
        <button type="submit" class="tirea-search-submit">Rechercher</button>
      </div>
      <p class="tirea-search-hint">Appuyez sur <kbd>Entrée</kbd> pour valider · <kbd>Échap</kbd> pour fermer</p>
    </div>
  </form>
</div>

<?php // === DRAWER MOBILE === ?>
<div class="tirea-mobile-menu" id="tireaMobileMenu" aria-hidden="true" role="dialog" aria-label="Menu de navigation" aria-modal="true">
  <div class="tirea-mobile-menu-inner">

    <button class="tirea-close" id="tireaClose" aria-label="Fermer le menu">
      <?php echo $tirea_svg['close']; ?>
    </button>

    <nav class="tirea-mobile-nav" aria-label="Navigation mobile">
      <?php
      // Construction de la liste mobile : menu principal + raccourcis + accordéon
      $anim = 1;
      foreach ($tirea_menu_main as $item):
      ?>
        <a href="<?php echo esc_url($item['url']); ?>" data-slug="<?php echo esc_attr($item['slug']); ?>" class="tirea-mobile-link" data-anim="<?php echo $anim++; ?>"><?php echo esc_html($item['label']); ?></a>
      <?php endforeach; ?>

      <?php // "Notre histoire" est dans menu_legal mais on l'expose aussi en mobile ?>
      <a href="<?php echo esc_url($tirea_menu_legal[0]['url']); ?>" data-slug="<?php echo esc_attr($tirea_menu_legal[0]['slug']); ?>" class="tirea-mobile-link" data-anim="<?php echo $anim++; ?>"><?php echo esc_html($tirea_menu_legal[0]['label']); ?></a>

      <button type="button" class="tirea-mobile-link tirea-mobile-search-btn" data-anim="<?php echo $anim++; ?>" id="tireaMobileSearchBtn">Recherche</button>
      <a href="<?php echo esc_url(function_exists('wc_get_cart_url') ? wc_get_cart_url() : '/panier'); ?>" class="tirea-mobile-link" data-anim="<?php echo $anim++; ?>">Panier</a>
      <a href="<?php echo esc_url('/mon-compte'); ?>" class="tirea-mobile-link" data-anim="<?php echo $anim++; ?>">Mon compte</a>

      <button class="tirea-mobile-link tirea-accordion-toggle" data-anim="<?php echo $anim++; ?>" id="tireaAccordionToggle" aria-expanded="false" aria-controls="tireaAccordionContent">
        Légal
        <span class="tirea-accordion-arrow"><?php echo $tirea_svg['chevron']; ?></span>
      </button>
      <div class="tirea-accordion-content" id="tireaAccordionContent">
        <?php // On boucle sur menu_legal en sautant le 1er (déjà affiché ci-dessus comme "Notre histoire") ?>
        <?php foreach (array_slice($tirea_menu_legal, 1) as $item): ?>
          <a href="<?php echo esc_url($item['url']); ?>" data-slug="<?php echo esc_attr($item['slug']); ?>" class="tirea-mobile-sublink"><?php echo esc_html($item['label']); ?></a>
        <?php endforeach; ?>
      </div>
    </nav>

  </div>
</div>

<?php // Ancre cible du skip link — placée après tout le header, le focus saute ici ?>
<span id="tirea-main-content" tabindex="-1" class="tirea-skip-target"></span>