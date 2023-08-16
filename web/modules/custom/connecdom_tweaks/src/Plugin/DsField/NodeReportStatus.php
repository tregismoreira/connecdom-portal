<?php

namespace Drupal\connecdom_tweaks\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;

/**
 * @DsField(
 *   id = "node_report_status",
 *   title = @Translation("Node Report Status"),
 *   entity_type = "node",
 *   provider = "connecdom_tweaks",
 *   ui_limit = {"report|*"}
 * )
 */
class NodeReportStatus extends DsFieldBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => '<h3>' . $this->entity()->field_report_title->value . '</h3>',
    ];
  }
}