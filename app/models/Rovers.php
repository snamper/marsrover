<?php
namespace App\Models;

/**
 *
 * @OA\Schema(
 *     title="Rovers",
 *     @OA\Xml(
 *         name="Rovers"
 *     )
 * )
 */

class Rovers extends \Phalcon\Mvc\Model
{

    /**
     * * @OA\Property(
     *     title="Id",
     *     description="Id",
     *     format="int64",
     * )
     *
     * @var integer
     */
    public $id;

    /**
     *  @OA\Property(
     *     title="Rover name",
     *     description="Rover name"
     * )
     *
     * @var string
     */
    public $name;

    /**
     *  @OA\Property(
     *     title="Rover plateau id",
     *     description="Rover plateau id"
     * )
     *
     * @var integer
     */
    public $plateauId;

    /**
     *  @OA\Property(
     *     title="Rover x coordinate",
     *     description="Rover x coordinate"
     * )
     *
     * @var integer
     */
    public $xCoor;

    /**
     *  @OA\Property(
     *     title="Rover y coordinate",
     *     description="Rover y coordinate"
     * )
     *
     * @var integer
     */
    public $yCoor;

    /**
     *  @OA\Property(
     *     title="Rover facing",
     *     description="Rover facing"
     * )
     *
     * @var integer
     */
    public $facing;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("marsrover_db");
        $this->setSource("rovers");
        $this->hasMany('Id', 'Sendedcommands', 'RoverId', ['alias' => 'Sendedcommands']);
        $this->belongsTo('PlateauId', 'Plateaus', 'Id', ['alias' => 'Plateaus']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'rovers';
    }
    
    public function validation()
    {
        $validator = new Validation();        
        $validator->add(
            [
                "Name",
                "PlateauId",
                "XCoor",
                "YCoor",
                "Facing",
            ],
            new PresenceOf(
                [
                    "message" => [
                        "Name" => "Robot ismi boş geçilemez.",
                        "PlateauId" => "Plato boş geçilemez.",
                        "XCoor" => "X koordinatı boş geçilemez.",
                        "YCoor"  => "Y koordinatı boş geçilemez.",
                        "Facing"  => "Robot yönü boş geçilemez.",
                    ],
                ]
            )
        );

        $validator->add(
            [
                "XCoor",
                "YCoor",
            ],
            new Digit(
                [
                    "message" => [
                        "XCoor" => "X koordinatı sayısal değer olmalıdır",
                        "YCoor"  => "Y koordinatı sayısal değer olmalıdır",
                    ],
                ]
            )
        );

        $validator->add(
            "type",
            new InclusionIn(
                [
                    'message' => 'Robot yönü "N", "S", "W" ya da "E" olmalıdır.',
                    'domain' => [
                        'N',
                        'S',
                        'W',
                        'E'
                    ],
                ]
            )
        );


        if ($this->validationHasFailed() === true) {
            return false;
        }
    }


    public function setName($name)
    {
        $this->Name = $name;

        return $this;
    }

    public function setPlateauId($plateau_id)
    {
        $this->PlateauId = $plateau_id;

        return $this;
    }

    public function setFacing($facing)
    {
        $this->Facing = $facing;

        return $this;
    }

    public function setXCoor($xcoor)
    {
        $this->XCoor = $xcoor;

        return $this;
    }

    public function setYCoor($ycoor)
    {
        $this->YCoor = $ycoor;

        return $this;
    }
}
