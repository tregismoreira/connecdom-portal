<?php

namespace Drupal\connecdom_tweaks\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Drupal\connecdom_tweaks\Controller\ConnecdomTweaksController;

/**
 * Defines a confirmation form to confirm the doctor assignment on study.
 */
class ConfirmStaffRemoveForm extends ConfirmFormBase {

  /**
   * Clinic of the item to update.
   *
   * @var int
   */
  protected $clinic;

  /**
   * Doctor of the item to update.
   *
   * @var int
   */
  protected $doctor;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $clinic = NULL, $doctor = NULL) {
    $this->clinic = $clinic;
    $this->doctor = $doctor;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    ConnecdomTweaksController::clinicStaffRemoveCallback($this->clinic, $this->doctor);
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
    return "confirm_staff_remove_form";
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('view.clinic_staff.page_staff');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to remove the doctor from your team?');
  }

}