<?php

namespace Drupal\connecdom_tweaks\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;

/**
 * @DsField(
 *   id = "node_status",
 *   title = @Translation("Node Status"),
 *   entity_type = "node",
 *   provider = "connecdom_tweaks"
 * )
 */
class NodeStatus extends DsFieldBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = $this->entity();
    $label = $node->get('status')->value ? $this->t('Published profile') : $this->t('Unpublished profile');
    $label_class = $node->get('status')->value ? 'info published' : 'danger unpublished';
    $field_class = $node->get('status')->value ? 'is-published' : 'is-unpublished';

    return [
      '#markup' => '<span class="label label-'. $label_class .'">'. $label .'</span>',
    ];
  }
}