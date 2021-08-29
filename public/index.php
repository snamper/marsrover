<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use App\Controllers\AbstractHttpException;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers the services that
     * provide a full stack framework. These default services can be overidden with custom ones.
     */
    $di = new FactoryDefault();

    /**
     * Include Services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Starting the application
     * Assign service locator to the application
     */
    $app = new Micro($di);

    /**
     * Include Application
     */
    include APP_PATH . '/app.php';

    /**
     * Making the correct answer after executing
     */
    $app->after(
        function () use ($app) {
          // geri dönüş değerini al
          $return = $app->getReturnedValue();
    
          if (is_array($return)) {
            // dönüştür array => json
            $app->response->setContent(json_encode($return));
          } elseif (!strlen($return)) {
            // içerik yok ise 204 üret
            $app->response->setStatusCode('204', 'No Content');
          } else {
            // response beklenmedik gelirse...
            throw new Exception('Bad Response');
          }
    
          // response client' a gönder
          $app->response->send();
        }
    );

    /**
     * Handle the request
     */
    $app->handle();

} catch (AbstractHttpException $e) { // hata AbstractHttpException olara üretilmiş ise gelen hata koduna göre client'a dönüş yap
	$response = $app->response;
	$response->setStatusCode($e->getCode(), $e->getMessage());
	$response->setJsonContent($e->getAppError());
	$response->send();
} catch (\Phalcon\Http\Request\Exception $e) { // hata, request exception ise 400 gönder
	$app->response->setStatusCode(400, 'Bad request')
	              ->setJsonContent([
		              AbstractHttpException::KEY_CODE    => 400,
		              AbstractHttpException::KEY_MESSAGE => 'Bad request'
	              ])
	              ->send();
} catch (\Exception $e) { // diğer hata durumlarında 500 üret
	$result = [
		AbstractHttpException::KEY_CODE    => 500,
		AbstractHttpException::KEY_MESSAGE => $e->getMessage()
	];

	$app->response->setStatusCode(500, 'Internal Server Error')
	              ->setJsonContent($result)
	              ->send();
}
