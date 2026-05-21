<?php
/**
 * Template Réassurance Card Tirea
 * 
 * Rendu via shortcode [tirea_reassurance_card].
 * Grille de cards de réassurance détaillées (vers le bas de page).
 * Desktop : 4 colonnes. Tablette/mobile : 2 colonnes.
 */

if (!defined('ABSPATH')) exit;

// ============================================
// CONFIGURATION DES CARDS
// Chaque card = titre (h3), texte de description, SVG.
// Le titre supporte un <br> en utilisant le caractère "|" comme séparateur.
// ============================================

$tirea_cards = [
    [
        'title' => 'Expédition|sous 24h',
        'text'  => 'Votre commande est préparée et expédiée le jour même ou le lendemain.',
        'svg'   => '<path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/><path d="M7.5 4.27 16.5 9.27"/>',
    ],
    [
        'title' => 'Livraison|en 24 à 72h',
        'text'  => 'Votre commande livrée chez vous, partout en France métropolitaine.',
        'svg'   => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
    ],
    [
        'title' => 'SAV|Réactif',
        'text'  => 'Une équipe française à votre écoute, avec une réponse garantie sous 24 heures.',
        'svg'   => '<path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>',
    ],
    [
        'title' => 'Satisfait|ou Remboursé',
        'text'  => '14 jours pour changer d\'avis. Retour simple, sans justification ni question.',
        'svg'   => '<path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/>',
    ],
];
?>

<section class="tirea-reassurance-bottom" aria-label="Nos engagements">
  <ul class="tirea-reassurance-grid">
    <?php foreach ($tirea_cards as $card): ?>
      <?php // Découpe le titre sur "|" pour le br visuel — le texte SR est sans br ?>
      <?php $title_parts = explode('|', $card['title']); ?>
      <li class="tirea-reassurance-card">
        <svg class="tirea-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <?php echo $card['svg']; ?>
        </svg>
        <h3 class="tirea-card-title">
          <?php foreach ($title_parts as $i => $part): ?>
            <?php if ($i > 0): ?><br><?php endif; ?>
            <?php echo esc_html($part); ?>
          <?php endforeach; ?>
        </h3>
        <p class="tirea-card-text"><?php echo esc_html($card['text']); ?></p>
      </li>
    <?php endforeach; ?>
  </ul>
</section>