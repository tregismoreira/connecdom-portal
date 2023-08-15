<?php

namespace Drupal\connecdom_orthanc;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Instance entity.
 *
 * @see \Drupal\connecdom_orthanc\Entity\InstanceEntity.
 */
class InstanceEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\connecdom_orthanc\Entity\InstanceEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished instance entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published instance entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit instance entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete instance entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add instance entities');
  }

}
