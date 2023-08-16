<?php

namespace Drupal\connecdom_orthanc_auth;

use Drupal\Component\Utility\Crypt;

class ConnecdomOrthancAuth {

  /**
   * The database connection.
   */
  protected $connection;

  /**
   * Constructs an ConnecdomOrthancAuth object.
   */
  public function __construct() {
    $this->connection = \Drupal::database();
  }

  /**
   * Generate URL-safe random access token.
   */
  public function generateAccessToken() {
    return str_replace('-', '', Crypt::randomBytesBase64(32));
  }

  /**
   * Store access token in the database.
   */
  public function storeAccessToken($token, $type, $resource = NULL, $sid = NULL) {
    // Abort if there is no token.
    if (!$token) {
      return FALSE;
    }

    // For master tokens (used by import system).
    if ($type == 1) {
      $this->connection->insert('connecdom_orthanc_auth')
        ->fields([
          'token' => $token,
          'type' => $type,
          'expire' => $this->generateExpire(),
        ])
        ->execute();
    }
    // For clinic tokens.
    elseif ($type == 2) {
      $this->connection->insert('connecdom_orthanc_auth')
        ->fields([
          'token' => $token,
          'type' => $type,
        ])
        ->execute();
    }
    else {
      // Load current session id.
      if (!$sid) {
        $sid = \Drupal::service('session')->getId();
      }

      // Retrieve existing access token for a given $sid.
      if ($current_token = $this->retrieveAccessToken($resource, $sid)) {
        $this->deleteAccessToken($current_token, $resource);
      }
      
      // Store in the database.
      $this->connection->insert('connecdom_orthanc_auth')
        ->fields([
          'token' => $token,
          'sid' => $sid,
          'resource' => $resource,
          'expire' => $this->generateExpire(),
        ])
        ->execute();
    }
  }

  /**
   * Validate access token.
   */
  public function validateAccessToken($token, $resource = NULL) {
    // Load configs from `connecdom_orthanc`.
    $config = \Drupal::config('connecdom_orthanc.settings');

    // Bypass if the token is set in the config.
    if ($config->get('orthanc_token') && strtolower($token) == strtolower($config->get('orthanc_token'))) {
      return TRUE;
    }
    else {
      // Retrieve entry for a given token.
      $type = $this->connection->select('connecdom_orthanc_auth', 'a')
        ->fields('a', ['type'])
        ->condition('a.token', $token)
        ->execute()
        ->fetchField();

      // For master tokens.
      if ($type == 1) {
        return TRUE;
      }
      // For clinic tokens.
      elseif ($type == 2) {
        // return TRUE;
      }
      else {
        $entry = $this->connection->select('connecdom_orthanc_auth', 'a')
          ->fields('a', ['sid', 'resource'])
          ->condition('a.token', $token)
          ->condition('a.resource', $resource)
          ->execute()
          ->fetchAll();
        $entry = reset($entry);

        // Get `uid` from `sid`.
        $uid = $this->connection->select('sessions', 's')
          ->fields('s', ['uid'])
          ->condition('s.sid', Crypt::hashBase64($entry->sid))
          ->execute()
          ->fetchField();

        // If there is a corresponding `uid` for the given `sid`, validate token.
        if ($uid) {
          // Delete access token so that it will not be used anymore.
          $this->deleteAccessToken($token, $resource);

          return TRUE;
        }
      }
    }

    return FALSE;
  }

  /**
   * Retrieve existing access token for a given $sid.
   */
  protected function retrieveAccessToken($resource_id, $sid = null) {
    // Load current session id.
    if (!$sid) {
      $sid = \Drupal::service('session')->getId();
    }

    $result = $this->connection->select('connecdom_orthanc_auth', 'a')
      ->fields('a', ['token'])
      ->condition('a.sid', $sid)
      ->condition('a.resource', $resource_id)
      ->execute()
      ->fetchField();

    return $result;
  }

  /**
   * Delete a token entry for a given resource.
   */
  public function deleteAccessToken($token, $resource = NULL) {
    if ($resource) {
      $this->connection->delete('connecdom_orthanc_auth')
        ->condition('token', $token)
        ->condition('resource', $resource)
        ->execute();
    }
    else {
      $this->connection->delete('connecdom_orthanc_auth')
        ->condition('token', $token)
        ->execute();
    }
  }

  /**
   * Generate expiration time.
   */
  protected function generateExpire() {
    return strtotime("+5 minutes");
  }

}
