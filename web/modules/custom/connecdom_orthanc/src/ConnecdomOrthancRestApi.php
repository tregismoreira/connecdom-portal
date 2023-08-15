<?php
namespace Drupal\connecdom_orthanc;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Drupal\connecdom_orthanc_auth\ConnecdomOrthancAuth;

class ConnecdomOrthancRestApi {

  /**
   * The config object.
   */
  protected $config;

  /**
   * The database connection.
   */
  protected $connection;

  /**
   * The Orthanc server base URL.
   */
  protected $baseUrl;

  /**
   * The HTTP client.
   */
  protected $httpClient;

  /**
   * The HTTP client.
   */
  protected $client;

  /**
   * Constructs an OrthancRestApi object.
   */
  public function __construct() {
    $this->config = \Drupal::configFactory()->getEditable('connecdom_orthanc.settings');
    if (!$this->config) {
      throw new Exception('Unable to load connecdom_orthanc.settings');
    }
    $this->connection = \Drupal::database();
    $this->baseUrl = $this->config->get('orthanc_url');
    // $this->entityManager = $entity_manager;
    $this->client = new Client([
      'base_uri' => $this->baseUrl,
      'auth' => [
        $this->config->get('orthanc_username'),
        $this->config->get('orthanc_password'),
      ],
      'headers' => [
        'Content-Type' => 'application/json',
      ],
    ]);
  }

  /**
   * Import all Resources from Changes.
   */
  public function importResources($changes) {
    // Generate and store master access token.
    $orthanc_auth = new ConnecdomOrthancAuth();
    $access_token = $orthanc_auth->generateAccessToken();
    $orthanc_auth->storeAccessToken($access_token, 1);

    $resources = [
      'patients' => [],
      'studies' => [],
      'series' => [],
      'instances' => [],
    ];

    foreach ($changes as $change) {
      if ($change->ChangeType == 'NewInstance') {
        // Instance
        $instance = $this->fetchResource('instance', $change->ID, $access_token);

        // ParentSeries
        if (!in_array($instance->ParentSeries, array_keys($resources['series']))) {
          $series = $this->fetchResource('series', $instance->ParentSeries, $access_token);

          // ParentStudy
          if (!in_array($series->ParentStudy, array_keys($resources['studies']))) {
            $study = $this->fetchResource('study', $series->ParentStudy, $access_token);

            // ParentPatient
            if (!in_array($study->ParentPatient, array_keys($resources['patients']))) {
              $patient = $this->fetchResource('patient', $study->ParentPatient, $access_token);
              if ($this->importResource('patient', $patient)) {
                $resources['patients'][$patient->ID][] = $patient;
              }
            }

            if ($this->importResource('study', $study)) {
              $resources['studies'][$study->ID][] = $study;
            }
          }

          if ($this->importResource('series', $series)) {
            $resources['series'][$series->ID][] = $series;
          }
        }

        if ($this->importResource('instance', $instance)) {
          $resources['instances'][$instance->ID][] = $instance;
        }
      }

      // Store resource change in the database.
      $this->storeChange($change);
    }

    // Log the number of resources imported.
    foreach ($resources as $type => $values) {
      if (count($values) > 0) {
        \Drupal::logger('connecdom_orthanc')->notice(t('Successfully imported @count @resource_type: @resources.', [
          '@count' => count($values),
          '@resource_type' => $type,
          '@resources' => implode(', ', array_keys($values)),
        ]));
      }
    }

    return $resources;
  }

  /**
   * Import a Resource from a specific type.
   */
  protected function importResource($resource_type, $resource) {
    // Ignore if the resource already exists.
    if ($this->checkIfResourceExists($resource_type, $resource->ID)) {
      // @TODO: fix duplicated message due to the fetch chain in `importResources`.
      // \Drupal::logger('connecdom_orthanc')->notice(t('Resource already exists: @resource_type (@resource_id). Ignoring.', [
      //   '@resource_type' => $resource_type,
      //   '@resource_id' => $resource->ID,
      // ]));

      return FALSE;
    }

    $entity = entity_create($resource_type);
    $entity->resource_id = $resource->ID ? $resource->ID : NULL;

    // Map custom fields for each resource_type.
    switch ($resource_type) {
      case 'patient':
        $entity->field_patient_birthdate->value = isset($resource->MainDicomTags->PatientBirthDate) ? $this->formatDate($resource->MainDicomTags->PatientBirthDate) : NULL;
        $entity->field_patient_id->value = isset($resource->MainDicomTags->PatientID) ? $resource->MainDicomTags->PatientID : NULL;
        $entity->field_patient_sex->value = isset($resource->MainDicomTags->PatientSex) ? $resource->MainDicomTags->PatientSex : NULL;
        $entity->field_patient_name->value = isset($resource->MainDicomTags->PatientName) ? $resource->MainDicomTags->PatientName : NULL;
        break;

      case 'study':
        // $file = $this->downloadStudy($resource->ID);
        // $entity->field_study_file->target_id = $file ? $file->id() : NULL;
        $entity->field_study_id->value = isset($resource->MainDicomTags->StudyID) ? $resource->MainDicomTags->StudyID : NULL;
        $entity->field_study_physician_name->value = isset($resource->MainDicomTags->ReferringPhysicianName) ? $resource->MainDicomTags->ReferringPhysicianName : NULL;
        $entity->field_study_description->value = isset($resource->MainDicomTags->StudyDescription) ? $resource->MainDicomTags->StudyDescription : NULL;
        $entity->field_study_date->value = isset($resource->MainDicomTags->StudyDate) ? $this->formatDate($resource->MainDicomTags->StudyDate) : NULL;
        $entity->field_study_parent_patient = isset($resource->ParentPatient) ? $resource->ParentPatient : NULL;
        if (isset($resource->MainDicomTags->InstitutionName)) {
          $institution_name = explode('#', $resource->MainDicomTags->InstitutionName);

          if ($institution_name[1]) {
            $entity->field_study_institution_name = $institution_name[0];
            $entity->field_study_clinic = $institution_name[1];
          }
        }
        break;

      case 'series':
        $entity->field_series_body_part_examined->value = isset($resource->MainDicomTags->BodyPartExamined) ? $resource->MainDicomTags->BodyPartExamined : NULL;
        $entity->field_series_description->value = isset($resource->MainDicomTags->SeriesDescription) ? $resource->MainDicomTags->SeriesDescription : NULL;
        $entity->field_series_protocol_name->value = isset($resource->MainDicomTags->ProtocolName) ? $resource->MainDicomTags->ProtocolName : NULL;
        $entity->field_series_modality->value = isset($resource->MainDicomTags->Modality) ? $resource->MainDicomTags->Modality : NULL;
        $entity->field_series_date->value = isset($resource->MainDicomTags->SeriesDate) ? $this->formatDate($resource->MainDicomTags->SeriesDate) : NULL;
        $entity->field_series_parent_study = isset($resource->ParentStudy) ? $resource->ParentStudy : NULL;
        break;

      case 'instance':
        $entity->field_instance_file_size->value = isset($resource->FileSize) ? $resource->FileSize : NULL;
        $entity->field_instance_file_uuid->value = isset($resource->FileUuid) ? $resource->FileUuid : NULL;
        $entity->field_instance_image_comments->value = isset($resource->MainDicomTags->ImageComments) ? $resource->MainDicomTags->ImageComments : NULL;
        $entity->field_instance_parent_series = isset($resource->ParentSeries) ? $resource->ParentSeries : NULL;
        break;
    }

    if ($entity->save()) {
      return TRUE;
    }
  }

  /**
   * Fetch a single Resource.
   */
  public function fetchResource($resource_type, $resource_id, $access_token) {
    try {
      $endpoints = [
        'patient' => 'patients',
        'study' => 'studies',
        'series' => 'series',
        'instance' => 'instances',
      ];
      $endpoint = $endpoints[$resource_type] . '/' . $resource_id;
      $response = $this->client->get($endpoint, ['query' => ['access_token' => $access_token]]);
      $body = json_decode($response->getBody()->getContents());

      return $body;
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addMessage(t('An error occurred while trying to fetch the Orthanc resources. Please check the system logs.'), 'error');
      \Drupal::logger('connecdom_orthanc')->error($e);
    }
  }

  /**
   * Delete all resources of a specific type.
   */
  public function deleteResources($resource_type) {
    $entity_manager = \Drupal::entityManager();
    $results = $entity_manager->getStorage($resource_type)->loadMultiple();
    $entity_ids = array_keys($results);

    if (count($results) > 0) {
      entity_delete_multiple($resource_type, $entity_ids);

      \Drupal::logger('connecdom_orthanc')->notice(t('Successfully deleted @count resources of type @resource_type.', [
        '@count' => count($results),
        '@resource_type' => $resource_type
      ]));
    }
  }

  /**
   * Check if a specific resource already exists.
   */
  protected function checkIfResourceExists($resource_type, $resource_id) {
    $result = $this->connection->select($resource_type, 'r')
      ->fields('r', ['resource_id'])
      ->condition('r.resource_id', $resource_id)
      ->execute()
      ->fetchField();
    return $result;
  }

  /**
   * Query the latest change Seq.
   */
  protected function getLastChangeSeq() {
    $query = $this->connection->select('connecdom_orthanc_changes', 'c')
      ->fields('c', ['seq'])
      ->orderBy('seq', 'DESC')
      ->range(0, 1);
    $result = $query->execute()->fetchField();

    return $result ? $result : 0;
  }

  /**
   * Fetch changes from Orthanc server.
   */
  public function fetchChanges() {
    try {
      $endpoint = '/changes';
      $options = [
        'query' => [
          'since' => $this->getLastChangeSeq(),
          'limit' => $this->config->get('orthanc_changes_limit'),
          'access_token' => $this->config->get('orthanc_token'),
        ]
      ];
      $response = $this->client->get($endpoint, $options);

      // Decode the body JSON string.
      $body = json_decode($response->getBody()->getContents());

      return $body;
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addMessage(t('An error occurred while trying to fetch the Orthanc changes. Please check the system logs.'), 'error');
      \Drupal::logger('connecdom_orthanc')->error($e);
    }
  }

  /**
   * Store change history in the database.
   */
  protected function storeChange($change) {
    $this->connection->insert('connecdom_orthanc_changes')
      ->fields([
        'change_type' => $change->ChangeType,
        'date' => $change->Date,
        'id' => $change->ID,
        'path' => $change->Path,
        'resource_type' => $change->ResourceType,
        'seq' => $change->Seq,
      ])
      ->execute();
  }

  /**
   * Download Study as archive.
   */
  public function studyArchiveRetrieve($resource_id, $file_path, $access_token) {
    $endpoint = 'studies/' . $resource_id . '/archive';

    $file = fopen($file_path, 'w');

    $client = new Client([
      RequestOptions::SINK => $file,
      RequestOptions::VERIFY => false,
      RequestOptions::TIMEOUT => 0
    ]);

    return $client->request('GET', $endpoint, [
      'base_uri' => $this->baseUrl,
      'query' => [
        'access_token' => $access_token,
      ],
    ]);
  }

  // /**
  //  * Download Study as archive.
  //  */
  // public function downloadStudy($resource_id) {
  //   // Get field definition.
  //   $field_instance = \Drupal::entityTypeManager()
  //     ->getStorage('field_config')
  //     ->load('study.study.field_study_file');

  //   // Load file variables.
  //   $file_dir = $field_instance->getSettings()['uri_scheme'] . '://' . $field_instance->getThirdPartySettings('filefield_paths')['file_path']['value'];
  //   $file_name = $resource_id . '.zip';
  //   $file_temp = tempnam(sys_get_temp_dir(), $file_name);
  //   $file_handle = fopen($file_temp, 'w');

  //   // Download file.
  //   $client = new Client([
  //     RequestOptions::SINK => $file_handle,
  //     RequestOptions::VERIFY => false,
  //     RequestOptions::TIMEOUT => 0
  //   ]);
  //   $endpoint = 'studies/' . $resource_id . '/archive';
  //   $client->request('GET', $endpoint, [
  //     'base_uri' => $this->baseUrl,
  //     'auth' => [
  //       $this->config->get('orthanc_username'),
  //       $this->config->get('orthanc_password'),
  //     ],
  //   ]);
  //   fclose($file_handle);

  //   // Save file entity.
  //   file_prepare_directory($file_dir, FILE_CREATE_DIRECTORY);
  //   if ($file = file_save_data(fopen($file_temp, 'r'), $file_dir . '/' . $file_name, FILE_EXISTS_REPLACE)) {
  //     return $file;
  //   }
  //   else {
  //     throw new Exception('Unable to download Study.');
  //   }
  // }

  /**
   * Check if the field is not empty.
   */
  protected function checkFieldValue($field) {
    return $field ? $field : NULL;
  }

  /**
   * Format date.
   */
  protected function formatDate($raw) {
    if ($raw) {
      $raw = str_replace('.', '', $raw);
      $date = \DateTime::createFromFormat('Ymd', $raw);
      return $date->format('Y-m-d');
    }
  }
}