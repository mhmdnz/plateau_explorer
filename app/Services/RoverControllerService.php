<?php

namespace App\Services;

use App\DTOs\LocationFaceDTO;
use App\DTOs\RoverDTO;

class RoverControllerService
{
    public function __construct(public RoverDTO $roverDTO)
    {
    }

    public function turnFaceLeft(): void
    {
        $this->roverDTO->locationFaceDTO->facing = match ($this->roverDTO->locationFaceDTO->facing) {
            LocationFaceDTO::NORTH => LocationFaceDTO::WEST,
            LocationFaceDTO::WEST => LocationFaceDTO::SOUTH,
            LocationFaceDTO::SOUTH => LocationFaceDTO::EAST,
            LocationFaceDTO::EAST => LocationFaceDTO::NORTH,
        };
    }

    public function turnFaceRight(): void
    {
        $this->roverDTO->locationFaceDTO->facing = match ($this->roverDTO->locationFaceDTO->facing) {
            LocationFaceDTO::NORTH => LocationFaceDTO::EAST,
            LocationFaceDTO::EAST => LocationFaceDTO::SOUTH,
            LocationFaceDTO::SOUTH => LocationFaceDTO::WEST,
            LocationFaceDTO::WEST => LocationFaceDTO::NORTH,
        };
    }

    public function move(): void
    {
        match ($this->roverDTO->locationFaceDTO->facing) {
            LocationFaceDTO::NORTH => $this->roverDTO->locationFaceDTO->locationDTO->y++,
            LocationFaceDTO::EAST => $this->roverDTO->locationFaceDTO->locationDTO->x++,
            LocationFaceDTO::SOUTH => $this->roverDTO->locationFaceDTO->locationDTO->y--,
            LocationFaceDTO::WEST => $this->roverDTO->locationFaceDTO->locationDTO->x--,
        };
    }
}
