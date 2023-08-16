<?php
/**
 * @file
 * Lando settings.
 */

// Set the database creds
$databases['default']['default'] = [
  'database' => 'drupal9',
  'username' => 'drupal9',
  'password' => 'drupal9',
  'host' => 'database',
  'port' => '3306',
  'driver' => 'mysql',
  'prefix' => '',
];
// And a bogus hashsalt for now
$settings['hash_salt'] = json_encode($databases);

global $content_directories;
$content_directories['sync'] = $app_root . '/../config/sync';
$settings['config_sync_directory'] = $app_root . '/../config/sync';

