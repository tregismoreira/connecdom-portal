<?php

namespace Drupal\connecdom_orthanc\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Modality entities.
 *
 * @ingroup connecdom_orthanc
 */
interface ModalityEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Modality Resource ID.
   *
   * @return string
   *   Resource ID of the Modality.
   */
  public function getResourceId();

  /**
   * Sets the Modality Resource ID.
   *
   * @param string $resource_id
   *   The Modality Resource ID.
   *
   * @return \Drupal\connecdom_orthanc\Entity\ModalityEntityInterface
   *   The called Modality entity.
   */
  public function setResourceId($resource_id);

  /**
   * Gets the Modality creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Modality.
   */
  public function getCreatedTime();

  /**
   * Sets the Modality creation timestamp.
   *
   * @param int $timestamp
   *   The Modality creation timestamp.
   *
   * @return \Drupal\connecdom_orthanc\Entity\ModalityEntityInterface
   *   The called Modality entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Modality published status indicator.
   *
   * Unpublished Modality are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Modality is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Modality.
   *
   * @param bool $published
   *   TRUE to set this Modality to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\connecdom_orthanc\Entity\ModalityEntityInterface
   *   The called Modality entity.
   */
  public function setPublished($published);

}
