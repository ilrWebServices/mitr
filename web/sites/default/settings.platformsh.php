<?php
/**
 * @file
 * Platform.sh settings.
 */

use Drupal\Core\Installer\InstallerKernel;

$platformsh = new \Platformsh\ConfigReader\Config();

// Set up a config sync directory.
//
// This is defined inside the read-only "config" directory, deployed via Git.
$settings['config_sync_directory'] = '../config/sync';

// Enable verbose error messages on development branches, but not on the production branch.
// You may add more debug-centric settings here if desired to have them automatically enable
// on development but not production.
if (isset($platformsh->branch)) {
  // Production type environment.
  if ($platformsh->onProduction() || $platformsh->onDedicated()) {
    $config['system.logging']['error_level'] = 'hide';
  } // Development type environment.
  else {
    $config['system.logging']['error_level'] = 'verbose';
  }
}

if ($platformsh->inRuntime()) {
  // Configure private and temporary file paths.
  if (!isset($settings['file_private_path'])) {
    $settings['file_private_path'] = $platformsh->appDir . '/private';
  }
  if (!isset($settings['file_temp_path'])) {
    $settings['file_temp_path'] = $platformsh->appDir . '/tmp';
  }

  // Configure the default PhpStorage and Twig template cache directories.
  if (!isset($settings['php_storage']['default'])) {
    $settings['php_storage']['default']['directory'] = $settings['file_private_path'];
  }
  if (!isset($settings['php_storage']['twig'])) {
    $settings['php_storage']['twig']['directory'] = $settings['file_private_path'];
  }

  // Set the project-specific entropy value, used for generating one-time
  // keys and such.
  $settings['hash_salt'] = empty($settings['hash_salt']) ? $platformsh->projectEntropy : $settings['hash_salt'];

  // Set the deployment identifier, which is used by some Drupal cache systems.
  $settings['deployment_identifier'] = $settings['deployment_identifier'] ?? $platformsh->treeId;
}

// The 'trusted_hosts_pattern' setting allows an admin to restrict the Host header values
// that are considered trusted.  If an attacker sends a request with a custom-crafted Host
// header then it can be an injection vector, depending on how the Host header is used.
// However, Platform.sh already replaces the Host header with the route that was used to reach
// Platform.sh, so it is guaranteed to be safe.  The following line explicitly allows all
// Host headers, as the only possible Host header is already guaranteed safe.
$settings['trusted_host_patterns'] = ['.*'];

// Import variables prefixed with 'drupalsettings:' into $settings
// and 'drupalconfig:' into $config.
foreach ($platformsh->variables() as $name => $value) {
  $parts = explode(':', $name);
  list($prefix, $key) = array_pad($parts, 3, null);
  switch ($prefix) {
    // Variables that begin with `drupalsettings` or `drupal` get mapped
    // to the $settings array verbatim, even if the value is an array.
    // For example, a variable named drupalsettings:example-setting' with
    // value 'foo' becomes $settings['example-setting'] = 'foo';
    case 'drupalsettings':
    case 'drupal':
      $settings[$key] = $value;
      break;
    // Variables that begin with `drupalconfig` get mapped to the $config
    // array.  Deeply nested variable names, with colon delimiters,
    // get mapped to deeply nested array elements. Array values
    // get added to the end just like a scalar. Variables without
    // both a config object name and property are skipped.
    // Example: Variable `drupalconfig:conf_file:prop` with value `foo` becomes
    // $config['conf_file']['prop'] = 'foo';
    // Example: Variable `drupalconfig:conf_file:prop:subprop` with value `foo` becomes
    // $config['conf_file']['prop']['subprop'] = 'foo';
    // Example: Variable `drupalconfig:conf_file:prop:subprop` with value ['foo' => 'bar'] becomes
    // $config['conf_file']['prop']['subprop']['foo'] = 'bar';
    // Example: Variable `drupalconfig:prop` is ignored.
    case 'drupalconfig':
      if (count($parts) > 2) {
        $temp = &$config[$key];
        foreach (array_slice($parts, 2) as $n) {
          $prev = &$temp;
          $temp = &$temp[$n];
        }
        $prev[$n] = $value;
      }
      break;
  }
}

// onProduction() currently assumes that the `master` branch exists. We use
// `main`, hence the extra check.
if ($platformsh->onProduction() || (isset($platformsh->branch) && $platformsh->branch == 'main')) {
  // Switch the salesforce auth provider for production sites. Otherwise, use
  // the default, which should be for dev.
  $config['salesforce.settings']['salesforce_auth_provider'] = 'ilr_marketing_jwt_oauth';
}
