<?php

namespace App\Services;

use App\DTOs\LocationDTO;
use App\ObjectModels\RoverControllerServiceCollection;

class GetResultMessagesService
{
    private const NOT_VALID_LOCATION_MESSAGE = 'This rover is not in a valid location of plateau :(';

    public function __invoke(
        RoverControllerServiceCollection $roverControllerServiceCollection,
        LocationDTO $playGroundSize
    ): array {
        $messages = [];
        $roverControllerServiceCollection->each(function (RoverControllerService $roverControllerService)
        use ($playGroundSize, &$messages) {
            $name = $roverControllerService->roverDTO->name;
            $currentPosition = self::NOT_VALID_LOCATION_MESSAGE;
            $roverFace = self::NOT_VALID_LOCATION_MESSAGE;
            if ($this->validateFinalLocation(
                $playGroundSize,
                $roverControllerService->roverDTO->locationFaceDTO->locationDTO
            )) {
                $currentPosition = json_encode($roverControllerService->roverDTO->locationFaceDTO->locationDTO);
                $roverFace = $roverControllerService->roverDTO->locationFaceDTO->facing;
            }
            $messages[] = "
            Rover_name: $name,
            Rover_currentPosition: $currentPosition
            Rover_face_orientation: $roverFace
            ---------------------------------------
            ";
        });

        return $messages;
    }

    private function validateFinalLocation(LocationDTO $playGroundSize, LocationDTO $roverCurrentLocation):bool
    {
        return ($roverCurrentLocation->x >= 0 && $roverCurrentLocation->y >= 0) &&
            ($playGroundSize >= $roverCurrentLocation);
    }
}
