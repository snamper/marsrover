<?php
namespace App\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Digit;

/**
 *
 * @OA\Schema(
 *     title="Plateaus",
 *     @OA\Xml(
 *         name="Plateaus"
 *     )
 * )
 */

class Plateaus extends \Phalcon\Mvc\Model
{

/**
     * @OA\Property(
     *     title="Id",
     *     description="Id",
     *     format="int64",
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *     title="Plateau name",
     *     description="Plateau name"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *     title="Plateau upper length",
     *     description="Plateau upper length"
     * ) 
     *
     * @var integer
     */
    public $upperLength;

    /**
     *  @OA\Property(
     *     title="Plateau right length",
     *     description="Plateau right length"
     * ) 
     *
     * @var integer
     */
    public $rightLength;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("marsrover_db");
        $this->setSource("plateaus");
        $this->hasMany('Id', 'Rovers', 'PlateauId', ['alias' => 'Rovers']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'plateaus';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Plateaus[]|Plateaus|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    public function validation()
    {
        $validator = new Validation();        
        $validator->add(
            [
                "Name",
                "UpperLength",
                "RightLength",
            ],
            new PresenceOf(
                [
                    "message" => [
                        "Name" => "Plato ismi boş geçilemez.",
                        "UpperLength" => "Plato yüksekliği boş geçilemez.",
                        "RightLength"  => "Plato genişliği boş geçilemez.",
                    ],
                ]
            )
        );

        $validator->add(
            [
                "UpperLength",
                "RightLength",
            ],
            new Digit(
                [
                    "message" => [
                        "UpperLength" => "Plato yüksekliği sayısal değer olmalıdır",
                        "RightLength"  => "Plato genişliği sayısal değer olmalıdır",
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

    public function setUpperLength($upper_length)
    {
        $this->UpperLength = $upper_length;

        return $this;
    }

    public function setRightLength($right_length)
    {
        $this->RightLength = $right_length;

        return $this;
    }
}
