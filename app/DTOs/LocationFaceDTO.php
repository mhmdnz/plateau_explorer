<?php

namespace App\DTOs;

class LocationFaceDTO
{
    public const EAST = 'e';
    public const WEST = 'w';
    public const NORTH = 'n';
    public const SOUTH = 's';

    public function __construct(public LocationDTO $locationDTO, public string $facing)
    {
    }
}
