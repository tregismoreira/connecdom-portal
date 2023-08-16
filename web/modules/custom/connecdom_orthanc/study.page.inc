<?php

/**
 * @file
 * Contains study.page.inc.
 *
 * Page callback for Study entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Study templates.
 *
 * Default template: study.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_study(array &$variables) {
  // Fetch StudyEntity Entity Object.
  $study = $variables['elements']['#study'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
