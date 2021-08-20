<?php

namespace App\Services;

use App\DTOs\LocationDTO;
use JetBrains\PhpStorm\Pure;

class NormalizeLocationService
{
    #[Pure] public function __invoke(string $locationWithSpace): LocationDTO
    {
        $explodePath = explode(' ', $locationWithSpace);

        return new LocationDTO($explodePath[0], $explodePath[1]);
    }
}
