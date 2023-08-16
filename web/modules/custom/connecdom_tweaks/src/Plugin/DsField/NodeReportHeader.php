<?php

namespace Drupal\connecdom_tweaks\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;
use Drupal\connecdom_tweaks\Controller\ConnecdomTweaksController;

/**
 * @DsField(
 *   id = "node_report_header",
 *   title = @Translation("Node Report Header"),
 *   entity_type = "node",
 *   provider = "connecdom_tweaks",
 *   ui_limit = {"report|*"}
 * )
 */
class NodeReportHeader extends DsFieldBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $report = $this->entity();
    $study = ConnecdomTweaksController::entityLoadFromResourceId($report->field_report_study->target_id, 'study');
    $patient = ConnecdomTweaksController::entityLoadFromResourceId($study->field_study_parent_patient->target_id, 'patient');
    $doctor = ConnecdomTweaksController::entityLoadFromResourceId($study->field_study_assignee->target_id, 'user');
    $data = [];

    // Study ID.
    $data['study_id'] = $study->field_study_id->value;

    // Study Date.
    $study_date = new \DateTime($study->field_study_date->value);
    $data['study_date'] = $study_date->format('d/m/Y');

    // Patient Name.
    $data['patient_name'] = $patient->field_patient_name->value;

    // Patient Age.
    $birthdate = new \DateTime($patient->field_patient_birthdate->value);
    $today = new \DateTime();
    $diff_dates = $today->diff($birthdate);
    $data['patient_age'] = $diff_dates->y;

    // Doctor Name.
    $data['doctor_name'] = $doctor->field_fullname->value;

    return [
      '#theme' => 'connecdom__ds_field__node_report_header',
      '#data' => $data,
    ];
  }
}