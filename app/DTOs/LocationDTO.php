<?php

namespace App\DTOs;

class LocationDTO
{
    public function __construct(public int $x, public int $y)
    {
    }
}
