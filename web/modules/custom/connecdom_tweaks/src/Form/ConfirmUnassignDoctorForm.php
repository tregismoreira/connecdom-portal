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
class ConfirmUnassignDoctorForm extends ConfirmFormBase {

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
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity_manager = \Drupal::entityManager();
    $study = $entity_manager->getStorage('study')->load($this->id);
    $user = User::load($study->field_study_assignee->target_id);
    $study->field_study_assignee = NULL;
    $study->field_study_status = 'unassigned';
    $study->save();
    drupal_set_message(t('The doctor was successfully unassigned from the study.'));
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
    return "confirm_unassign_doctor_form";
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
    return t('Are you sure you want to unassign the doctor from this study?');
  }

}
