<?php
/**
 * Template FAQ Tirea
 *
 * Rendu via shortcode [tirea_faq].
 * Accordéon natif <details>/<summary>, recherche client-side,
 * formulaire de contact via wp_mail() avec honeypot anti-spam.
 */

if (!defined('ABSPATH')) exit;

// ============================================
// CONFIGURATION — modifier ici pour ajouter / éditer des questions
// ============================================

$tirea_faq_badge    = "Centre d'aide TIREA";
$tirea_faq_title    = "Vos questions,";
$tirea_faq_title_em = "nos réponses";
$tirea_faq_intro    = "Tout ce qu'il faut savoir sur l'Ajusteur TIREA™ : concept, matières, livraison et retours. Si vous ne trouvez pas la réponse à votre question, écrivez-nous : notre équipe vous répond sous 24h.";

// Email destinataire du formulaire de contact
$tirea_faq_contact_email = 'contact@tirea.fr';

// Bloc contact
$tirea_faq_contact_badge = "Réponse sous 24h";
$tirea_faq_contact_title = "Une autre question ?";
$tirea_faq_contact_em    = "Posez-la nous.";
$tirea_faq_contact_lead  = "Notre équipe vous répond personnellement par e-mail, tous les jours, sous 24 heures.";

// Liste des questions — chaque item :
//   - q       : la question (texte)
//   - a       : la réponse (HTML autorisé : <p>, <b>, <em>, <ul>/<li>)
//   - tags    : labels affichés sous la réponse
//   - keywords: mots-clés supplémentaires pour la recherche (non affichés)
$tirea_faq_items = [
    [
        'q' => "L'Ajusteur TIREA™, c'est quoi exactement ?",
        'a' => '<p>L\'Ajusteur TIREA™ est un accessoire pensé comme une <em>ceinture invisible</em> qui se fixe entre votre chemise et votre pantalon. Il maintient votre chemise parfaitement rentrée, toute la journée, sans avoir à la rajuster.</p>
                <p>Il s\'ancre délicatement au dernier bouton de votre chemise et reste totalement invisible une fois le pantalon enfilé.</p>',
        'tags' => ['Produit', '30s à comprendre'],
        'keywords' => "ajusteur tirea c'est quoi accessoire chemise ceinture invisible",
    ],
    [
        'q' => "En quelles matières est-il fabriqué ?",
        'a' => '<p>Trois matériaux choisis pour leur durabilité :</p>
                <ul>
                  <li>Élastique gros grain : souple, résistant et conçu pour conserver sa tension dans le temps.</li>
                  <li>Acier inoxydable : pour les éléments d\'attache, garanti sans rouille.</li>
                  <li>Cuir synthétique Skyvertex : au toucher cuir, doté de propriétés antidérapantes pour un ancrage parfait.</li>
                </ul>',
        'tags' => ['Matières', 'Durabilité'],
        'keywords' => "matière fabrication élastique acier inoxydable cuir skyvertex antidérapant",
    ],
    [
        'q' => "Est-il visible une fois porté ?",
        'a' => '<p>Porté correctement, l\'Ajusteur reste sous votre pantalon, il est <em>parfaitement invisible</em>, même avec une chemise blanche fine.</p>
                <p>Un mode d\'emploi clair en <b>3 étapes et 30 secondes</b> est fourni avec chaque commande.</p>',
        'tags' => ['Confort', 'Discrétion'],
        'keywords' => "visible porté discret invisible sous pantalon mode emploi",
    ],
    [
        'q' => "Comment choisir la bonne taille et éviter qu'il glisse ?",
        'a' => '<p>L\'Ajusteur TIREA™ est en <b>taille unique</b>. Le système de réglage intégré permet de l\'ajuster à votre tour de taille en quelques secondes.</p>
                <p>Une fois réglé, la pression élastique combinée à l\'ancrage sur le dernier bouton empêche votre chemise de remonter ou de glisser, même en mouvement.</p>',
        'tags' => ['Taille', 'Maintien'],
        'keywords' => "taille unique ajusteur intégré morphologie glisser maintien",
    ],
    [
        'q' => "Peut-il abîmer mes chemises ?",
        'a' => '<p>L\'ancrage est volontairement <em>doux</em> : il s\'attache sur le dernier bouton sans le forcer ni marquer le tissu.</p>
                <p>Il fonctionne aussi sur des chemises sans bouton (T-shirt, polo), le maintien reste correct mais légèrement moins ferme.</p>',
        'tags' => ['Compatibilité'],
        'keywords' => "abîmer chemise compatible bouton t-shirt polo préserver",
    ],
    [
        'q' => "Quels sont les délais et frais de livraison ?",
        'a' => '<p><b>Livraison offerte</b>, sans minimum d\'achat. Délai total : <b>72h</b>.</p>
                <ul>
                  <li>Expédition sous <b>24h</b> depuis notre stock en France.</li>
                  <li>Livraison sous <b>48h</b> en France métropolitaine.</li>
                </ul>',
        'tags' => ['Livraison', 'France'],
        'keywords' => "livraison 72h gratuite france expédition 24h 48h stock",
    ],
    [
        'q' => "Et si l'Ajusteur ne me convient pas, je peux le retourner ?",
        'a' => '<p>Notre politique <b>Satisfait ou Remboursé</b> vous permet de retourner votre commande dans les <b>14 jours</b> suivant la réception, sans avoir à vous justifier.</p>
                <p>Un email à notre service client suffit pour lancer la procédure, avec une réponse sous 24h.</p>',
        'tags' => ['Retour', 'SAV'],
        'keywords' => "retour remboursement satisfait politique sav 14 jours",
    ],
];

// Nonce pour sécuriser le formulaire AJAX
$tirea_faq_nonce = wp_create_nonce('tirea_faq_contact');
?>

<?php // ============================================
      // JSON-LD FAQPage pour les rich snippets Google
      // ============================================ ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    <?php
    $json_items = [];
    foreach ($tirea_faq_items as $item) {
        // On retire les balises HTML pour le JSON-LD (texte brut requis)
        $clean_answer = wp_strip_all_tags($item['a']);
        $clean_answer = preg_replace('/\s+/', ' ', trim($clean_answer));
        $json_items[] = sprintf(
            '{"@type":"Question","name":%s,"acceptedAnswer":{"@type":"Answer","text":%s}}',
            wp_json_encode($item['q'], JSON_UNESCAPED_UNICODE),
            wp_json_encode($clean_answer, JSON_UNESCAPED_UNICODE)
        );
    }
    echo implode(',', $json_items);
    ?>
  ]
}
</script>

<section class="tirea-faq" id="tirea-faq" aria-labelledby="tirea-faq-title">

  <?php // ===== HERO ===== ?>
  <div class="tirea-faq-hero">
    <div class="tirea-faq-badge"><?php echo esc_html($tirea_faq_badge); ?></div>
    <h2 id="tirea-faq-title" class="tirea-faq-h2">
      <?php echo esc_html($tirea_faq_title); ?><br>
      <em><?php echo esc_html($tirea_faq_title_em); ?></em><span class="tirea-faq-dot">.</span>
    </h2>
    <p class="tirea-faq-intro"><?php echo esc_html($tirea_faq_intro); ?></p>
  </div>

  <?php // ===== BARRE DE RECHERCHE ===== ?>
  <div class="tirea-faq-search" role="search">
    <svg class="tirea-faq-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" aria-hidden="true">
      <circle cx="11" cy="11" r="8"/>
      <path d="m21 21-4.3-4.3"/>
    </svg>
    <input
      type="search"
      id="tirea-faq-search-input"
      class="tirea-faq-search-input"
      placeholder="Rechercher dans la FAQ…"
      aria-label="Rechercher dans la FAQ">
    <button type="button" id="tirea-faq-expand" class="tirea-faq-expand-btn">Tout déplier</button>
  </div>

  <?php // ===== ACCORDÉON ===== ?>
  <div class="tirea-faq-list" id="tirea-faq-list">
    <?php foreach ($tirea_faq_items as $i => $item):
        $num = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
        // On agrège la recherche : keywords + question + tags
        $search_haystack = strtolower(
            ($item['keywords'] ?? '') . ' ' .
            $item['q'] . ' ' .
            wp_strip_all_tags($item['a']) . ' ' .
            implode(' ', $item['tags'] ?? [])
        );
    ?>
      <details class="tirea-faq-item" data-q="<?php echo esc_attr($search_haystack); ?>">
        <summary class="tirea-faq-summary">
          <span class="tirea-faq-summary-left">
            <span class="tirea-faq-num"><?php echo esc_html($num); ?></span>
            <span class="tirea-faq-q"><?php echo esc_html($item['q']); ?></span>
          </span>
          <svg class="tirea-faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true">
            <path d="M12 5v14 M5 12h14"/>
          </svg>
        </summary>
        <div class="tirea-faq-answer">
          <?php echo wp_kses_post($item['a']); ?>
          <?php if (!empty($item['tags'])): ?>
            <div class="tirea-faq-tags">
              <?php foreach ($item['tags'] as $tag): ?>
                <span class="tirea-faq-tag"><?php echo esc_html($tag); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </details>
    <?php endforeach; ?>

    <div class="tirea-faq-empty" id="tirea-faq-empty" hidden>
      Aucun résultat — essayez un autre mot-clé ou écrivez-nous directement.
    </div>
  </div>

  <?php // ===== FORMULAIRE CONTACT ===== ?>
  <div class="tirea-faq-contact">
    <div class="tirea-faq-contact-intro">
      <div class="tirea-faq-badge"><?php echo esc_html($tirea_faq_contact_badge); ?></div>
      <h3 class="tirea-faq-contact-title">
        <?php echo esc_html($tirea_faq_contact_title); ?><br>
        <em><?php echo esc_html($tirea_faq_contact_em); ?></em>
      </h3>
      <p class="tirea-faq-contact-lead"><?php echo esc_html($tirea_faq_contact_lead); ?></p>
    </div>

    <form id="tirea-faq-contact-form" class="tirea-faq-contact-form" novalidate>
      <input type="hidden" name="nonce" value="<?php echo esc_attr($tirea_faq_nonce); ?>">

      <?php // Honeypot anti-spam : champ caché, doit rester vide ?>
      <div class="tirea-faq-hp" aria-hidden="true">
        <label for="tirea-faq-website">Site web (ne pas remplir)</label>
        <input type="text" id="tirea-faq-website" name="website" tabindex="-1" autocomplete="off">
      </div>

      <div class="tirea-faq-form-row">
        <input type="text" name="name" placeholder="Votre prénom" aria-label="Votre prénom" required>
        <input type="email" name="email" placeholder="Votre email" aria-label="Votre email" required>
      </div>
      <textarea name="message" placeholder="Votre question…" aria-label="Votre question" required></textarea>

      <button type="submit" class="tirea-faq-submit">
        <span class="tirea-faq-submit-text">Envoyer ma question</span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
          <path d="M5 12h14 M13 5l7 7-7 7"/>
        </svg>
      </button>

      <p class="tirea-faq-form-status" id="tirea-faq-status" role="status" aria-live="polite"></p>
    </form>
  </div>
</section>