<?php

namespace App\DTOs;

class RoverDTO
{
    const LEFT = 'l';
    const RIGHT = 'r';
    const MOVE = 'm';
    public function __construct(
        public string $name,
        public LocationFaceDTO $locationFaceDTO,
        public string $moves
    ) {
    }
}
