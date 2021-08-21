<?php

namespace App\Services;

use App\DTOs\LocationDTO;
use App\Collections\RoverControllerServiceCollection;

class GetResultMessagesService
{
    const NOT_VALID_LOCATION_MESSAGE = 'This rover is not in a valid location of plateau :(';
    const ROVER_NAME = 'Rover_name:';
    const ROVER_CURRENT_POSITION = 'Rover_currentPosition:';
    const ROVER_FACE_ORIENTATION = 'Rover_face_orientation:';

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
            ".self::ROVER_NAME." $name,
            ".self::ROVER_CURRENT_POSITION." $currentPosition
            ".self::ROVER_FACE_ORIENTATION." $roverFace
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
