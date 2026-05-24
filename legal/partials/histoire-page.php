<?php // Layout spécifique de la page Notre Histoire : hero éditorial + chapitres + CTA fort
if (!defined('ABSPATH')) exit;
$tirea_legal_dir = get_stylesheet_directory() . '/legal'; ?>

<div class="tirea-histoire-page">

  <?php // HERO éditorial ?>
  <section class="tirea-histoire-hero">
    <p class="tirea-histoire-eyebrow"><?php echo esc_html($tirea_legal_page['eyebrow'] ?? 'Notre Histoire'); ?></p>
    <h1>
      <?php echo esc_html($tirea_legal_page['h1']); ?><br>
      <em><?php echo esc_html($tirea_legal_page['h1_em']); ?></em>
    </h1>
    <p class="tirea-histoire-desc"><?php echo esc_html($tirea_legal_page['lede']); ?></p>
    <span class="tirea-histoire-scroll" aria-hidden="true">Défiler</span>
  </section>

  <?php // CHAPITRES ?>
  <div class="tirea-histoire-main">
    <?php
    $content_file = $tirea_legal_dir . '/contents/' . $tirea_legal_slug . '.php';
    if (file_exists($content_file)) include $content_file;
    ?>
  </div>

  <?php // CTA final ?>
  <?php if (!empty($tirea_legal_page['cta'])) : $cta = $tirea_legal_page['cta']; ?>
    <section class="tirea-histoire-cta">
      <p class="tirea-histoire-cta-eyebrow"><?php echo esc_html($cta['eyebrow']); ?></p>
      <h2>
        <?php echo esc_html($cta['h2']); ?><br>
        <em><?php echo esc_html($cta['h2_em']); ?></em>
      </h2>
      <p><?php echo esc_html($cta['text']); ?></p>
      <div class="tirea-histoire-cta-btns">
        <?php if (!empty($cta['btn1_url'])) : ?>
          <a class="tirea-histoire-btn-primary" href="<?php echo esc_url($cta['btn1_url']); ?>">
            <?php echo esc_html($cta['btn1']); ?>
            <span class="tirea-legal-arrow" aria-hidden="true"></span>
          </a>
        <?php endif; ?>
        <?php if (!empty($cta['btn2_url'])) : ?>
          <a class="tirea-histoire-btn-outline" href="<?php echo esc_url($cta['btn2_url']); ?>">
            <?php echo esc_html($cta['btn2']); ?>
          </a>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

</div>