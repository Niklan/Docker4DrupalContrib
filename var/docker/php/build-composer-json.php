#!/usr/bin/env php
<?php

declare(strict_types=1);

$project_name = getenv('DRUPAL_PROJECT_NAME');
$path = getenv('DRUPAL_PROJECT_PATH') . '/composer.json';
$composer_project = json_decode(file_get_contents($path), TRUE);
$composer_default = default_composer();

if (isset($composer_project['require-dev']['drupal/core'])) {
  unset($composer_project['require-dev']['drupal/core-recommended']);
}

$composer_result = merge_deep($composer_default, $composer_project);
$composer_result['scripts']['post-update-cmd'][] = 'ln -fs /var/www/html/project /var/www/html/web/modules/project';

$composer_result = \array_filter($composer_result);
file_put_contents(
  filename: getenv('APP_ROOT') . '/composer.json',
  data: json_encode($composer_result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
);

/**
 * Get default composer.json contents.
 */
function default_composer(): array {
  $project_name = getenv('DRUPAL_PROJECT_NAME') ?: 'foo';
  $drupal_constraint = getenv('DRUPAL_CONSTRAINT') ?: '~10.0.0';
  $web_root = 'web';

  return [
    'name' => 'drupal/' . $project_name,
    'type' => 'drupal-module',
    'description' => 'A description',
    'license' => 'GPL-2.0-or-later',
    'repositories' => [
      'drupal' => [
        'type' => 'composer',
        'url' => 'https://packages.drupal.org/8',
      ],
    ],
    'require' => [],
    'require-dev' => [
      'composer/installers' => '^1 || ^2',
      'drupal/core-composer-scaffold' => $drupal_constraint,
      'cweagans/composer-patches' => '~1.0',
      'drupal/core-recommended' => $drupal_constraint,
      'drupal/core-dev' => $drupal_constraint,
      'php-parallel-lint/php-parallel-lint' => '^1.2',
    ],
    'minimum-stability' => 'dev',
    'prefer-stable' => TRUE,
    'config' => [
      'process-timeout' => 36000,
      'allow-plugins' => [
        "dealerdirect/phpcodesniffer-composer-installer" => TRUE,
        "composer/installers" => TRUE,
        "cweagans/composer-patches" => TRUE,
        "drupal/core-composer-scaffold" => TRUE,
        "drupalspoons/composer-plugin" => TRUE,
        "phpstan/extension-installer" => TRUE,
      ],
    ],
    'extra' => [
      'installer-paths' => [
        $web_root . '/core' => [
          0 => 'type:drupal-core',
        ],
        $web_root . '/libraries/{$name}' => [
          0 => 'type:drupal-library',
        ],
        $web_root . '/modules/contrib/{$name}' => [
          0 => 'type:drupal-module',
        ],
        $web_root . '/profiles/{$name}' => [
          0 => 'type:drupal-profile',
        ],
        $web_root . '/themes/{$name}' => [
          0 => 'type:drupal-theme',
        ],
        'drush/{$name}' => [
          0 => 'type:drupal-drush',
        ],
      ],
      'drupal-scaffold' => [
        'locations' => [
          'web-root' => $web_root . '/',
        ],
      ],
      'drush' => [
        'services' => [
          'drush.services.yml' => '^9 || ^10 || ^11',
        ],
      ],
    ],
  ];
}

/**
 * Deeply merges arrays. Borrowed from Drupal core.
 */
function merge_deep(): array {
  return merge_deep_array(func_get_args());
}


/**
 * Deeply merges arrays. Borrowed from drupal.org/project/core.
 *
 * @param array $arrays
 *   An array of array that will be merged.
 * @param bool $preserve_integer_keys
 *   Whether to preserve integer keys.
 */
function merge_deep_array(array $arrays, bool $preserve_integer_keys = FALSE): array {
  $result = [];
  foreach ($arrays as $array) {
    foreach ($array as $key => $value) {
      if (is_int($key) && !$preserve_integer_keys) {
        $result[] = $value;
      }
      elseif (isset($result[$key]) && is_array($result[$key]) && is_array($value)) {
        $result[$key] = merge_deep_array([$result[$key], $value], $preserve_integer_keys);
      }
      else {
        $result[$key] = $value;
      }
    }
  }
  return $result;
}
