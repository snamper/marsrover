<?php
/**
 * v1 for UserService
 */
namespace App\Services\v1;

use App\Models\Rovers;

/**
 * Business-logic for rovers
 *
 * Class RoversService
 */
class RoversService extends \App\Services\AbstractService
{
	const ERROR_UNABLE_CREATE_ROVER = 11001;
	const ERROR_ROVER_NOT_FOUND = 11002;
	const ERROR_INCORRECT_ROVER = 11003;
	const ERROR_WRONG_ROVER_COMMAND = 11004;
	const ERROR_UNABLE_UPDATE_USER = 11005;
	/**
	 * Creating a new user
	 *
	 * @param array $userData
	 */
	public function createRover(object $RoverData)
	{
		try {
			$rover   = new Rovers();
			$result = $rover->setName($RoverData->name)
							->setPlateauId($RoverData->plateauId)
							->setFacing($RoverData->facing)
			               ->setXCoor($RoverData->xCoor)
			               ->setYCoor($RoverData->yCoor)
			               ->create();
			if (!$result) { //validation models/Roversda  yapılır...
				throw new \App\Services\ServiceException('Unable to create rover', self::ERROR_UNABLE_CREATE_ROVER);
			}

		} catch (\PDOException $e) {
			if ($e->getCode() == 23505) {
				throw new \App\Services\ServiceException('Rover already exists', self::ERROR_ALREADY_EXISTS, $e);
			} else {
				throw new \App\Services\ServiceException($e->getMessage(), $e->getCode(), $e);
			}
		}
		return $rover->toArray();
	}

	public function getRover($roverId)
	{
		try {
			$rover = Rovers::findFirst(
				[
					'conditions' => 'Id = :id:',
					'bind'       => [
						'id' => $roverId
					]
				]
			);

			if (!$rover) {				
				return [];
			}

			return $rover->toArray();

		} catch (\PDOException $e) {
			throw new \App\Services\ServiceException($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function aaa()
    {
        return 1;
    }

	public function ChangePosition($roverx, $rovery, $roverface, $command, $plateurightlength, $plateauupperlength) {
		//yön değiştirme rehberi, yönlerin sağ ve sol dönüş değerleri
		$directions = array('N' => array('L' => 'W', 'R' => 'E'), 
		'E' => array('L' => 'N', 'R' => 'S'), 
		'S' => array('L' => 'E', 'R' => 'W'), 
		'W' => array('L' => 'S', 'R' => 'N')); 

		$commands = str_split($command);
		foreach ($commands as $value) { //L , R , M
			//throw new \App\Services\ServiceException('rover x:'.$roverx . ' rover y:'.$rovery . 'facing:'.$roverface, self::ERROR_WRONG_ROVER_COMMAND);

			if ($value === 'M') { // kendi yönünde 1 blok ilerler
				switch ($roverface) {
					case 'N': //kuzey ise
						$rovery++;
						break;
					case 'S': //güney ise
						$rovery--;
						break;
					case 'E': //doğu ise
						$roverx++;
						break;
					case 'W': //batı ise
						$roverx--;
						break;
				}
			}
			else //L ya da R geldi ise yön değiştir
				$roverface = $directions[$roverface][$value];	
			//hareket edilen konum plato alanı içinde mi kontrolü
			if ($roverx<0 || 
				$rovery<0 || 
				$roverx > $plateurightlength || 
				$rovery > $plateauupperlength)
					throw new \App\Services\ServiceException('Plato alanı dışında komut gönderimi', self::ERROR_WRONG_ROVER_COMMAND);

			
		}
		return array('roverface' => $roverface,'roverx' => $roverx,'rovery' => $rovery);
	}


	public function sendCommand($roverId, $command)
	{
		try {
			$rover = Rovers::findFirst(
				[
					'conditions' => 'Id = :id:',
					'bind'       => [
						'id' => $roverId
					]
				]
			);
//robot var mı kontrol et
			if (!$rover) {				
				throw new \App\Services\ServiceException('Robot bulunamadı', self::ERROR_ROVER_NOT_FOUND);
			}
//robotun şu anki konumunu al
			$roverlocation = $this->ChangePosition($rover->XCoor, $rover->YCoor, $rover->Facing, $command, $rover->Plateaus->RightLength, $rover->Plateaus->UpperLength); 

			$result = $rover->setFacing($roverlocation['roverface'])
					->setXCoor($roverlocation['roverx'])
					->setYCoor($roverlocation['rovery'])
					->save();	
			if (!$result)
				throw new \App\Services\ServiceException('Plato konumu değiştirilemedi.', self::ERROR_INCORRECT_ROVER);

			return $rover->toArray();

		} catch (\PDOException $e) {
			throw new \App\Services\ServiceException($e->getMessage(), $e->getCode(), $e);
		}
	}

}
