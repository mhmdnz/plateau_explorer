<?php

namespace Tests\Unit;

use App\Collections\RoverControllerServiceCollection;
use App\DTOs\LocationDTO;
use App\DTOs\LocationFaceDTO;
use App\DTOs\RoverDTO;
use App\Services\RoverControllerService;
use App\Services\RunRoversService;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

class RunRoversServiceTest extends TestCase
{
    public function testRunAllRovers()
    {
        //ARRANGE
        $sut = $this->resolveAction();
        $createdRoverControllerServiceCollection = $this->createRoverControllerServiceCollection();
        $firstRoverControllerService = $createdRoverControllerServiceCollection->first();
        $lastRoverControllerService = $createdRoverControllerServiceCollection->last();

        // ACT
        $sut($createdRoverControllerServiceCollection);

        //ASSERT
        /**
         * @var RoverControllerService $firstRoverControllerService
         */
        $this->assertEquals($firstRoverControllerService->roverDTO->locationFaceDTO->locationDTO->x, 12);
        $this->assertEquals($firstRoverControllerService->roverDTO->locationFaceDTO->locationDTO->y, 8);
        $this->assertEquals($lastRoverControllerService->roverDTO->locationFaceDTO->locationDTO->x, 12);
        $this->assertEquals($lastRoverControllerService->roverDTO->locationFaceDTO->locationDTO->y, 8);
    }

    private function resolveAction(): RunRoversService
    {
        return resolve(RunRoversService::class);
    }

    private function createRoverControllerServiceCollection(): RoverControllerServiceCollection
    {
        $roverControllerServiceCollection = new RoverControllerServiceCollection();
        $roverControllerServiceCollection->add(
            new RoverControllerService($this->createRoverDTO('firstRover'))
        );
        $roverControllerServiceCollection->add(
            new RoverControllerService($this->createRoverDTO('secondRover'))
        );
        $roverControllerServiceCollection->add(
            new RoverControllerService($this->createRoverDTO('thirdRover'))
        );

        return $roverControllerServiceCollection;
    }

    #[Pure] private function createRoverDTO(string $roverName): RoverDTO
    {
        $locationDTO = new LocationDTO(10, 10);
        $locationFaceDTO = new LocationFaceDTO($locationDTO, LocationFaceDTO::EAST);

        return new RoverDTO($roverName, $locationFaceDTO, 'mmrmm');
    }
}
