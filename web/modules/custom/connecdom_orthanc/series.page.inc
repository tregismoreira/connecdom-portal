<?php

/**
 * @file
 * Contains series.page.inc.
 *
 * Page callback for Series entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Series templates.
 *
 * Default template: series.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_series(array &$variables) {
  // Fetch SeriesEntity Entity Object.
  $series = $variables['elements']['#series'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
