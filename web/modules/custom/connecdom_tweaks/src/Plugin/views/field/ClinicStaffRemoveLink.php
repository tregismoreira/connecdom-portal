<?php

namespace Drupal\connecdom_tweaks\Plugin\views\field;

use Drupal\Core\Url;
use Drupal\views\Plugin\views\field\LinkBase;
use Drupal\views\ResultRow;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("clinic_staff_remove_link")
 */
class ClinicStaffRemoveLink extends LinkBase {

  /**
   * {@inheritdoc}
   */
  protected function getUrlInfo(ResultRow $row) {
    if ($row) {
      $doctor = $row->users_field_data_user__field_clinic_staff_uid;
      $clinic = $row->uid;

      if ($doctor && $clinic) {
        return Url::fromRoute('connecdom_tweaks.clinic_staff_remove', ['clinic' => $clinic, 'doctor' => $doctor]);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function renderLink(ResultRow $row) {
    if ($row) {
      $this->options['alter']['query'] = $this->getDestinationArray();
      $this->options['alter']['make_link'] = TRUE;
      $this->options['alter']['url'] = $this
        ->getUrlInfo($row);
      $this->options['alter']['link_class'] = 'btn btn-danger btn-sm';
      $text = !empty($this->options['text']) ? $this
        ->sanitizeValue($this->options['text']) : $this
        ->getDefaultLabel();
      $this
        ->addLangcode($row);
      return $text;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultLabel() {
    return $this->t('Remove from staff');
  }

}
