<?php

namespace Drupal\connecdom_tweaks\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Drupal\connecdom_tweaks\SendMail;

/**
 * Defines a confirmation form to confirm the doctor assignment on study.
 */
class ConfirmAssignDoctorForm extends ConfirmFormBase {

  /**
   * ID of the item to update.
   *
   * @var int
   */
  protected $id;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {
    $this->id = $id;

    $account = \Drupal::currentUser();
    $clinic = User::load($account->id());

    $staff = [];
    foreach ($clinic->get('field_clinic_staff')->getValue() as $key => $user) {
      $staff[] = $user['target_id'];
    }
    $users = User::loadMultiple($staff);

    $options = [];
    foreach ($users as $uid => $user) {
      $options[$uid] = "{$user->getDisplayName()} (CRM: {$user->field_crm_number->value} - {$user->field_crm_state->value})";
    }

    $form['assignee'] = [
      '#title' => $this->t('Choose a Doctor'),
      '#type' => 'select',
      '#options' => $options,
      '#empty_value' => '',
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $uid = $form_state->getValue('assignee');
    $user = User::load($uid);

    $entity_manager = \Drupal::entityManager();
    $study = $entity_manager->getStorage('study')->load($this->id);
    $study->field_study_assignee = $user;
    $study->field_study_status = 'assigned';
    $study->save();
    drupal_set_message(t('The doctor was successfully assigned to the study.'));
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() : string {
    return "confirm_assign_doctor_form";
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('view.worklist.main');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Which doctor you want to assign to this study?');
  }

}
