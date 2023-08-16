<?php

namespace Drupal\connecdom_orthanc\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

class ConnecdomOrthancController extends ControllerBase {

  /**
   * Show admin options.
   *
   * @return array
   *   Array of page elements to render.
   */
  public function adminOptions() {
    $output = [
      '#theme' => 'admin_block_content',
      '#content' => [],
    ];

    $options = [
      'entity_types' => [
        'label' => $this->t('Entity types'),
        'route' => 'connecdom_orthanc.entity_types',
      ],
      'modalities' => [
        'label' => $this->t('Modalities'),
        'route' => 'entity.modality.collection',
      ],
      'settings' => [
        'label' => $this->t('Settings'),
        'route' => 'connecdom_orthanc.settings',
      ],
    ];

    foreach ($options as $key => $value) {
      $output['#content'][] = [
        'url' => Url::fromRoute($value['route']),
        'title' => $value['label'],
      ];
    }

    return $output;
  }

  /**
   * Builds the entity types overview page.
   *
   * @return array
   *   A render array as expected by the renderer.
   */
  public function entityTypeList() {
    $headers = [
      $this
        ->t('Type'),
      $this
        ->t('Operations'),
    ];
    
    $rows = [];

    $types = [
      'patient' => $this->t('Patient'),
      'study' => $this->t('Study'),
      'series' => $this->t('Series'),
      'instance' => $this->t('Instance'),
    ];

    foreach ($types as $entity_type_id => $entity_type) {
      $row['type'] = [
        'data' => $entity_type,
        'class' => 'table-filter-text-source',
      ];

      $row['operations']['data'] = [
        '#type' => 'operations',
        '#links' => [
          'list' => [
            'title' => $this->t('List'),
            'url' => Url::fromRoute('entity.' . $entity_type_id . '.collection'),
          ],
          'settings' => [
            'title' => $this->t('Settings'),
            'url' => Url::fromRoute($entity_type_id . '.settings'),
          ],
        ],
      ];

      $rows[$entity_type_id] = $row;
    }

    $output['entities'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => $this
        ->t('No entity types found.'),
      '#sticky' => TRUE,
      '#attributes' => [
        'class' => [
          'connecdom_orthanc-entity-type-list',
        ],
      ],
    ];

    return $output;
  }

}