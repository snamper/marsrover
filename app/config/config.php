<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'    => 'Mysql',
        'host'       => 'localhost',
        'username'   => 'root',
        'password'   => '',
        'dbname'     => 'marsrover_db',
        'charset'    => 'utf8',
    ],

    'application' => [
        'modelsDir'      => APP_PATH . '/models/',
        'controllersDir'  => APP_PATH . '/controllers/',
        'baseUri'        => '/',
    ],
    'swagger' => [
        'path' => APP_PATH . '/controllers',
        'host' => 'localhost',
        'schemes' => ['http', 'https'],
        'basePath' => '/',
        'version' => '1.0.0',
        'title' => "MarsRover API",
        'description' => 'MarsRover API',
        'email' => 'yaseminhocaoglu@gmail.com',
        'jsonUri' => '/swagger.json'
    ],
]);
