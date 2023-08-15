<?php

namespace Drupal\connecdom_tweaks\Plugin\Action;

use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Form\FormStateInterface;
use  \Drupal\user\Entity\User;

/**
 * Action description.
 *
 * @Action(
 *   id = "assign_doctor_to_study_action",
 *   label = @Translation("Assign a Doctor"),
 *   type = "study",
 *   confirm = FALSE,
 *   requirements = {
 *     "_permission" = "administer study entities",
 *   },
 * )
 */
class AssignDoctorToStudyAction extends ViewsBulkOperationsActionBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $ids = \Drupal::entityQuery('user')
      ->condition('status', 1)
      ->condition('roles', 'doctor')
      ->execute();
    $users = User::loadMultiple($ids);

    $options = [];
    foreach ($users as $uid => $user) {
      $options[$uid] = $user->getDisplayName();
    }

    $form['assignee'] = [
      '#title' => $this->t('Choose a Doctor'),
      '#type' => 'select',
      '#options' => $options,
      '#empty_value' => '',
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['assignee'] = $form_state->getValue('assignee');
  }

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    $uid = $this->configuration['assignee'];
    $user = User::load($uid);

    $assignee = $entity->get('field_study_assignee')->getValue();
    if ($assignee && $uid == $assignee[0]['target_id']) {
      return $this->t('Doctor @doctor is already assigned.', ['@doctor' => $user->getDisplayName()]);
    }
    else {
      $entity->field_study_assignee = $user;
      $entity->save();

      return $this->t('Doctor @doctor successfully assigned.', ['@doctor' => $user->getDisplayName()]);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    return TRUE;
  }

}