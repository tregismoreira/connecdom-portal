<?php

/**
 * @file
 * Contains patient.page.inc.
 *
 * Page callback for Patient entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Patient templates.
 *
 * Default template: patient.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_patient(array &$variables) {
  // Fetch PatientEntity Entity Object.
  $patient = $variables['elements']['#patient'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
