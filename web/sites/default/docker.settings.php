<?php

$databases['default']['default'] = [
  'database' => 'drupal',
  'username' => 'drupal',
  'password' => 'password',
  'host' => 'database',
  'port' => '3306',
  'driver' => 'mysql',
];

$config_directories = array(
  CONFIG_SYNC_DIRECTORY => '/var/www/html/config/sync'
);

$settings['hash_salt'] = 'DeyqgEiAeuWIVCsTPceSj3jhFDLexLgL5P5FH7Wz7OR-_JhLa2sGOHZmTqb-7dEvaMBcXm9T0A';

$settings['update_free_access'] = FALSE;

$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';

$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

$settings['entity_update_batch_size'] = 50;

$settings['entity_update_backup'] = TRUE;
