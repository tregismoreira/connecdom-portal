<?php

namespace Drupal\connecdom_activity\Plugin\views\field;

use Drupal\Core\Url;
use Drupal\views\Plugin\views\field\LinkBase;
use Drupal\views\ResultRow;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("connecdom_activity_link")
 */
class ConnecdomActivityLink extends LinkBase {

  /**
   * {@inheritdoc}
   */
  protected function getUrlInfo(ResultRow $row) {
    $resource_id = $row->resource_id;

    return Url::fromRoute('view.connecdom_activity.page_history', ['arg_0' => $resource_id], [
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
    return parent::renderLink($row);
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultLabel() {
    return $this->t('View activity');
  }

}
