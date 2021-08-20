<?php

namespace App\Services;

use App\DTOs\LocationDTO;
use App\DTOs\LocationFaceDTO;
use JetBrains\PhpStorm\Pure;

class NormalizeRoverLocationFace
{
    #[Pure] public function __invoke(string $locationFace): LocationFaceDTO
    {
        $explodePath = explode(' ', $locationFace);
        $locationDTO = new LocationDTO($explodePath[0], $explodePath[1]);
        $facing = match (strtolower($explodePath[2])) {
            'e' => LocationFaceDTO::EAST,
            'w' => LocationFaceDTO::WEST,
            's' => LocationFaceDTO::SOUTH,
            'n' => LocationFaceDTO::NORTH
        };

        return new LocationFaceDTO($locationDTO, $facing);
    }
}
