<?php

namespace Drupal\connecdom_orthanc\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Series entity.
 *
 * @ingroup connecdom_orthanc
 *
 * @ContentEntityType(
 *   id = "series",
 *   label = @Translation("Series"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\connecdom_orthanc\SeriesEntityListBuilder",
 *     "views_data" = "Drupal\connecdom_orthanc\Entity\SeriesEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\connecdom_orthanc\Form\SeriesEntityForm",
 *       "add" = "Drupal\connecdom_orthanc\Form\SeriesEntityForm",
 *       "edit" = "Drupal\connecdom_orthanc\Form\SeriesEntityForm",
 *       "delete" = "Drupal\connecdom_orthanc\Form\SeriesEntityDeleteForm",
 *     },
 *     "access" = "Drupal\connecdom_orthanc\SeriesEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\connecdom_orthanc\SeriesEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "series",
 *   admin_permission = "administer series entities",
 *   entity_keys = {
 *     "id" = "resource_id",
 *     "label" = "resource_id",
 *     "uuid" = "uuid",
 *     "uid" = "uid",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/connecdom_orthanc/types/series/{series}",
 *     "add-form" = "/admin/structure/connecdom_orthanc/types/series/add",
 *     "edit-form" = "/admin/structure/connecdom_orthanc/types/series/{series}/edit",
 *     "delete-form" = "/admin/structure/connecdom_orthanc/types/series/{series}/delete",
 *     "collection" = "/admin/structure/connecdom_orthanc/types/series",
 *   },
 *   field_ui_base_route = "series.settings"
 * )
 */
class SeriesEntity extends ContentEntityBase implements SeriesEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'uid' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getResourceId() {
    return $this->get('resource_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setResourceId($resource_id) {
    $this->set('resource_id', $resource_id);
    return $this;
  }

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
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Series entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Series is published.'))
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['resource_id'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('Resource ID'))
      ->setDescription(t('The Resource ID from ConnecdomOrthanc.'))
      ->setReadOnly(TRUE);

    return $fields;
  }

}
