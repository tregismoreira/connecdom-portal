<?php

namespace Drupal\connecdom_tweaks\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;

/**
 * @DsField(
 *   id = "clinic_name",
 *   title = @Translation("Clinic Name"),
 *   entity_type = "user",
 *   provider = "connecdom_tweaks"
 * )
 */
class ClinicName extends DsFieldBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $user = $this->entity();
    $markup = [$user->getDisplayName()];
    if (isset($user->field_clinic_branch->value)) {
      $markup[] = $user->field_clinic_branch->value;
    }

    return [
      '#markup' => implode(' - ', $markup),
    ];
  }
}