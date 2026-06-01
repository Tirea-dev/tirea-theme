<?php
/**
 * Template Footer Tirea
 * 
 * Rendu via shortcode [tirea_footer].
 * Footer global du site : 4 colonnes (marque + socials, boutique, assistance, newsletter)
 * + barre inférieure (copyright, liens légaux, moyens de paiement).
 * 
 * Newsletter : form non connecté pour l'instant.
 * Pour brancher Brevo, modifier $tirea_newsletter_action et $tirea_newsletter_list_id ci-dessous.
 */

if (!defined('ABSPATH')) exit;

// ============================================
// CONFIGURATION DU FOOTER
// Modifie ici pour changer le contenu sans toucher au markup
// ============================================

// === Branding ===
$tirea_footer_logo = [
    'url' => 'https://tirea.fr/wp-content/uploads/2026/05/Logo-Last-5x4-1.png',
    'alt' => 'TIREA',
];
$tirea_footer_tagline = "L'élégance invisible.|La marque française qui redéfinit l'élégance.";

// === Réseaux sociaux ===
// Chaque entrée : ['url' => ..., 'label' => ..., 'svg' => path SVG inline]
$tirea_socials = [
    [
        'url'   => 'https://www.instagram.com/tirea.fr',
        'label' => 'Instagram',
        'svg'   => '<path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41-.56-.22-.96-.48-1.38-.9-.42-.42-.68-.82-.9-1.38-.16-.42-.36-1.06-.41-2.23-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41 1.27-.06 1.65-.07 4.85-.07M12 0C8.74 0 8.33.01 7.05.07 5.78.13 4.9.33 4.14.63c-.79.31-1.46.72-2.13 1.39C1.35 2.68.94 3.35.63 4.14.33 4.9.13 5.78.07 7.05.01 8.33 0 8.74 0 12c0 3.26.01 3.67.07 4.95.06 1.27.26 2.15.56 2.91.31.79.72 1.46 1.39 2.13.67.67 1.34 1.08 2.13 1.39.76.3 1.64.5 2.91.56C8.33 23.99 8.74 24 12 24c3.26 0 3.67-.01 4.95-.07 1.27-.06 2.15-.26 2.91-.56.79-.31 1.46-.72 2.13-1.39.67-.67 1.08-1.34 1.39-2.13.3-.76.5-1.64.56-2.91.06-1.28.07-1.69.07-4.95 0-3.26-.01-3.67-.07-4.95-.06-1.27-.26-2.15-.56-2.91-.31-.79-.72-1.46-1.39-2.13C21.32 1.35 20.65.94 19.86.63c-.76-.3-1.64-.5-2.91-.56C15.67.01 15.26 0 12 0zm0 5.84c-3.4 0-6.16 2.76-6.16 6.16s2.76 6.16 6.16 6.16 6.16-2.76 6.16-6.16S15.4 5.84 12 5.84zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.85-10.41c0 .8-.64 1.44-1.44 1.44s-1.44-.65-1.44-1.44.65-1.44 1.44-1.44 1.44.65 1.44 1.44z"/>',
    ],
    [
        'url'   => 'https://www.facebook.com/Tirea4Epingles',
        'label' => 'Facebook',
        'svg'   => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>',
    ],
    [
        'url'   => 'https://www.tiktok.com/@tirea.fr',
        'label' => 'TikTok',
        'svg'   => '<path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5.8 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.84 0z"/>',
    ],
    [
        'url'   => 'https://youtube.com/@tirea',
        'label' => 'YouTube',
        'svg'   => '<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>',
    ],
];

// === Colonnes de liens ===
$tirea_footer_columns = [
    [
        'title' => 'Boutique',
        'links' => [
            ['label' => 'Notre histoire', 'url' => '/notre-histoire'],
            ['label' => "L'Ajusteur",      'url' => '/produit/lajusteur-tirea/'],
            ['label' => 'Suivi de commande', 'url' => '/suivi'],
        ],
    ],
    [
        'title' => 'Assistance',
        'links' => [
            ['label' => 'Contact',                  'url' => '/contact'],
            ['label' => 'Livraison',                'url' => '/livraison'],
            ['label' => 'Retours & remboursements', 'url' => '/retours'],
        ],
    ],
];

// === Newsletter ===
$tirea_newsletter_text  = "Recevez nos offres exclusives et découvrez nos nouveautés en avant-première.";
$tirea_newsletter_legal = "En vous inscrivant, vous acceptez de recevoir nos offres commerciales. Vous pouvez vous désinscrire à tout moment.";

// Quand tu créeras ton compte Brevo, remplace ces 2 valeurs :
$tirea_newsletter_action  = ''; // URL du form Brevo (vide = mode inactif, message affiché)
$tirea_newsletter_list_id = ''; // ID de la liste Brevo

// === Barre inférieure ===
$tirea_footer_copyright = '© ' . date('Y') . ' TIREA™';
$tirea_legal_links = [
    ['label' => 'CGV',             'url' => '/cgv'],
    ['label' => 'Mentions légales', 'url' => '/mentions-legales'],
    ['label' => 'Confidentialité', 'url' => '/confidentialite'],
];

// === Moyens de paiement ===
$tirea_payments = [
    ['name' => 'Visa',       'url' => 'https://tirea.fr/wp-content/uploads/2026/05/Visa_Inc._logo_2021–present.svg'],
    ['name' => 'Mastercard', 'url' => 'https://tirea.fr/wp-content/uploads/2026/05/Mastercard-logo.svg'],
    ['name' => 'PayPal',     'url' => 'https://tirea.fr/wp-content/uploads/2026/05/PayPal_logo.svg'],
    ['name' => 'Apple Pay',  'url' => 'https://tirea.fr/wp-content/uploads/2026/05/Apple_Pay_logo.svg'],
    ['name' => 'Google Pay', 'url' => 'https://tirea.fr/wp-content/uploads/2026/05/Google_Pay_Logo.svg'],
];

// Découpage tagline sur "|" pour rendu <br>
$tirea_tagline_parts = explode('|', $tirea_footer_tagline);

// Liste blanche d'éléments/attributs autorisés pour les SVG sociaux
// (même pattern que tirea-reassurance-pill.php / tirea-reassurance-card.php)
$tirea_svg_allowed = [
    'path' => [
        'd' => true,
    ],
];
?>

<footer class="tirea-footer" role="contentinfo">
  <div class="tirea-footer-inner">

    <div class="tirea-footer-grid">

      <?php // ===== COLONNE 1 : MARQUE ===== ?>
      <div class="tirea-footer-col tirea-col-brand">

        <a href="/" class="tirea-footer-logo-link" aria-label="Retour à l'accueil TIREA">
          <img src="<?php echo esc_url($tirea_footer_logo['url']); ?>"
               alt="<?php echo esc_attr($tirea_footer_logo['alt']); ?>"
               class="tirea-footer-logo"
               width="170" height="85"
               loading="lazy"
               decoding="async">
        </a>

        <p class="tirea-footer-tagline">
          <?php foreach ($tirea_tagline_parts as $i => $part): ?>
            <?php if ($i > 0): ?><br><?php endif; ?>
            <?php echo esc_html($part); ?>
          <?php endforeach; ?>
        </p>

        <ul class="tirea-socials" aria-label="Nos réseaux sociaux">
          <?php foreach ($tirea_socials as $social): ?>
            <li>
              <a href="<?php echo esc_url($social['url']); ?>"
                 target="_blank"
                 rel="noopener noreferrer"
                 aria-label="<?php echo esc_attr($social['label']); ?> (nouvel onglet)">
                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <?php echo wp_kses($social['svg'], $tirea_svg_allowed); ?>
                </svg>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <?php // ===== COLONNES 2 & 3 : LIENS ===== ?>
      <?php foreach ($tirea_footer_columns as $col): ?>
        <nav class="tirea-footer-col" aria-label="<?php echo esc_attr($col['title']); ?>">
          <h3 class="tirea-footer-title"><?php echo esc_html($col['title']); ?></h3>
          <ul class="tirea-footer-links">
            <?php foreach ($col['links'] as $link): ?>
              <li>
                <a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['label']); ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        </nav>
      <?php endforeach; ?>

      <?php // ===== COLONNE 4 : NEWSLETTER ===== ?>
      <div class="tirea-footer-col">
        <h3 class="tirea-footer-title">Restez informé</h3>
        <p class="tirea-newsletter-text"><?php echo esc_html($tirea_newsletter_text); ?></p>

        <?php // Form Brevo prêt à brancher — pour l'instant, mode "inactif" avec message ?>
        <form class="tirea-newsletter-form"
              <?php if ($tirea_newsletter_action): ?>
                action="<?php echo esc_url($tirea_newsletter_action); ?>"
                method="post"
              <?php endif; ?>
              data-tirea-newsletter
              novalidate>
          <label for="tirea-newsletter-email" class="tirea-sr-only">Adresse email</label>
          <input id="tirea-newsletter-email"
                 type="email"
                 name="EMAIL"
                 placeholder="Votre adresse email"
                 autocomplete="email"
                 required>
          <?php if ($tirea_newsletter_list_id): ?>
            <input type="hidden" name="list_id" value="<?php echo esc_attr($tirea_newsletter_list_id); ?>">
          <?php endif; ?>
          <button type="submit">S'inscrire</button>
        </form>

        <?php // Zone d'affichage des messages de succès/erreur — gérée par le JS ?>
        <p class="tirea-newsletter-feedback" role="status" aria-live="polite"></p>

        <p class="tirea-newsletter-legal"><?php echo esc_html($tirea_newsletter_legal); ?></p>
      </div>

    </div>

    <?php // ===== BARRE INFÉRIEURE ===== ?>
    <div class="tirea-footer-bottom">

      <div class="tirea-footer-bottom-left">
        <span><?php echo esc_html($tirea_footer_copyright); ?></span>
        <?php foreach ($tirea_legal_links as $link): ?>
          <a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['label']); ?></a>
        <?php endforeach; ?>
      </div>

      <ul class="tirea-payments" aria-label="Moyens de paiement acceptés">
        <?php foreach ($tirea_payments as $payment): ?>
          <li class="tirea-pay-logo">
            <img src="<?php echo esc_url($payment['url']); ?>"
                 alt="<?php echo esc_attr($payment['name']); ?>"
                 loading="lazy"
                 decoding="async"
                 width="44" height="28">
          </li>
        <?php endforeach; ?>
      </ul>

    </div>

  </div>
</footer>