<?php

namespace Drupal\connecdom_orthanc;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Modality entity.
 *
 * @see \Drupal\connecdom_orthanc\Entity\ModalityEntity.
 */
class ModalityEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\connecdom_orthanc\Entity\ModalityEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished modality entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published modality entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit modality entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete modality entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add modality entities');
  }

}
