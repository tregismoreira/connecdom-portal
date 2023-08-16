<?php

namespace Drupal\connecdom_orthanc;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Patient entity.
 *
 * @see \Drupal\connecdom_orthanc\Entity\PatientEntity.
 */
class PatientEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\connecdom_orthanc\Entity\PatientEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished patient entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published patient entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit patient entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete patient entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add patient entities');
  }

}
