<?php

namespace Drupal\connecdom_orthanc\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Study entities.
 */
class StudyEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
