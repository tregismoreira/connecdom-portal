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
 * @ViewsField("report_view_link")
 */
class ReportViewLink extends LinkBase {

  /**
   * {@inheritdoc}
   */
  protected function getUrlInfo(ResultRow $row) {
    $resource_id = $row->resource_id;

    return Url::fromRoute('entity.node.canonical', ['node' => ConnecdomTweaksController::getNodeIdFromStudyId($resource_id, 'field_report_study')], [
      'attributes' => [
        'class' => ['use-ajax'],
        'data-dialog-type' => 'modal'
      ]
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $row) {
    $build = [];
    $resource_id = $row->resource_id;
    $report_id = ConnecdomTweaksController::getNodeIdFromStudyId($resource_id, 'field_report_study');

    if ($report_id) {
      $build = ['#markup' => $this->renderLink($row)];
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultLabel() {
    return $this->t('View Report');
  }

}
