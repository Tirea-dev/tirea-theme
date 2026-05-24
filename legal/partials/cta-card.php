<?php // Carte CTA en pied de page
if (!defined('ABSPATH')) exit;
if (empty($tirea_legal_page['cta'])) return;
$cta = $tirea_legal_page['cta']; ?>
<aside class="tirea-legal-cta">
  <div>
    <span class="tirea-legal-pill"><?php echo esc_html($cta['pill']); ?></span>
    <h2>
      <?php echo esc_html($cta['h3']); ?>
      <em><?php echo esc_html($cta['h3_em']); ?></em>
    </h2>
    <p><?php echo esc_html($cta['text']); ?></p>
  </div>
  <a class="tirea-legal-btn" href="<?php echo esc_url($cta['btn_url']); ?>">
    <?php echo esc_html($cta['btn']); ?>
    <span class="tirea-legal-arrow" aria-hidden="true"></span>
  </a>
</aside>