<?php
/**
 * Template Storytelling Tirea
 * 
 * Rendu via shortcode [tirea_storytelling].
 * Animation "Un look ___" → impeccable → sublimé → assuré → tiré à quatre épingles.
 * Les mots cyclent en fondu via JS au scroll dans la section.
 */

if (!defined('ABSPATH')) exit;

// ============================================
// CONFIGURATION
// ============================================

$tirea_story_prefix = "Un look";

// Liste des mots qui défilent. Le dernier est marqué comme "final" (taille différente).
$tirea_story_words = [
    'impeccable.',
    'sublimé.',
    'assuré.',
    'tiré à quatre épingles.',
];
?>

<section class="tirea-storytelling" aria-label="<?php echo esc_attr($tirea_story_prefix . ' ' . end($tirea_story_words)); ?>">
  <div class="tirea-story-container">
    <p class="tirea-story-prefix"><?php echo esc_html($tirea_story_prefix); ?></p>

    <?php // aria-hidden : le contenu est purement visuel (l'aria-label de la section suffit aux SR) ?>
    <div class="tirea-story-words" aria-hidden="true">
      <?php foreach ($tirea_story_words as $i => $word): ?>
        <?php
          // Le dernier mot est "final" (style plus gros)
          $is_final = ($i === count($tirea_story_words) - 1);
          $classes = 'tirea-story-word' . ($is_final ? ' tirea-story-final' : '');
        ?>
        <span class="<?php echo esc_attr($classes); ?>"><?php echo esc_html($word); ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</section>