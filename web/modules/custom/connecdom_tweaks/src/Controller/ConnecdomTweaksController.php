<?php
namespace Drupal\connecdom_tweaks\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Drupal\connecdom_orthanc\ConnecdomOrthancRestApi;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\connecdom_orthanc_auth\ConnecdomOrthancAuth;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

class ConnecdomTweaksController extends ControllerBase {

  /**
   *
   */
  public static function studyAssignmentAccess(AccountInterface $account, $id) {
    $study = \Drupal::entityTypeManager()->getStorage('study')->load($id);
    return AccessResult::allowedIf($study->field_study_clinic->entity && $study->field_study_clinic->entity->id() == $account->id());
  }

  /**
   *
   */
  public static function clinicRegenerateTokenAccess(AccountInterface $account, $user) {
    $user = User::load($user);
    return AccessResult::allowedIf($account->hasPermission('manage access tokens') || $user->id() == $account->id());
  }

  /**
   *
   */
  public static function clinicStaffRemoveCallback($clinic, $doctor) {
    $user = User::load($clinic);

    $doctors = $user->get('field_clinic_staff')->getValue();
    $index = array_search($doctor, array_column($doctors, 'target_id'));

    $user->get('field_clinic_staff')->removeItem($index);

    if ($user->save()) {
      drupal_set_message('Doctor successfully removed from your team.');
    }

    $destination = \Drupal::destination()->get();
    return new RedirectResponse(URL::fromUserInput($destination)->toString());
  }

  /**
   *
   */
  public static function clinicStaffAddCallback($clinic, $doctor) {
    $user = User::load($clinic);

    $user->field_clinic_staff[] = $doctor;

    $response = new AjaxResponse();
    if ($user->save()) {
      $response->addCommand(new HtmlCommand('.user-'. $doctor .' .views-field-clinic-staff-add-link', '<span class="success">Added</span>'));
    }

    return $response;
  }

  /**
   *
   */
  public static function reportHasAddPermission($study, $account = NULL) {
    if ($study) {
      $user = $account ? $account : \Drupal::currentUser();

      // Load Doctor ID.
      $doctor_id = $study->field_study_assignee->target_id;

      // If the Doctor is the same as logged in User
      // AND
      // If there's no report added for the current Study.
      if (!self::studyHasReport($study) && $user->id() == $doctor_id) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   *
   */
  public static function reportHasEditPermission($report, $account = NULL) {
    $user = $account ? $account : \Drupal::currentUser();

    // Load Study.
    $study_id = $report->field_report_study->target_id;
    $study = \Drupal::entityTypeManager()->getStorage('study')->load($study_id);

    // Load Doctor ID.
    $doctor_id = $study->field_study_assignee->target_id;

    // If the Doctor is the same as logged in User
    if (in_array('clinic', $user->getRoles()) || (in_array('doctor', $user->getRoles()) && $user->id() == $doctor_id)) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   *
   */
  public function reportGetDoctorFromStudy($study) {
    $doctor_id = $study->field_study_assignee->target_id;

    return $doctor_id;
  }

  /**
   * Get Report status.
   */
  public static function getNodeIdFromStudyId($study_id, $field_name) {
    $query = \Drupal::database()->select('node__'. $field_name, 'n');
    $query->fields('n', ['entity_id']);
    $query->condition('n.' . $field_name . '_target_id', $study_id);

    return $query->execute()->fetchField();
  }

  /**
   * Redirect user to DICOM Web Viewer.
   */
  public static function dicomWebViewer($id) {
    if ($study = self::entityLoadFromResourceId($id, 'study')) {
      $account = \Drupal::currentUser();
      $doctor_id = $study->field_study_assignee->target_id;

      if (($doctor_id && $account->id() == $doctor_id) || in_array('clinic', $account->getRoles())) {
        // Generate and store access token.
        $orthanc_auth = new ConnecdomOrthancAuth();
        $access_token = $orthanc_auth->generateAccessToken();
        // Store access token for Study
        $orthanc_auth->storeAccessToken($access_token, 0, $id);
        // Store access token for Parent Patient
        $orthanc_auth->storeAccessToken($access_token, 0, $study->field_study_parent_patient->target_id);

        // Get file_name and file_path.
        $file_name = $id . '.zip';
        $file_path = tempnam(sys_get_temp_dir(), $file_name);

        // Download file.
        $orthancRestApi = new ConnecdomOrthancRestApi();
        if ($orthancRestApi->studyArchiveRetrieve($id, $file_path, $access_token)) {
          $response = new BinaryFileResponse($file_path, 200, [
            'Content-Type' => 'application/zip, application/octet-stream',
            'Content-Disposition' => 'attachment;filename="' . $file_name . '"'
          ], true);
        }
        else {
          throw new \Exception('Unable to download Study.');
        }
      }
      else {
        throw new AccessDeniedHttpException();
      }
    }
    else {
      throw new \Exception(t('There is no valid study for the given request.'));
    }

    return $response;
  }

  /**
   * Get Study status.
   */
  public static function studyGetStatus($study_id) {
    $query = \Drupal::database()->select('study__field_study_status', 'a');
    $query->fields('a', ['field_study_status_value']);
    $query->condition('a.bundle', 'study');
    $query->condition('a.entity_id', $study_id);

    return $query->execute()->fetchField();
  }

  /**
   * Returns TRUE if there's a Report added for the current Study.
   */
  public static function studyHasReport($study) {
    $connection = \Drupal::database();

    $query = $connection->select('node__field_report_study', 'n');
    $query->condition('n.field_report_study_target_id', $study->resource_id->value);
    $query->fields('n', ['entity_id']);
    $result = $query->countQuery()->execute()->fetchField();

    return (bool) $result;
  }

  /**
   * Load entity from resource_id.
   */
  public static function entityLoadFromResourceId($resource_id, $entity_type) {
    $entity = \Drupal::entityTypeManager()->getStorage($entity_type)->load($resource_id);

    return $entity;
  }

  /**
   *
   */
  public static function doctorAccountIsReady($user) {
    return (bool) $user->field_crm_number->value;
  }

  /**
   *
   */
  public static function userHasProfile($type = NULL) {
    $uid = \Drupal::currentUser()->id();
    $connection = \Drupal::database();

    $query = $connection->select('node_field_data', 'n');

    if ($type) {
      $query->condition('n.type', $type);
    }
    else {
      $or = $query->orConditionGroup()
        ->condition('n.type', 'doctor')
        ->condition('n.type', 'clinic');
    }

    $query->condition($or)
      ->condition('n.uid', $uid)
      ->fields('n', ['nid']);
    $result = $query->countQuery()->execute()->fetchField();

    return $result ? TRUE : FALSE;
  }

}
