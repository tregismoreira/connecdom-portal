<?php

/**
 * @file
 * Contains connecdom_tweaks\connecdom_tweaks.views.inc..
 * Provide a custom views field data that isn't tied to any other module. */

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Render\Markup;
use Drupal\field\FieldConfigInterface;
use Drupal\field\FieldStorageConfigInterface;
use Drupal\system\ActionConfigEntityInterface;

/**
* Implements hook_views_data().
*/
function connecdom_tweaks_views_data() {
  $data['views']['dicom_viewer_link'] = [
    'title' => t('DICOM Web Viewer Link'),
    'help' => t('Link to the external DICOM Web Viewer.'),
    'field' => [
      'id' => 'dicom_viewer_link',
    ],
  ];

  $data['views']['study_modality_field'] = [
    'title' => t('Study Modality'),
    'help' => t('List the Modalities from the series related to the Study.'),
    'field' => [
      'id' => 'study_modality_field',
    ],
  ];

  $data['views']['study_assign_link'] = [
    'title' => t('Study Assign link'),
    'help' => t('Assign a doctor from a Study.'),
    'field' => [
      'id' => 'study_assign_link',
    ],
  ];

  $data['views']['study_unassign_link'] = [
    'title' => t('Study Unassign link'),
    'help' => t('Unassign a doctor from a Study.'),
    'field' => [
      'id' => 'study_unassign_link',
    ],
  ];

  $data['views']['clinic_staff_add_link'] = [
    'title' => t('Clinic Staff Add link'),
    'help' => t('Link to add Doctor to a Clinic.'),
    'field' => [
      'id' => 'clinic_staff_add_link',
    ],
  ];

  $data['views']['clinic_staff_remove_link'] = [
    'title' => t('Clinic Staff Remove link'),
    'help' => t('Link to remove Doctor to a Clinic.'),
    'field' => [
      'id' => 'clinic_staff_remove_link',
    ],
  ];

  $data['views']['report_add_edit_link'] = [
    'title' => t('Report Add/Edit link'),
    'help' => t('Add/edit report associated with a Study.'),
    'field' => [
      'id' => 'report_add_edit_link',
    ],
  ];

  $data['views']['report_add_view_link'] = [
    'title' => t('Report Add/View link'),
    'help' => t('Add/view report associated with a Study.'),
    'field' => [
      'id' => 'report_add_view_link',
    ],
  ];

  $data['views']['study_info_add_link'] = [
    'title' => t('Add Additional Info'),
    'help' => t('Add additional info to Study.'),
    'field' => [
      'id' => 'study_info_add_link',
    ],
  ];

  $data['views']['study_info_edit_link'] = [
    'title' => t('Edit Additional Info'),
    'help' => t('Edit additional info.'),
    'field' => [
      'id' => 'study_info_edit_link',
    ],
  ];

  $data['views']['study_info_view_link'] = [
    'title' => t('View Additional Info'),
    'help' => t('View additional info.'),
    'field' => [
      'id' => 'study_info_view_link',
    ],
  ];

  $data['views']['report_add_link'] = [
    'title' => t('Add Report link'),
    'help' => t('Add a new Report.'),
    'field' => [
      'id' => 'report_add_link',
    ],
  ];

  $data['views']['report_edit_link'] = [
    'title' => t('Edit Report link'),
    'help' => t('Edit Report.'),
    'field' => [
      'id' => 'report_edit_link',
    ],
  ];

  $data['views']['report_view_link'] = [
    'title' => t('View Report link'),
    'help' => t('View Report.'),
    'field' => [
      'id' => 'report_view_link',
    ],
  ];

  $data['views']['report_download_link'] = [
    'title' => t('Download Report link'),
    'help' => t('Download Report.'),
    'field' => [
      'id' => 'report_download_link',
    ],
  ];

  $data['views']['report_publish_link'] = [
    'title' => t('Publish Report link'),
    'help' => t('Publish Report.'),
    'field' => [
      'id' => 'report_publish_link',
    ],
  ];

  return $data;
}
