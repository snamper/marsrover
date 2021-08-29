<?php

use Phalcon\Mvc\View\Simple as View;
use Phalcon\Mvc\Url as UrlResolver;

/**
 * Overriding Response-object to set the Content-type header globally
 */

$di->setShared(
    'response',
    function () {
        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'utf-8');
  
        return $response;
    }
  );
  
/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * Sets the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});

/**
 * Register router
 */
$di->setShared('router', function () {
    $router = new \Phalcon\Mvc\Router();
    $router->setUriSource(
        \Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI
    );

    return $router;
});

$di->setShared('swagger', function () {
        $config = $this->getConfig();
        return $config->get('swagger')->toArray();
    }
);

$di->setShared('usersService_v1', '\App\Services\v1\UsersService');
$di->setShared('plateausService_v1', '\App\Services\v1\PlateausService');
$di->setShared('roversService_v1', '\App\Services\v1\RoversService');
