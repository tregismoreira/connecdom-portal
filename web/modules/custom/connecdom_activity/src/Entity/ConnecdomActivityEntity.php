<?php

namespace Drupal\connecdom_activity\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Connecdom Activity entity.
 *
 * @ingroup connecdom_activity
 *
 * @ContentEntityType(
 *   id = "connecdom_activity",
 *   label = @Translation("Connecdom Activity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\connecdom_activity\ConnecdomActivityEntityListBuilder",
 *     "views_data" = "Drupal\connecdom_activity\Entity\ConnecdomActivityEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\connecdom_activity\Form\ConnecdomActivityEntityForm",
 *       "add" = "Drupal\connecdom_activity\Form\ConnecdomActivityEntityForm",
 *       "edit" = "Drupal\connecdom_activity\Form\ConnecdomActivityEntityForm",
 *       "delete" = "Drupal\connecdom_activity\Form\ConnecdomActivityEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\connecdom_activity\ConnecdomActivityEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\connecdom_activity\ConnecdomActivityEntityAccessControlHandler",
 *   },
 *   base_table = "connecdom_activity",
 *   translatable = FALSE,
 *   admin_permission = "administer connecdom activity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "id",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/connecdom_activity/{connecdom_activity}",
 *     "add-form" = "/admin/structure/connecdom_activity/add",
 *     "edit-form" = "/admin/structure/connecdom_activity/{connecdom_activity}/edit",
 *     "delete-form" = "/admin/structure/connecdom_activity/{connecdom_activity}/delete",
 *     "collection" = "/admin/structure/connecdom_activity",
 *   },
 *   field_ui_base_route = "connecdom_activity.settings"
 * )
 */
class ConnecdomActivityEntity extends ContentEntityBase implements ConnecdomActivityEntityInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['status']->setDescription(t('A boolean indicating whether the Connecdom Activity is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
