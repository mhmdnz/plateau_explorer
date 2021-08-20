<?php

namespace App\DTOs;

class LocationFaceDTO
{
    public const EAST = 'East';
    public const WEST = 'West';
    public const NORTH = 'North';
    public const SOUTH = 'South';

    public function __construct(public LocationDTO $locationDTO, public string $facing)
    {
    }
}
