<?php

namespace Drupal\connecdom_orthanc\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Series entities.
 *
 * @ingroup connecdom_orthanc
 */
interface SeriesEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Series Resource ID.
   *
   * @return string
   *   Resource ID of the Series.
   */
  public function getResourceId();

  /**
   * Sets the Series Resource ID.
   *
   * @param string $resource_id
   *   The Series Resource ID.
   *
   * @return \Drupal\connecdom_orthanc\Entity\SeriesEntityInterface
   *   The called Series entity.
   */
  public function setResourceId($resource_id);

  /**
   * Gets the Series creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Series.
   */
  public function getCreatedTime();

  /**
   * Sets the Series creation timestamp.
   *
   * @param int $timestamp
   *   The Series creation timestamp.
   *
   * @return \Drupal\connecdom_orthanc\Entity\SeriesEntityInterface
   *   The called Series entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Series published status indicator.
   *
   * Unpublished Series are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Series is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Series.
   *
   * @param bool $published
   *   TRUE to set this Series to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\connecdom_orthanc\Entity\SeriesEntityInterface
   *   The called Series entity.
   */
  public function setPublished($published);

}
