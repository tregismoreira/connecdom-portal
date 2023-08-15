<?php

namespace Drupal\connecdom_tweaks\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;
use Drupal\connecdom_tweaks\Controller\ConnecdomTweaksController;

/**
 * @DsField(
 *   id = "node_report_signature",
 *   title = @Translation("Node Report Signature"),
 *   entity_type = "node",
 *   provider = "connecdom_tweaks",
 *   ui_limit = {"report|*"}
 * )
 */
class NodeReportSignature extends DsFieldBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $report = $this->entity();
    $study = ConnecdomTweaksController::entityLoadFromResourceId($report->field_report_study->target_id, 'study');
    $doctor = ConnecdomTweaksController::entityLoadFromResourceId($study->field_study_assignee->target_id, 'user');
    $data = [];

    if ($doctor->field_signature->target_id) {
      // Signature.
      $signature_file = ConnecdomTweaksController::entityLoadFromResourceId($doctor->field_signature->target_id, 'file');
      $data['signature_url'] = file_create_url($signature_file->uri->value);

      // Doctor Name.
      $data['doctor_name'] = $doctor->field_fullname->value;

      // Doctor CRM.
      $data['doctor_crm'] = $doctor->field_crm_number->value . '-' . $doctor->field_crm_state->value;
    }

    return [
      '#theme' => 'connecdom__ds_field__node_report_signature',
      '#data' => $data,
    ];
  }
}