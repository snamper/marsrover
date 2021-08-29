<?php

use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;

ini_set("display_errors", 1);
error_reporting(E_ALL);

define("ROOT_PATH", __DIR__);
define('BASE_PATH', ROOT_PATH . '/../..');
define('APP_PATH', BASE_PATH . '/app');


set_include_path(
    ROOT_PATH . "/../../app". ROOT_PATH . PATH_SEPARATOR . get_include_path()
);

// Required for phalcon/incubator
include __DIR__ . "/../vendor/autoload.php";

// Use the application autoloader to autoload the classes
// Autoload the dependencies found in composer
$loader = new Loader();

$loader->registerDirs(
    [
        ROOT_PATH,
    ]
);

$loader->register();

$di = new FactoryDefault();


Di::reset();

// Add any needed services to the DI here

include ROOT_PATH . '/../../app/config/services.php';

$config = $di->getConfig();

include ROOT_PATH . "/../../app/config/config.php";
include ROOT_PATH . "/../../app/config/loader.php";


Di::setDefault($di);
