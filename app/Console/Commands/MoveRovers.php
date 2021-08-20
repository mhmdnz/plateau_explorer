<?php

namespace App\Console\Commands;

use App\DTOs\LocationDTO;
use App\DTOs\LocationFaceDTO;
use App\DTOs\RoverDTO;
use App\ObjectModels\RoverControllerServiceCollection;
use App\Services\RoverControllerService;
use App\Services\RunRoversService;
use Faker\Generator as Faker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\Pure;

class MoveRovers extends Command
{

    protected $signature = 'rovers:explore';

    protected $description = 'With this command you will be able to explore a plateau on Mars';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Faker $faker)
    {
        $playGroundSizeString = $this->getPlaygroundSize();
        $normalizedPlayGroundSize = $this->normalizeLocationPath($playGroundSizeString);
        $roverControllerServiceCollection = $this->createRovers($faker, $normalizedPlayGroundSize);
        $runRoversService = new RunRoversService($roverControllerServiceCollection);
        $runRoversService->moveAll();
        $this->showResult($roverControllerServiceCollection, $normalizedPlayGroundSize);
    }

    private function showResult(
        RoverControllerServiceCollection $roverControllerServiceCollection,
        LocationDTO $normalizedPlayGroundSize
    ): void {
        $this->info('======  WWwooooww, That`s it, I hope you enjoy the results  =====');
        $this->showResultInSeparateLines($roverControllerServiceCollection, $normalizedPlayGroundSize);
    }

    private function showResultInSeparateLines(
        RoverControllerServiceCollection $roverControllerServiceCollection,
        LocationDTO $playGroundSize
    ) {
        $roverControllerServiceCollection->each(function (RoverControllerService $roverControllerService) use ($playGroundSize) {
            $name = $roverControllerService->roverDTO->name;
            $currentPosition = $this->validateFinalLocation(
                $playGroundSize,
                $roverControllerService->getCurrentLocation()
            ) ? json_encode($roverControllerService->getCurrentLocation()) : 'This rover is not in a valid location of plateau :(';
            $roverFace = $roverControllerService->facing;
            $this->info("
            Rover_name: $name,
            Rover_currentPosition: $currentPosition
            Rover_face_orientation: $roverFace
            ---------------------------------------
            ");
        });
    }

    private function validateFinalLocation(LocationDTO $playGroundSize, LocationDTO $roverCurrentLocation):bool
    {
        return ($roverCurrentLocation->x >= 0 && $roverCurrentLocation->y >= 0) &&
            ($playGroundSize >= $roverCurrentLocation);
    }

    private function getPlaygroundSize(): string
    {
        do {
            $playGroundSize = $this
                ->ask('Lets start with the size of our plateau, could you please enter the max X Y? => example : 6 6');
            $validator = Validator::make([
                'play_ground_size' => $playGroundSize
            ], [
                'play_ground_size' => ['required', 'regex:/^[0-9]*(?<=\d) (\d)*$/']
            ]);
        } while ($validator->errors()->getMessages() != []);

        return $playGroundSize;
    }

    #[Pure] private function normalizeLocationPath(string $pathWithSpace): LocationDTO
    {
        $explodePath = explode(' ', $pathWithSpace);

        return new LocationDTO($explodePath[0], $explodePath[1]);
    }

    private function createRovers(Faker $faker, LocationDTO $playGroundSize): RoverControllerServiceCollection
    {
        $roverCollection = new RoverControllerServiceCollection();
        do {
            $roverName = $faker->name;
            $roverDTO = $this->createRoverBaseOnPlayGroundSize($roverName, $playGroundSize);
            $roverCollection(new RoverControllerService($roverDTO));
        } while ($this->confirm('Do you have another rover?'));

        return $roverCollection;
    }

    private function createRoverBaseOnPlayGroundSize(string $roverName, LocationDTO $playGroundSize): RoverDTO
    {
        $locationFace = $this->getRoverLocationFace($roverName, $playGroundSize);
        $moves = $this->getRoverMoves($roverName);

        return new RoverDTO($roverName, $locationFace, $moves);
    }

    private function getRoverMoves(string $roverName): string
    {
        do {
            $moves = $this
                ->ask("Ok I've got the location of $roverName, So what are the moves for that ? example MMRMMRMRRM");
            $validator = Validator::make([
                'rover_location' => $moves
            ], [
                'rover_location' => ['required', "regex:/^[lLrRmM]*$/"]
            ]);
        } while ($validator->errors()->getMessages() != []);

        return strtolower($moves);
    }

    private function getRoverLocationFace(string $roverName, LocationDTO $playGroundSize): LocationFaceDTO
    {
        do {
            $location = $this
                ->ask("Ok I named one of your rovers '$roverName' now let me know what is the location of $roverName ? example 1 3 n/e/s/w");
            $validator = Validator::make([
                'rover_location' => $location
            ], [
                'rover_location' => ['required', "regex:/\b([0-$playGroundSize->x])[[:space:]]([0-$playGroundSize->y])[[:space:]]\b(?i)(n|e|w|s)\b/"]
            ]);
        } while ($validator->errors()->getMessages() != []);

        return $this->normalizeRoverLocationFace($location);
    }

    #[Pure] private function normalizeRoverLocationFace(string $locationFace): LocationFaceDTO
    {
        $explodePath = explode(' ', $locationFace);
        $locationDTO = new LocationDTO($explodePath[0], $explodePath[1]);
        $facing = match (strtolower($explodePath[2])) {
            'e' => LocationFaceDTO::EAST,
            'w' => LocationFaceDTO::WEST,
            's' => LocationFaceDTO::SOUTH,
            'n' => LocationFaceDTO::NORTH
        };

        return new LocationFaceDTO($locationDTO, $facing);
    }
}
