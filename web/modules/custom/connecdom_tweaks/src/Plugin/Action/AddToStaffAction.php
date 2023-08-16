<?php

namespace Drupal\connecdom_tweaks\Plugin\Action;

use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Action description.
 *
 * @Action(
 *   id = "add_to_staff_action",
 *   label = @Translation("Add To Staff"),
 *   type = "user",
 *   confirm = FALSE
 * )
 */
class AddToStaffAction extends ViewsBulkOperationsActionBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function execute($account = NULL) {
    $account->save();
    // return;
    // $uid = $this->configuration['assignee'];
    // $user = User::load($uid);

    // $assignee = $account->get('field_study_assignee')->getValue();
    // if ($assignee && $uid == $assignee[0]['target_id']) {
    //   return $this->t('Doctor @doctor is already assigned.', ['@doctor' => $user->getDisplayName()]);
    // }
    // else {
    //   $account->field_study_assignee = $user;
    //   $account->save();

    //   return $this->t('Doctor @doctor successfully assigned.', ['@doctor' => $user->getDisplayName()]);
    // }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    return TRUE;
  }

}