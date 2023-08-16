<?php

namespace Drupal\connecdom_orthanc\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Instance entities.
 *
 * @ingroup connecdom_orthanc
 */
interface InstanceEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Instance Resource ID.
   *
   * @return string
   *   Resource ID of the Instance.
   */
  public function getResourceId();

  /**
   * Sets the Instance Resource ID.
   *
   * @param string $resource_id
   *   The Instance Resource ID.
   *
   * @return \Drupal\connecdom_orthanc\Entity\InstanceEntityInterface
   *   The called Instance entity.
   */
  public function setResourceId($resource_id);

  /**
   * Gets the Instance creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Instance.
   */
  public function getCreatedTime();

  /**
   * Sets the Instance creation timestamp.
   *
   * @param int $timestamp
   *   The Instance creation timestamp.
   *
   * @return \Drupal\connecdom_orthanc\Entity\InstanceEntityInterface
   *   The called Instance entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Instance published status indicator.
   *
   * Unpublished Instance are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Instance is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Instance.
   *
   * @param bool $published
   *   TRUE to set this Instance to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\connecdom_orthanc\Entity\InstanceEntityInterface
   *   The called Instance entity.
   */
  public function setPublished($published);

}
