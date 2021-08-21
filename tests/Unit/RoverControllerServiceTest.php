<?php

namespace Tests\Unit;

use App\DTOs\LocationDTO;
use App\DTOs\LocationFaceDTO;
use App\DTOs\RoverDTO;
use App\Services\RoverControllerService;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

class RoverControllerServiceTest extends TestCase
{
    public function testTurnFaceLeft()
    {
        //ARRANGE
        $sut = $this->resolveAction();

        // ACT
        $sut->turnFaceLeft();

        //ASSERT
        $this->assertEquals($sut->roverDTO->locationFaceDTO->facing, LocationFaceDTO::NORTH);
    }

    public function testTurnFaceRight()
    {
        //ARRANGE
        $sut = $this->resolveAction();

        // ACT
        $sut->turnFaceRight();

        //ASSERT
        $this->assertEquals($sut->roverDTO->locationFaceDTO->facing, LocationFaceDTO::SOUTH);
    }

    public function testMove()
    {
        //ARRANGE
        $sut = $this->resolveAction();

        // ACT
        $sut->move();

        //ASSERT
        $this->assertEquals($sut->roverDTO->locationFaceDTO->locationDTO->x, 10);
        $this->assertEquals($sut->roverDTO->locationFaceDTO->locationDTO->y, 11);
    }

    private function resolveAction(): RoverControllerService
    {
        return new RoverControllerService($this->createRoverDTO());
    }

    #[Pure] private function createRoverDTO(): RoverDTO
    {
        $locationDTO = new LocationDTO(10, 10);
        $locationFaceDTO = new LocationFaceDTO($locationDTO, LocationFaceDTO::EAST);

        return new RoverDTO('RoverTest', $locationFaceDTO, 'mm');
    }
}
