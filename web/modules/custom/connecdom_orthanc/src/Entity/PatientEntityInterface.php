<?php

namespace Drupal\connecdom_orthanc\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Patient entities.
 *
 * @ingroup connecdom_orthanc
 */
interface PatientEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Patient Resource ID.
   *
   * @return string
   *   Resource ID of the Patient.
   */
  public function getResourceId();

  /**
   * Sets the Patient Resource ID.
   *
   * @param string $resource_id
   *   The Patient Resource ID.
   *
   * @return \Drupal\connecdom_orthanc\Entity\PatientEntityInterface
   *   The called Patient entity.
   */
  public function setResourceId($resource_id);

  /**
   * Gets the Patient creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Patient.
   */
  public function getCreatedTime();

  /**
   * Sets the Patient creation timestamp.
   *
   * @param int $timestamp
   *   The Patient creation timestamp.
   *
   * @return \Drupal\connecdom_orthanc\Entity\PatientEntityInterface
   *   The called Patient entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Patient published status indicator.
   *
   * Unpublished Patient are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Patient is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Patient.
   *
   * @param bool $published
   *   TRUE to set this Patient to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\connecdom_orthanc\Entity\PatientEntityInterface
   *   The called Patient entity.
   */
  public function setPublished($published);

}
