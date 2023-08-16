<?php

namespace Drupal\connecdom_tweaks\Plugin\Block;

use Drupal\Core\Url;
use Drupal\Core\Block\BlockBase;
use Drupal\connecdom_tweaks\Controllers\ConnecdomTweaksController;

/**
 * Provides a 'ProfileOptions' Block.
 *
 * @Block(
 *   id = "connecdom_profile_options",
 *   admin_label = @Translation("Profile Options"),
 *   category = @Translation("ConnecDOM"),
 * )
 */
class ProfileOptions extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [
      '#cache' => [
        'tags' => [
          \Drupal::service('handy_cache_tags.manager')->getBundleTag('node', 'doctor'),
          \Drupal::service('handy_cache_tags.manager')->getBundleTag('node', 'clinic'),
        ],
      ],
    ];

    if (!ConnecdomTweaksController::userHasProfile()) {
      $build['#theme'] = 'connecdom__block__profile_options';
    }
    
    return $build;
  }
  
}