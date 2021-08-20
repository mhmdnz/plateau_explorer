<?php

namespace App\DTOs;

class RoverDTO
{
    public function __construct(public string $location, public string $moves)
    {
    }
}
