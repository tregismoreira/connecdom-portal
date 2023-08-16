<?php

/**
 * @file
 * Contains instance.page.inc.
 *
 * Page callback for Instance entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Instance templates.
 *
 * Default template: instance.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_instance(array &$variables) {
  // Fetch InstanceEntity Entity Object.
  $instance = $variables['elements']['#instance'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
