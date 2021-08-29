<?php

namespace App\Controllers\v1;

use App\Controllers\HttpExceptions\Http400Exception;
use App\Controllers\HttpExceptions\Http422Exception;
use App\Controllers\HttpExceptions\Http500Exception;

use App\Services\AbstractService;
use App\Services\ServiceException;
use App\Services\v1\RoversService;


class RoversController extends \App\Controllers\AbstractController
{
    /**
     * 
     * @OA\Post(
     *     path="/v1/rover/add",
     *     tags={"rover"},
     *     operationId="addAction",
     *     description="Add new rover",
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
     *     requestBody={"$ref": "#/components/requestBodies/Rovers"}
     * )
     */    
    public function addAction() //add rover
    {
        try {
            if (!$this->request->hasPost('name') || 
                !$this->request->hasPost('plateauId') || 
                !$this->request->hasPost('facing') ||
                !$this->request->hasPost('xCoor') || 
                !$this->request->hasPost('yCoor'))
                throw new Http400Exception("Bad request", ERROR_INVALID_REQUEST);

                //facin giçin kontrol ekle
            $rover = $this->roversService_v1->createRover($this->request->getJsonRawBody());
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_ALREADY_EXISTS:
                case PlateausService::ERROR_UNABLE_CREATE_ROVER:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }
        return $rover;
    }

        /**
     * @OA\Put(
     *     path="/v1/rover/sendcommand/{roverId}",
     *     summary="Changes the location of the rover",
     *     description="Location is updated according to sent command",
     *     operationId="sendCommandAction",
     *     @OA\Parameter(
     *         name="roverId",
     *         in="path",
     *         description="Id of rover",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Rovers"),
     *         @OA\XmlContent(ref="#/components/schemas/Rovers"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\RequestBody(
     *         description="command which sent to rover",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Schema(
     *                  type="string"
     *              )         
     *          )
     *     )
     * )
     */
    public function sendCommandAction($roverId) //command gönder
    {
        try {
            if (!$this->request->hasPost('command'))
                throw new Http400Exception("Bad request", ERROR_INVALID_REQUEST);

//command sadece M,L ve R'den oluşmalı
            $command = $this->request->getPost('command');
            $pattern = "/([MLR])+/";
            if (!(preg_replace($pattern,"",$str)==="")) 
                throw new Http400Exception("Bad request", ERROR_INVALID_REQUEST);

            $rover = $this->roversService_v1->sendCommand($roverId, $this->request->getPost('command'));
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case RobotsService::ERROR_UNABLE_UPDATE_USER:
                case RobotsService::ERROR_ROVER_NOT_FOUND:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }
        return $plateau;
    }


    /**
     * @OA\Get(
     *     path="/v1/rover/{roverId}",
     *     tags={"rover"},
     *     summary="Find rover by id",
     *     description="returns one rover",
     *     operationId="getRoverAction",
     *     @OA\Parameter(
     *         name="roverId",
     *         in="path",
     *         description="Id of rover to return",
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
    public function getRoverAction($roverId) //rover bilgilerini al
    {
        try {
            $rover = $this->roversService_v1->getRover($roverId);
        } catch (ServiceException $e) {
            throw new Http400Exception(_('Internal Server Error'), $e->getCode(), $e);
        }

        return $rover;

    }

}
