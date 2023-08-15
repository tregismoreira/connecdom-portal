<?php

namespace Drupal\connecdom_tweaks\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;

/**
 * Defines a confirmation form to confirm the doctor assignment on study.
 */
class ConfirmClinicRegenerateToken extends ConfirmFormBase {

  /**
   * Clinic of the item to update.
   *
   * @var int
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $user = NULL) {
    $this->user = $user;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $orthanc_auth = new \Drupal\connecdom_orthanc_auth\ConnecdomOrthancAuth();
    $user = $this->user;
    $old_token = $user->field_access_token->value;

    // Delete previous token.
    $orthanc_auth->deleteAccessToken($old_token);

    // Generate and store the new token.
    $access_token = $orthanc_auth->generateAccessToken();
    $orthanc_auth->storeAccessToken($access_token, 2);

    // Save in profile's field.
    $user->field_access_token->value = $access_token;
    $user->save();

    drupal_set_message(t('A new token was generated for %clinic.', ['%clinic' => $user->label()]));

    // Set redirect.
    $form_state->setRedirect('entity.user.canonical', ['user' => $user->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('The previous token will be revoked.');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() : string {
    return "confirm_clinic_regenerate_token_form";
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.user.canonical', ['user' => $this->user->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to generate a new token for this Clinic?');
  }

}