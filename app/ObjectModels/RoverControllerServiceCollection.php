<?php

namespace App\ObjectModels;

use App\Services\RoverControllerService;
use Illuminate\Support\Collection;

class RoverControllerServiceCollection extends Collection
{
    public function __invoke(RoverControllerService $roverControllerService): self
    {
        $this->add($roverControllerService);

        return $this;
    }
}
