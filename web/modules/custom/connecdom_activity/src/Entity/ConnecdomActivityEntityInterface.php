<?php

namespace Drupal\connecdom_activity\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Connecdom Activity entities.
 *
 * @ingroup connecdom_activity
 */
interface ConnecdomActivityEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Connecdom Activity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Connecdom Activity.
   */
  public function getCreatedTime();

  /**
   * Sets the Connecdom Activity creation timestamp.
   *
   * @param int $timestamp
   *   The Connecdom Activity creation timestamp.
   *
   * @return \Drupal\connecdom_activity\Entity\ConnecdomActivityEntityInterface
   *   The called Connecdom Activity entity.
   */
  public function setCreatedTime($timestamp);

}
