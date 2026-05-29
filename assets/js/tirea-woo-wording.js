/**
 * TIREA — Wording WooCommerce Blocks (panier + checkout)
 * Les blocs Woo sont rendus en JavaScript : le filtre PHP gettext ne les
 * atteint pas. On intercepte donc les traductions ici, côté JS.
 */
( function () {
  var TIREA_WORDING = {
    'Gratuit': 'Offert',
    // 'GRATUIT' : 'OFFERT',
  };

  if ( ! window.wp || ! wp.hooks || ! wp.hooks.addFilter ) return;

  function tireaReplace( translation ) {
    if ( typeof translation === 'string' &&
         Object.prototype.hasOwnProperty.call( TIREA_WORDING, translation ) ) {
      return TIREA_WORDING[ translation ];
    }
    return translation;
  }

  wp.hooks.addFilter(
    'i18n.gettext_woocommerce',
    'tirea/wording',
    function ( translation ) { return tireaReplace( translation ); }
  );

  wp.hooks.addFilter(
    'i18n.gettext_with_context_woocommerce',
    'tirea/wording-ctx',
    function ( translation ) { return tireaReplace( translation ); }
  );
} )();