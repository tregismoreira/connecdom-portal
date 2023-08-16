<?php

namespace Drupal\connecdom_tweaks\Plugin\views\field;

use Drupal\Core\Url;
use Drupal\views\Plugin\views\field\LinkBase;
use Drupal\views\ResultRow;
use Drupal\connecdom_tweaks\Controller\ConnecdomTweaksController;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("report_download_link")
 */
class ReportDownloadLink extends LinkBase {

  /**
   * {@inheritdoc}
   */
  protected function getUrlInfo(ResultRow $row) {
    $resource_id = $row->resource_id;

    return Url::fromRoute('entity_print.view', [
      'export_type' => 'pdf',
      'entity_type' => 'node',
      'entity_id' => ConnecdomTweaksController::getNodeIdFromStudyId($resource_id, 'field_report_study')
    ]);
  }

  /**
   * {@inheritdoc}
   */
  protected function renderLink(ResultRow $row) {
    $resource_id = $row->resource_id;
    $report_id = ConnecdomTweaksController::getNodeIdFromStudyId($resource_id, 'field_report_study');
    $node = ConnecdomTweaksController::entityLoadFromResourceId($report_id, 'node');

    if ($node->isPublished() && ConnecdomTweaksController::getNodeIdFromStudyId($resource_id, 'field_report_study') && ConnecdomTweaksController::reportHasEditPermission($node)) {
      $text = parent::renderLink($row);
      return $text;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultLabel() {
    return $this->t('Download Report');
  }

}
