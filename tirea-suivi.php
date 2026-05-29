<?php
/**
 * Template Name: Tirea — Suivi
 * Page "suivi" routée par slug via template_include (functions.php), comme le légal.
 * Aucune assignation de modèle dans l'admin : créer une page vide au slug "suivi".
 * Formulaire → AJAX tirea_suivi_track() → API Suivi La Poste (serveur). Clé en wp-config.php.
 */
if (!defined('ABSPATH')) exit;

get_header();
?>
<div class="tirea-suivi-wrapper">
  <main class="tirea-suivi-page">

    <header class="tirea-suivi-hero">
      <span class="tirea-suivi-pill">Suivi de commande</span>
      <h1>Où en est<br><em>votre livraison ?</em></h1>
      <p class="tirea-suivi-lede">Entrez votre numéro de suivi qui figure dans votre e-mail de confirmation d'expédition afin de connaître l'état de votre livraison en temps réel.</p>
    </header>

    <div class="tirea-suivi-form-card">
      <form class="tirea-suivi-form" id="tirea-suivi-form" novalidate>
        <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('tirea_suivi')); ?>">

        <div class="tirea-suivi-honeypot" aria-hidden="true">
          <label>Ne pas remplir <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
        </div>

        <div class="tirea-suivi-field-group">
          <label for="tirea-suivi-input">Numéro de suivi</label>
          <div class="tirea-suivi-field">
            <input type="text" id="tirea-suivi-input" name="tracking" placeholder="Ex. 6A12345678901" autocomplete="off" required>
            <button type="submit" class="tirea-suivi-btn">
              <span class="tirea-suivi-btn-text">Suivre mon colis</span>
              <span class="tirea-suivi-arrow" aria-hidden="true"></span>
            </button>
          </div>
        </div>

        <p class="tirea-suivi-feedback" id="tirea-suivi-feedback" role="status" aria-live="polite"></p>
      </form>

      <div class="tirea-suivi-result" id="tirea-suivi-result" hidden></div>
    </div>

  </main>
</div>
<?php
get_footer();