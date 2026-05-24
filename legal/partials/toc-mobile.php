<?php // Sommaire mobile (accordéon)
if (!defined('ABSPATH')) exit;
if (empty($tirea_legal_page['sections'])) return;
$count = count($tirea_legal_page['sections']); ?>
<details class="tirea-legal-toc-mobile">
  <summary>
    <span>Sommaire (<?php echo (int) $count; ?> sections)</span>
    <span class="tirea-legal-chev" aria-hidden="true"></span>
  </summary>
  <div class="tirea-legal-toc-mobile-list">
    <?php foreach ($tirea_legal_page['sections'] as $i => $s) : ?>
      <a href="#<?php echo esc_attr($s['id']); ?>">
        <span class="tirea-legal-num"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></span>
        <span><?php echo esc_html($s['label']); ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</details>