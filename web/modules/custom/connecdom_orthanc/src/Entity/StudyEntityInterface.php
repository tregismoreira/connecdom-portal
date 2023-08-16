<?php

namespace Drupal\connecdom_orthanc\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Study entities.
 *
 * @ingroup connecdom_orthanc
 */
interface StudyEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Study Resource ID.
   *
   * @return string
   *   Resource ID of the Study.
   */
  public function getResourceId();

  /**
   * Sets the Study Resource ID.
   *
   * @param string $resource_id
   *   The Study Resource ID.
   *
   * @return \Drupal\connecdom_orthanc\Entity\StudyEntityInterface
   *   The called Study entity.
   */
  public function setResourceId($resource_id);

  /**
   * Gets the Study creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Study.
   */
  public function getCreatedTime();

  /**
   * Sets the Study creation timestamp.
   *
   * @param int $timestamp
   *   The Study creation timestamp.
   *
   * @return \Drupal\connecdom_orthanc\Entity\StudyEntityInterface
   *   The called Study entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Study published status indicator.
   *
   * Unpublished Study are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Study is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Study.
   *
   * @param bool $published
   *   TRUE to set this Study to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\connecdom_orthanc\Entity\StudyEntityInterface
   *   The called Study entity.
   */
  public function setPublished($published);

}
