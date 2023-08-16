<?php

namespace Drupal\connecdom_orthanc;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Study entities.
 *
 * @ingroup connecdom_orthanc
 */
class StudyEntityListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['resource_id'] = $this->t('Resource ID');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\connecdom_orthanc\Entity\InstanceEntity */
    $row['resource_id'] = Link::createFromRoute(
      $entity->id(),
      'entity.study.canonical',
      ['study' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
