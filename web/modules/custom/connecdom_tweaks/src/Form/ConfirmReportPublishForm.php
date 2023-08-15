<?php

namespace Drupal\connecdom_tweaks\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Drupal\user\Entity\User;
use Drupal\connecdom_tweaks\Controller\ConnecdomTweaksController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Defines a confirmation form to confirm the doctor assignment on study.
 */
class ConfirmReportPublishForm extends ConfirmFormBase {

  /**
   * Node object.
   *
   * @var int
   */
  protected $node;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, NodeInterface $node = NULL) {
    $this->node = $node;

    $user = User::load(\Drupal::currentUser()->id());

    if (!$node->access('update', $user)) {
      throw new AccessDeniedHttpException();
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->node->status = 1;

    if ($this->node->save()) {
      drupal_set_message(t('The Report was successfully published.'));
    }
    else {
      drupal_set_message(t('Something went wrong. Please try again later or contact our support.'));
    }

    $form_state->setRedirect('view.worklist.main');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('This action cannot be undone.');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() : string {
    return "confirm_report_publish_form";
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
    return t('Are you sure you want to publish the current report?');
  }

}