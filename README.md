# Mars Rover

The work has been completed by using the `Phalcon 3.4`framework and `PHP 7.2.4`.

`MySQL` database has been used.

## Endpoints

* Create Plateau   `/v1/plateau/add`
* Get Plateau `/v1/plateau/{plateauId}`
* Create Rover `/v1/rover/add`
* Get Rover `/v1/rover/{roverId}`
* Send Command to Rover `/v1/rover/sendcommand/{roverId}`

## Unit Tests
Unit tests file are located at `/app/tests/Test/RoverCommandTest.php`.

## API Docs

Swagger configuration file is located at `/swagger.json`.