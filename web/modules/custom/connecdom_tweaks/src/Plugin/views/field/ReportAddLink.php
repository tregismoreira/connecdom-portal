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
 * @ViewsField("report_add_link")
 */
class ReportAddLink extends LinkBase {

  /**
   * {@inheritdoc}
   */
  protected function getUrlInfo(ResultRow $row) {
    $resource_id = $row->resource_id;

    return Url::fromRoute('node.add', ['node_type' => 'report', 'sid' => $resource_id], [
      'attributes' => [
        'class' => ['use-ajax'],
        'data-dialog-type' => 'modal'
      ]
    ]);
  }

  /**
   * {@inheritdoc}
   */
  protected function renderLink(ResultRow $row) {
    $resource_id = $row->resource_id;
    $study = ConnecdomTweaksController::entityLoadFromResourceId($resource_id, 'study');

    if (!ConnecdomTweaksController::getNodeIdFromStudyId($resource_id, 'field_report_study') && ConnecdomTweaksController::reportHasAddPermission($study)) {
      $text = parent::renderLink($row);
      return $text;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultLabel() {
    return $this->t('Add Report');
  }

}
