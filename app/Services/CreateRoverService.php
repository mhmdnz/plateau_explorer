<?php

namespace App\Services;

use App\DTOs\LocationDTO;
use App\DTOs\LocationFaceDTO;
use App\DTOs\RoverDTO;

class CreateRoverService
{
    public function __invoke(string $roverName, LocationFaceDTO $locationFace, string $moves)
    {
        return new RoverDTO($roverName, $locationFace, $moves);
    }
}
