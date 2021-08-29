<?php

/**
 * Registering an autoloader
 */
$loader = new \Phalcon\Loader();

$loader->registerNamespaces(
    [
      'App\Services'    => realpath(__DIR__ . '/../services/'),
      'App\Services\v1'    => realpath(__DIR__ . '/../services/v1'),

      'App\Controllers' => realpath(__DIR__ . '/../controllers/'),
      'App\Controllers\v1' => realpath(__DIR__ . '/../controllers/v1/'),
      
      'App\Models'      => realpath(__DIR__ . '/../models/'),
    ]
  );

$loader->registerDirs(
    [
        $config->application->modelsDir
    ]
)->register();
