<?php
/**
 * @file
 * Platform.sh example settings.php file for Drupal 10.
 */

 // Get the folder name to configure paths.
$subsite_id = basename(__DIR__);


// Default Drupal 9 settings.
//
// These are already explained with detailed comments in Drupal's
// default.settings.php file.
//
// See https://api.drupal.org/api/drupal/sites!default!default.settings.php/9
$databases['default']['default'] = [
  'database' => '../data/' . $subsite_id . '.sqlite',
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

// Set up a config sync directory.
$settings['config_sync_directory'] = '../config/sync/' . $subsite_id;

// Set the file base_url.
$settings['file_public_base_url'] = '/files/' . $subsite_id;

// Set the file path.
$settings['file_public_path'] = '../data/files/' . $subsite_id;

/**
 * Configure sftp servers.
 */
$settings['sftp'] = [
  'ilr_collegenet' => [
    'server' => 'sftp.applyweb.com',
    'username' => getenv('ILR_COLLEGENET_SFTP_USER'),
    'password' => getenv('ILR_COLLEGENET_SFTP_PASSWORD'),
  ],
];

/**
 * Salesforce consumer key for sandbox/dev auth provider. This is an env var
 * because the app consumer key changes every time the sandbox is refreshed.
 */
if (!empty(getenv('SALESFORCE_CONSUMER_KEY_DEV'))) {
  $config['salesforce.salesforce_auth.ilr_marketing_jwt_oauth_dev']['provider_settings']['consumer_key'] = getenv('SALESFORCE_CONSUMER_KEY_DEV');
}

// Set the slack webhoook URL from an environment variable.
if (!empty(getenv('SLACK_WEBHOOK_URL'))) {
  $config['webhook_logger.settings']['slack_webhook_url'] = getenv('SLACK_WEBHOOK_URL');
}

// Automatic Platform.sh settings.
$platformsh_subsite_id = $subsite_id;

if (file_exists($app_root . '/' . $site_path . '/../settings.platformsh.php')) {
  include $app_root . '/' . $site_path . '/../settings.platformsh.php';
}

// Local settings. These come last so that they can override anything.
if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}
