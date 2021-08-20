<?php

namespace App\Console\Commands;

use App\DTOs\LocationDTO;
use App\DTOs\LocationFaceDTO;
use App\DTOs\RoverDTO;
use App\ObjectModels\RoverControllerServiceCollection;
use App\Services\GetResultMessage;
use App\Services\NormalizeLocationService;
use App\Services\NormalizeRoverLocationFace;
use App\Services\RoverControllerService;
use App\Services\RunRoversService;
use Faker\Generator as Faker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class MoveRovers extends Command
{

    protected $signature = 'rovers:explore';

    protected $description = 'With this command you will be able to explore a plateau on Mars';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Faker $faker, NormalizeLocationService $normalizeLocationService)
    {
        $playGroundSize = $this->getPlaygroundSize();
        $roverControllerServiceCollection = $this->createRovers($faker, $playGroundSize);
        $runRoversService = new RunRoversService($roverControllerServiceCollection);
        $runRoversService->moveAll();
        $this->showResult($roverControllerServiceCollection, $playGroundSize);
    }

    private function showResult(
        RoverControllerServiceCollection $roverControllerServiceCollection,
        LocationDTO $normalizedPlayGroundSize
    ): void {
        $this->info('======  WWwooooww, That`s it, I hope you enjoy the results  =====');
        $results = resolve(GetResultMessage::class)
        ($roverControllerServiceCollection, $normalizedPlayGroundSize);
        foreach ($results as $result){
            $this->info($result);
        }
    }

    private function getPlaygroundSize(): LocationDTO
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


        return resolve(NormalizeLocationService::class)($playGroundSize);
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

        return resolve(NormalizeRoverLocationFace::class)($location);
    }
}
