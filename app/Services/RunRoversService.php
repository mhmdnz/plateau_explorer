<?php

namespace App\Services;

use App\DTOs\RoverDTO;
use App\ObjectModels\RoverControllerServiceCollection;
use Illuminate\Support\Collection;

class RunRoversService
{
    public function __invoke(RoverControllerServiceCollection $roverCollection): void
    {
        $roverCollection->each(function (RoverControllerService $roverControllerService) {
            $moves = collect(str_split($roverControllerService->roverDTO->moves));
            $this->moveToNewPosition($moves, $roverControllerService);
        });
    }

    private function moveToNewPosition(Collection $moves, RoverControllerService $roverControllerService): void
    {
        $moves->each(function (string $action) use ($roverControllerService) {
            match ($action) {
                RoverDTO::RIGHT => $roverControllerService->turnFaceRight(),
                RoverDTO::LEFT => $roverControllerService->turnFaceLeft(),
                RoverDTO::MOVE => $roverControllerService->move(),
            };
        });
    }
}
