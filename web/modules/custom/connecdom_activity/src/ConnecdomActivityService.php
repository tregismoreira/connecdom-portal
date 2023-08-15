<?php

namespace Drupal\connecdom_activity;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class ConnecdomActivityService.
 */
class ConnecdomActivityService {

  /**
   * The entity type manager.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;


  /**
   * Constructs a new ConnecdomActivityService object.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Log activity in the database.
   */
  public function log($action, $study, $clinic = NULL, $doctor = NULL, $details = NULL) {
    if ($action && $study) {
      $entity = $this->entityTypeManager->getStorage('connecdom_activity')->create([
        'field_activity_action' => $action,
        'field_activity_study' => $study,
        'field_activity_clinic' => $clinic,
        'field_activity_doctor' => $doctor,
        'field_activity_details' => $details
      ]);
      $entity->save();
    }
  }

}
