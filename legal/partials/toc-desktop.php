<?php // Sommaire desktop (sidebar sticky) — généré depuis la config
if (!defined('ABSPATH')) exit;
if (empty($tirea_legal_page['sections'])) return; ?>
<aside class="tirea-legal-toc" aria-label="Sommaire">
  <div class="tirea-legal-toc-label">Sommaire</div>
  <ol>
    <?php foreach ($tirea_legal_page['sections'] as $i => $s) : ?>
      <li>
        <a href="#<?php echo esc_attr($s['id']); ?>">
          <span class="tirea-legal-num"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></span>
          <span><?php echo esc_html($s['label']); ?></span>
        </a>
      </li>
    <?php endforeach; ?>
  </ol>
</aside>