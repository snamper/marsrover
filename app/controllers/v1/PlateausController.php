<?php

namespace App\Controllers\v1;

use App\Controllers\HttpExceptions\Http400Exception;
use App\Controllers\HttpExceptions\Http422Exception;
use App\Controllers\HttpExceptions\Http500Exception;

use App\Services\AbstractService;
use App\Services\ServiceException;
use App\Services\v1\PlateausService;

class PlateausController extends \App\Controllers\AbstractController
{
    /**
     * 
     *   @OA\Post(    
     *     path="/v1/plateau/add",
     *     tags={"plateau"},
     *     description="Add new plateau",
     *     operationId="addAction",
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     *     requestBody={"$ref": "#/components/requestBodies/Plateaus"}
     * )
     */
    public function addAction() //plateau ekle
    {
        try {
            if (!$this->request->hasPost('name') || 
                !$this->request->hasPost('upperLength') || 
                !$this->request->hasPost('rightLength'))
                throw new Http400Exception("Bad request", ERROR_INVALID_REQUEST);
            $plateau = $this->plateausService_v1->createPlateau($this->request->getJsonRawBody());
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_ALREADY_EXISTS:
                case PlateausService::ERROR_UNABLE_CREATE_PLATEAU:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }
        return $plateau;
    }

    /**
     * @OA\Get(
     *     path="/v1/plateau/{plateauId}",
     *     tags={"plateau"},
     *     summary="Find plateau by id",
     *     description="returns one plateu",
     *     operationId="getPlateauAction",
     *     @OA\Parameter(
     *         name="plateauId",
     *         in="path",
     *         description="Id of plateau to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Plateaus"),
     *         @OA\XmlContent(ref="#/components/schemas/Plateaus"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function getPlateauAction($plateauId) //plateau bilgilerini al
    {
        try {
            $plateau = $this->plateausService_v1->getPlateau($plateauId);
        } catch (ServiceException $e) {
            throw new Http400Exception(_('Internal Server Error'), $e->getCode(), $e);
        }

        return $plateau;

    }

    public function denemeAction()
    {
        return 1;
    }
}
