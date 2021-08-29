<?php

namespace Test;
use App\Services\v1\RoversService;

/**
 * Class UnitTest
 */
class RoverCommandTest extends \UnitTestCase
{
    public function testRoverLocation()
    {
        $roverservice = new RoversService();
        $newPositionArray = $roverservice->ChangePosition(0, 0, 'N', 'MMRMMM', 5, 5);
        $this->assertIsArray($newPositionArray);

        $this->assertEquals(2, $newPositionArray['roverx'], 'Rover X:'.$newPositionArray['roverx']);
        $this->assertEquals(2, $newPositionArray['rovery'], 'Rover Y:'.$newPositionArray['rovery']);
        $this->assertEquals('E',$newPositionArray['roverface'], 'facing:'.$newPositionArray['roverface']);

    }
}