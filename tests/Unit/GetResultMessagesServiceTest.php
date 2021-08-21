<?php

namespace Tests\Unit;

use App\Collections\RoverControllerServiceCollection;
use App\DTOs\LocationDTO;
use App\DTOs\LocationFaceDTO;
use App\DTOs\RoverDTO;
use App\Services\GetResultMessagesService;
use App\Services\RoverControllerService;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

class GetResultMessagesServiceTest extends TestCase
{
    public function testGetValidatedMessages()
    {
        // ARRANGE
        $mockedRoverControllerServiceCollection = $this->mockRoverControllerServiceCollection();
        $locationDTO = new LocationDTO(15, 15);
        $sut = $this->resolveAction();

        // ACT
        $results = $sut($mockedRoverControllerServiceCollection, $locationDTO);

        //ASSERT
        $this->assertCount(1, $results);
        $this->assertStringContainsString(GetResultMessagesService::ROVER_NAME, $results[0]);
        $this->assertStringContainsString(GetResultMessagesService::ROVER_FACE_ORIENTATION, $results[0]);
        $this->assertStringContainsString(GetResultMessagesService::ROVER_CURRENT_POSITION, $results[0]);
        $this->assertStringNotContainsString(GetResultMessagesService::NOT_VALID_LOCATION_MESSAGE, $results[0]);
    }

    public function testGetNotValidatedMessages()
    {
        // ARRANGE
        $mockedRoverControllerServiceCollection = $this->mockRoverControllerServiceCollection();
        $locationDTO = new LocationDTO(9, 9);
        $sut = $this->resolveAction();

        // ACT
        $results = $sut($mockedRoverControllerServiceCollection, $locationDTO);

        //ASSERT
        $this->assertCount(1, $results);
        $this->assertStringContainsString(GetResultMessagesService::NOT_VALID_LOCATION_MESSAGE, $results[0]);
    }

    private function resolveAction(): GetResultMessagesService
    {
        return resolve(GetResultMessagesService::class);
    }

    #[Pure] private function mockRoverControllerService(): RoverControllerService
    {
        $locationDTO = new LocationDTO(10, 10);
        $locationFaceDTO = new LocationFaceDTO($locationDTO, LocationFaceDTO::EAST);
        $roverDTO = new RoverDTO('RoverTest', $locationFaceDTO, 'mm');

        return new RoverControllerService($roverDTO);
    }

    private function mockRoverControllerServiceCollection(): RoverControllerServiceCollection
    {
        $roverControllerServiceCollection = new RoverControllerServiceCollection();
        $roverControllerServiceCollection->add($this->mockRoverControllerService());

        return $roverControllerServiceCollection;
    }
}
