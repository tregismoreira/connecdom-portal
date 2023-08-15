<?php

namespace Drupal\connecdom_activity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Connecdom Activity entities.
 *
 * @ingroup connecdom_activity
 */
class ConnecdomActivityEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header = [
      'id' => $this->t('ID'),
      'study' => $this->t('Study'),
      'doctor' => $this->t('Doctor'),
      'clinic' => $this->t('Clinic'),
      'details' => $this->t('Details')
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\connecdom_activity\Entity\ConnecdomActivityEntity $entity */

    $row = [
      'id' => $entity->id(),
      'study' => $entity->field_activity_study->entity ? $entity->field_activity_study->entity->toLink() : '',
      'doctor' => $entity->field_activity_doctor->entity ? $entity->field_activity_doctor->entity->toLink() : '',
      'clinic' => $entity->field_activity_clinic->entity ? $entity->field_activity_clinic->entity->toLink() : '',
      'details' => $entity->field_activity_details->value
    ];

    return $row + parent::buildRow($entity);
  }

}
