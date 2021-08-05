<?php
/**
 * @file
 * Settings.php file for multisite Drupal.
 */

// Default Drupal settings.
//
// These are already explained with detailed comments in Drupal's
// default.settings.php file.
//
// See https://api.drupal.org/api/drupal/sites!default!default.settings.php/9
$databases['default']['default'] = [
  'database' => '../data/' . getenv('EMHRM_SQLITE_DATABASE'),
  'driver' => 'sqlite',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\sqlite',
  'prefix' => '',
];

$settings['update_free_access'] = FALSE;
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';

// The hash_salt should be a unique random value for each application.
// If left unset, the settings.platformsh.php file will attempt to provide one.
// You can also provide a specific value here if you prefer and it will be used
// instead. In most cases it's best to leave this blank on Platform.sh. You
// can configure a separate hash_salt in your settings.local.php file for
// local development.
// $settings['hash_salt'] = 'change_me';
$settings['hash_salt'] = 'Ph9pVgUc_9ApY5oOoxc9-TZerO-Mo7PbesxRsUmfBIpftYydh_KO4X0OUzhV1aKH1budP8JGiw';

$subsite_id = basename(__DIR__);

// Set up a config sync directory.
//
// This is defined inside the read-only "config" directory, deployed via Git.
$settings['config_sync_directory'] = '../config/sync/' . $subsite_id;
