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
 * @ViewsField("clinic_staff_add_link")
 */
class ClinicStaffAddLink extends LinkBase {

  /**
   * {@inheritdoc}
   */
  protected function getUrlInfo(ResultRow $row) {
    if ($row) {
      $params = \Drupal::routeMatch()->getParameters();
      return Url::fromRoute('connecdom_tweaks.clinic_staff_add', ['clinic' => $params->get('user'), 'doctor' => $row->uid]);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function renderLink(ResultRow $row) {
    if ($row) {
      $this->options['alter']['make_link'] = TRUE;
      $this->options['alter']['url'] = $this
        ->getUrlInfo($row);
      $this->options['alter']['link_class'] = 'btn btn-success btn-sm use-ajax';
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
    return $this->t('Add to staff');
  }

}
