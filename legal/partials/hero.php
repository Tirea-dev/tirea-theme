<?php // Hero partagé : pill + h1 + lede + meta
if (!defined('ABSPATH')) exit; ?>
<header class="tirea-legal-hero">
  <span class="tirea-legal-pill"><?php echo esc_html($tirea_legal_page['pill']); ?></span>
  <h1>
    <?php echo esc_html($tirea_legal_page['h1']); ?>
    <em><?php echo esc_html($tirea_legal_page['h1_em']); ?></em>
  </h1>
  <p class="tirea-legal-lede"><?php echo esc_html($tirea_legal_page['lede']); ?></p>
  <?php if (!empty($tirea_legal_page['meta'])) : ?>
    <div class="tirea-legal-meta">
      <?php foreach ($tirea_legal_page['meta'] as $m) : ?>
        <span><?php echo esc_html($m['label']); ?> <strong><?php echo esc_html($m['value']); ?></strong></span>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</header>