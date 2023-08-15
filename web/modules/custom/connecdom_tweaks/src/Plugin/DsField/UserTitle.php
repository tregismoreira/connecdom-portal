<?php

namespace Drupal\connecdom_tweaks\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;

/**
 * @DsField(
 *   id = "user_title",
 *   title = @Translation("User Title"),
 *   entity_type = "user",
 *   provider = "connecdom_tweaks"
 * )
 */
class UserTitle extends DsFieldBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $user = $this->entity();

    // Add clinic name.
    $markup = [$user->getDisplayName()];

    // Add branch name if exists
    if (isset($user->field_clinic_branch->value)) {
      $markup[] = $user->field_clinic_branch->value;
    }

    // Add user role.
    $roles = array_diff($user->getRoles(), ['authenticated']);
    $roles_markup = $roles ? '<span class="label label-danger">'. implode(', ', $roles) .'</span>' : '';

    return [
      '#markup' => '<h2>' . implode(' - ', $markup) . $roles_markup . '</h2>',
    ];
  }
}