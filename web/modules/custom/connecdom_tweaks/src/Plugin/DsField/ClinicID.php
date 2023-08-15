<?php

namespace Drupal\connecdom_tweaks\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;

/**
 * @DsField(
 *   id = "clinic_id",
 *   title = @Translation("Clinic ID"),
 *   entity_type = "user",
 *   provider = "connecdom_tweaks"
 * )
 */
class ClinicID extends DsFieldBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $user = $this->entity();

    return [
      '#markup' => $user->id(),
    ];
  }
}