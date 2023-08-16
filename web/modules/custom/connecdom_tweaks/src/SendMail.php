<?php

namespace Drupal\connecdom_tweaks;

use Drupal\user\Entity\User;

class SendMail {

  /**
   * Send email according to the given params.
   */
  static function send($identifier, $params) {
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'connecdom_tweaks';
    $key = $identifier;
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $recipient = $params['recipient'];

    $mailManager->mail($module, $key, $recipient->get('mail')->value, $langcode, $params);
  }

  /**
   * Load Clinic profile from Study.
   *
   * @TODO: Create relationship between Clinic and Study.
   */
  static function clinicUserByStudy($study) {
    $user_storage = \Drupal::service('entity_type.manager')->getStorage('user');
    $ids = $user_storage->getQuery()
      ->condition('status', 1)
      ->condition('roles', 'clinic')
      ->execute();
    $user_id = array_keys($ids)[0];

    return User::load($user_id);
  }

}
