<?php
/**
 * v1 for UserService
 */
namespace App\Services\v1;

use App\Models\Plateaus;

/**
 * Business-logic for users
 *
 * Class UsersService
 */
class PlateausService extends \App\Services\AbstractService
{
	/** Unable to create user */
	const ERROR_UNABLE_CREATE_PLATEAU = 11001;

	/** User not found */
	const ERROR_PLATEAU_NOT_FOUND = 11002;

	/** No such user */
	const ERROR_INCORRECT_PLATEAU = 11003;

	/**
	 * Creating a new user
	 *
	 * @param array $userData
	 */
	public function createPlateau(object $PlateauData)
	{
		try {
			$plateau   = new Plateaus();
			$result = $plateau->setName($PlateauData->name)
			               ->setUpperLength($PlateauData->upperLength)
			               ->setRightLength($PlateauData->rightLength)
			               ->create();
			if (!$result) { //validation models/Plateaus  yapılır...
				throw new \App\Services\ServiceException('Unable to create plateau', self::ERROR_UNABLE_CREATE_PLATEAU);
			}

		} catch (\PDOException $e) {
			if ($e->getCode() == 23505) {
				throw new \App\Services\ServiceException('Plateau already exists', self::ERROR_ALREADY_EXISTS, $e);
			} else {
				throw new \App\Services\ServiceException($e->getMessage(), $e->getCode(), $e);
			}
		}
		return $plateau->toArray();
	}

	public function getPlateau($plateauId)
	{
		try {
			$plateau = Plateaus::findFirst(
				[
					'conditions' => 'Id = :id:',
					'bind'       => [
						'id' => $plateauId
					]
				]
			);

			if (!$plateau) {				
				return [];
			}

			return $plateau->toArray();

		} catch (\PDOException $e) {
			throw new \App\Services\ServiceException($e->getMessage(), $e->getCode(), $e);
		}
	}
}
