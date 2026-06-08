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

        <?php tirea_render_socials(); ?>
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

        <?php // Form Brevo prêt à brancher - pour l'instant, mode "inactif" avec message ?>
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

        <?php // Zone d'affichage des messages de succès/erreur - gérée par le JS ?>
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