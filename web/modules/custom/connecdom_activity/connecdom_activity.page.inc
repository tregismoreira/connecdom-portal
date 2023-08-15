<?php

/**
 * @file
 * Contains connecdom_activity.page.inc.
 *
 * Page callback for Connecdom Activity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Connecdom Activity templates.
 *
 * Default template: connecdom_activity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_connecdom_activity(array &$variables) {
  // Fetch ConnecdomActivityEntity Entity Object.
  $connecdom_activity = $variables['elements']['#connecdom_activity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
