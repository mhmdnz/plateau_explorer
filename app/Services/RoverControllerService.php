<?php

namespace App\Services;

use App\DTOs\LocationDTO;
use App\DTOs\LocationFaceDTO;
use App\DTOs\RoverDTO;

class RoverControllerService
{
    private LocationDTO $currentLocation;
    public string $facing;

    public function __construct(public RoverDTO $roverDTO)
    {
        $this->currentLocation = $this->roverDTO->locationFaceDTO->locationDTO;
        $this->facing = $this->roverDTO->locationFaceDTO->facing;
    }

    public function getCurrentLocation(): LocationDTO
    {
        return $this->currentLocation;
    }

    public function turnFaceLeft(): void
    {
        $this->facing = match ($this->facing) {
            LocationFaceDTO::NORTH => LocationFaceDTO::WEST,
            LocationFaceDTO::WEST => LocationFaceDTO::SOUTH,
            LocationFaceDTO::SOUTH => LocationFaceDTO::EAST,
            LocationFaceDTO::EAST => LocationFaceDTO::NORTH,
        };
    }

    public function turnFaceRight(): void
    {
        $this->facing = match ($this->facing) {
            LocationFaceDTO::NORTH => LocationFaceDTO::EAST,
            LocationFaceDTO::EAST => LocationFaceDTO::SOUTH,
            LocationFaceDTO::SOUTH => LocationFaceDTO::WEST,
            LocationFaceDTO::WEST => LocationFaceDTO::NORTH,
        };
    }

    public function move(): void
    {
        match ($this->facing) {
            LocationFaceDTO::NORTH => $this->currentLocation->y++,
            LocationFaceDTO::EAST => $this->currentLocation->x++,
            LocationFaceDTO::SOUTH => $this->currentLocation->y--,
            LocationFaceDTO::WEST => $this->currentLocation->x--,
        };
    }
}
