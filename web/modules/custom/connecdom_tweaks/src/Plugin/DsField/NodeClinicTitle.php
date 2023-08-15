<?php

namespace Drupal\connecdom_tweaks\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;

/**
 * @DsField(
 *   id = "node_clinic_title",
 *   title = @Translation("Node Clinic Title"),
 *   entity_type = "node",
 *   provider = "connecdom_tweaks",
 *   ui_limit = {"clinic|*"}
 * )
 */
class NodeClinicTitle extends DsFieldBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = $this->entity();

    $markup = $node->getTitle();

    if ($branch = $node->get('field_clinic_branch')) {
      $markup .= ' - ' . $branch->value;
    }

    return [
      '#markup' => '<h2>' . $markup . '</h2>',
    ];
  }
}