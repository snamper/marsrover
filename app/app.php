<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */
 
/**
 * @license Apache 2.0
 */

 
/**
 * @OA\Info(
 *     description="Hepsiburada case study",
 *     version="1.0.0",
 *     title="Swagger MarsRovers",
 *     @OA\Contact(
 *         email="yaseminhocaoglu@gmail.com"
 *     )
 * )
 */

//plateau collection v1
$plateauCollection_v1 = new \Phalcon\Mvc\Micro\Collection();
$plateauCollection_v1->setHandler('\App\Controllers\v1\PlateausController', true);
$plateauCollection_v1->setPrefix('/v1/plateau');

$plateauCollection_v1->post('/add', 'addAction');
$plateauCollection_v1->get('/{plateauId:[1-9][0-9]*}', 'getPlateauAction');
$app->mount($plateauCollection_v1);


//rover collection v1
$roverCollection_v1 = new \Phalcon\Mvc\Micro\Collection();
$roverCollection_v1->setHandler('\App\Controllers\v1\RoversController', true);
$roverCollection_v1->setPrefix('/v1/rover');

$roverCollection_v1->post('/add', 'addAction');
$roverCollection_v1->put('/sendcommand/{roverId:[1-9][0-9]*}', 'sendCommandAction');
$roverCollection_v1->get('/{roverId:[1-9][0-9]*}', 'getRoverAction');
$app->mount($roverCollection_v1);


// URL bulunamadı ise 404 üret
$app->notFound(
  function () use ($app) {
      $exception =
        new \App\Controllers\HttpExceptions\Http404Exception(
            'URI not found: ' . $app->request->getMethod() . ' ' . $app->request->getURI(),
          \App\Controllers\AbstractController::ERROR_NOT_FOUND,
          new \Exception('URI not found: ' . $app->request->getMethod() . ' ' . $app->request->getURI())
        );
      throw $exception;
  }
);