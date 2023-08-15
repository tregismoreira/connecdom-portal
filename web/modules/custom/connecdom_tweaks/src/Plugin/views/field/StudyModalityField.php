<?php

namespace Drupal\connecdom_tweaks\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Random;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("study_modality_field")
 */
class StudyModalityField extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function usesGroupBy() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Do nothing -- to override the parent query.
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['hide_alter_empty'] = ['default' => FALSE];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $resource_id = $values->resource_id;

    $query_series = \Drupal::database()->select('study__field_study_series', 's');
    $query_series->fields('s', ['field_study_series_target_id']);
    $query_series->condition('s.entity_id', $resource_id);
    $series = $query_series->execute()->fetchAll();

    $modalities = [];
    foreach ($series as $key => $value) {
      $resource_id = $value->field_study_series_target_id;

      $query_modality = \Drupal::database()->select('series__field_series_modality', 'm');
      $query_modality->addField('m', 'field_series_modality_value', 'modality');
      $query_modality->condition('m.entity_id', $resource_id);
      $modality = $query_modality->execute()->fetchField();

      $modalities[$modality] = $modality;
    }

    return implode(', ', $modalities);
  }

}
