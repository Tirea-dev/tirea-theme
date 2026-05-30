<?php
/**
 * Template FAQ Tirea — rendu
 *
 * Rendu via shortcode :
 *   [tirea_faq]              → version allégée (page d'accueil)
 *   [tirea_faq mode="full"]  → FAQ complète (page /faq)
 *
 * Les questions et les textes viennent de tirea-faq-data.php (source unique) :
 * c'est ce fichier-là, et lui seul, que l'on édite pour gérer les questions.
 *
 * Accordéon natif <details>/<summary>, recherche client-side,
 * formulaire de contact via wp_mail() avec honeypot anti-spam.
 *
 * $tirea_faq_mode est défini par le shortcode (functions.php). Défaut : 'home'.
 */

if (!defined('ABSPATH')) exit;

// Mode de rendu (défini par le shortcode) : 'home' (allégé) ou 'full' (page /faq)
$tirea_faq_mode    = (isset($tirea_faq_mode) && $tirea_faq_mode === 'full') ? 'full' : 'home';
$tirea_faq_is_full = ($tirea_faq_mode === 'full');

// Affichage du formulaire de contact, du lien "Voir toutes les questions" et du badge
// (désactivables via le shortcode : contact="off" / more="off" / badge="off" — ex. fiche produit)
$tirea_faq_show_contact = isset($tirea_faq_show_contact) ? (bool) $tirea_faq_show_contact : true;
$tirea_faq_show_more    = isset($tirea_faq_show_more) ? (bool) $tirea_faq_show_more : true;
$tirea_faq_show_badge   = isset($tirea_faq_show_badge) ? (bool) $tirea_faq_show_badge : true;

// ============================================
// SOURCE UNIQUE DES DONNÉES
// ============================================
$tirea_faq_data = require get_stylesheet_directory() . '/tirea-faq-data.php';

$tirea_faq_items      = isset($tirea_faq_data['items']) ? $tirea_faq_data['items'] : [];
$tirea_faq_count      = count($tirea_faq_items);
$tirea_faq_home_limit = isset($tirea_faq_data['home_limit']) ? (int) $tirea_faq_data['home_limit'] : 7;

$tirea_faq_badge    = isset($tirea_faq_data['badge'])    ? $tirea_faq_data['badge']    : "Centre d'aide TIREA";
$tirea_faq_title    = isset($tirea_faq_data['title'])    ? $tirea_faq_data['title']    : "Vos questions,";
$tirea_faq_title_em = isset($tirea_faq_data['title_em']) ? $tirea_faq_data['title_em'] : "nos réponses";
$tirea_faq_intro    = isset($tirea_faq_data['intro'])    ? $tirea_faq_data['intro']    : "";

$tirea_faq_contact_badge = isset($tirea_faq_data['contact_badge']) ? $tirea_faq_data['contact_badge'] : "Réponse sous 24h";
$tirea_faq_contact_title = isset($tirea_faq_data['contact_title']) ? $tirea_faq_data['contact_title'] : "Une autre question ?";
$tirea_faq_contact_em    = isset($tirea_faq_data['contact_em'])    ? $tirea_faq_data['contact_em']    : "Posez-la nous.";
$tirea_faq_contact_lead  = isset($tirea_faq_data['contact_lead'])  ? $tirea_faq_data['contact_lead']  : "";

// Y a-t-il des questions masquées au repos ? (home avec plus de questions que la limite)
$tirea_faq_has_extra = (!$tirea_faq_is_full && count($tirea_faq_items) > $tirea_faq_home_limit);

// Nonce pour sécuriser le formulaire AJAX
$tirea_faq_nonce = wp_create_nonce('tirea_faq_contact');
?>

<?php // ===== JSON-LD FAQPage — uniquement sur /faq (évite le contenu dupliqué avec la home) ===== ?>
<?php if ($tirea_faq_is_full): ?>
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
<?php endif; ?>

<section class="tirea-faq" id="tirea-faq" data-mode="<?php echo esc_attr($tirea_faq_mode); ?>" aria-labelledby="tirea-faq-title">

  <?php // ===== HERO ===== ?>
  <div class="tirea-faq-hero">
    <?php if ($tirea_faq_show_badge): ?><div class="tirea-faq-badge"><?php echo esc_html($tirea_faq_badge); ?></div><?php endif; ?>
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
      aria-label="Rechercher dans la FAQ"
      aria-describedby="tirea-faq-search-hint">
    <button type="button" id="tirea-faq-expand" class="tirea-faq-expand-btn">Tout déplier</button>
  </div>

  <?php // ===== INDICATION : la recherche couvre TOUTES les questions, pas seulement celles affichées ===== ?>
  <p class="tirea-faq-search-hint" id="tirea-faq-search-hint">
    Recherche par mots-clés parmi <span class="tirea-faq-search-count"><?php echo (int) $tirea_faq_count; ?></span> <?php echo ($tirea_faq_count > 1) ? 'questions' : 'question'; ?>
  </p>

  <?php // ===== ACCORDÉON ===== ?>
  <div class="tirea-faq-list" id="tirea-faq-list">
    <?php foreach ($tirea_faq_items as $i => $item):
        $num = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
        // On agrège la recherche : keywords + question + réponse + tags
        $search_haystack = strtolower(
            ($item['keywords'] ?? '') . ' ' .
            $item['q'] . ' ' .
            wp_strip_all_tags($item['a']) . ' ' .
            implode(' ', $item['tags'] ?? [])
        );
        // Version allégée : les questions au-delà de la limite sont masquées
        // par défaut (mais présentes dans le DOM, donc trouvables par la recherche).
        $is_extra   = (!$tirea_faq_is_full && $i >= $tirea_faq_home_limit);
        $item_class = 'tirea-faq-item' . ($is_extra ? ' is-extra is-hidden' : '');
    ?>
      <details class="<?php echo esc_attr($item_class); ?>" data-q="<?php echo esc_attr($search_haystack); ?>">
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

  <?php // ===== LIEN VERS LA FAQ COMPLÈTE — version allégée uniquement, sauf si désactivé (more="off") ===== ?>
  <?php if ($tirea_faq_has_extra && $tirea_faq_show_more): ?>
    <div class="tirea-faq-more" id="tirea-faq-more">
      <a class="tirea-faq-more-link" href="<?php echo esc_url(home_url('/faq/')); ?>">
        Voir toutes les questions
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M5 12h14 M13 5l7 7-7 7"/>
        </svg>
      </a>
    </div>
  <?php endif; ?>

  <?php // ===== FORMULAIRE CONTACT — masquable via contact="off" ===== ?>
  <?php if ($tirea_faq_show_contact): ?>
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
  <?php endif; ?>
</section>