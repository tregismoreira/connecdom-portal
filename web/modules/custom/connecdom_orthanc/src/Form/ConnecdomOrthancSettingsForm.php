<?php

namespace Drupal\connecdom_orthanc\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConnecdomOrthancSettingsForm.
 * @package Drupal\connecdom_orthanc\Form
 * @ingroup connecdom_orthanc
 */
class ConnecdomOrthancSettingsForm extends ConfigFormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'connecdom_orthanc_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'connecdom_orthanc.settings',
    ];
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $config = $this->config('connecdom_orthanc.settings');
    $config->set('orthanc_url', $values['orthanc_url']);
    $config->set('orthanc_username', $values['orthanc_username']);
    if ($values['orthanc_password']) {
      $config->set('orthanc_password', $values['orthanc_password']);
    }
    $config->set('orthanc_token', $values['orthanc_token']);
    $config->set('orthanc_changes_limit', $values['orthanc_changes_limit']);
    $config->save();

    parent::submitForm($form, $form_state);
  }


  /**
   * Define the form used for ConnecdomOrthanc settings.
   * @return array
   *   Form definition array.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('connecdom_orthanc.settings');

    $form['connecdom_orthanc_settings']['orthanc_credentials'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Webservice access credentials'),
      '#weight' => 0,
    ];

    $form['connecdom_orthanc_settings']['orthanc_credentials']['orthanc_url'] = [
      '#type' => 'url',
      '#title' => $this->t('URL'),
      '#description' => $this->t('The complete URL to the Orthanc server without trailing slash. Remember to include the port and protocol. Example: https://myorthanc.com:8042.'),
      '#required' => TRUE,
      '#weight' => 0,
      '#default_value' => $config->get('orthanc_url') ? $config->get('orthanc_url') : NULL,
    ];

    $form['connecdom_orthanc_settings']['orthanc_credentials']['orthanc_username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#required' => TRUE,
      '#weight' => 1,
      '#default_value' => $config->get('orthanc_username') ? $config->get('orthanc_username') : NULL,
    ];

    $form['connecdom_orthanc_settings']['orthanc_credentials']['orthanc_password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#weight' => 2,
      '#default_value' => $config->get('orthanc_password') ? $config->get('orthanc_password') : NULL,
    ];

    $form['connecdom_orthanc_settings']['orthanc_credentials']['orthanc_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Access Token'),
      '#weight' => 2,
      '#default_value' => $config->get('orthanc_token') ? $config->get('orthanc_token') : NULL,
    ];

    $form['connecdom_orthanc_settings']['orthanc_changes'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Changes'),
      '#weight' => 1,
    ];

    $form['connecdom_orthanc_settings']['orthanc_changes']['orthanc_changes_limit'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Limit'),
      '#description' => $this->t('The number of changes to fetch each time the Cron runs.'),
      '#required' => TRUE,
      '#default_value' => $config->get('orthanc_changes_limit') ? $config->get('orthanc_changes_limit') : 100,
    ];

    return parent::buildForm($form, $form_state);
  }
}
