<?php

/**
 * @file
 * Contains modality.page.inc.
 *
 * Page callback for Modality entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Modality templates.
 *
 * Default template: modality.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_modality(array &$variables) {
  // Fetch ModalityEntity Entity Object.
  $modality = $variables['elements']['#modality'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
