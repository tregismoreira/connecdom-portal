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
 * @ViewsField("study_assign_link")
 */
class StudyAssignLink extends LinkBase {

  /**
   * {@inheritdoc}
   */
  protected function getUrlInfo(ResultRow $row) {
    $resource_id = $row->resource_id;

    return Url::fromRoute('connecdom_tweaks.study_assign_doctor', ['id' => $resource_id], [
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
    $user = \Drupal::currentUser();

    if (in_array('clinic', $user->getRoles()) && ConnecdomTweaksController::studyGetStatus($resource_id) == 'unassigned') {
      $text = parent::renderLink($row);
      return $text;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultLabel() {
    return $this->t('Assign Doctor');
  }

}
