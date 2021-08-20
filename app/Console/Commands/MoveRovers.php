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

    private const FINAL_RESULT_MESSAGE = '======  WWwooooww, That`s it, I hope you enjoy the results  =====';
    private const GET_PLAYGROUND_SIZE_MESSAGE = 'Lets start with the size of our plateau, could you please enter the max X Y? => example : 6 6';
    private const ASK_FOR_ANOTHER_ROVER_MESSAGE = 'Do you have another rover?';
    private const GET_ROVER_LOCATION_FACE_MESSAGE = "Ok I named one of your rovers ':roverName' now let me know what is the location of :roverName ? example 1 3 n/e/s/w";
    private const GET_ROVER_MOVES_MESSAGE = 'Ok I`ve got the location of :roverName, So what are the moves for that ? example MMRMMRMRRM';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Faker $faker, RunRoversService $runRoversService)
    {
        $playGroundSize = $this->getPlaygroundSize();
        $roverControllerServiceCollection = $this->createRovers($faker, $playGroundSize);
        $runRoversService($roverControllerServiceCollection);
        $this->showResult($roverControllerServiceCollection, $playGroundSize);
    }

    private function showResult(
        RoverControllerServiceCollection $roverControllerServiceCollection,
        LocationDTO $normalizedPlayGroundSize
    ): void {
        $this->info(self::FINAL_RESULT_MESSAGE);
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
                ->ask(self::GET_PLAYGROUND_SIZE_MESSAGE);
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
        } while ($this->confirm(self::ASK_FOR_ANOTHER_ROVER_MESSAGE));

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
                ->ask(str_replace(':roverName', $roverName, self::GET_ROVER_MOVES_MESSAGE));
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
                ->ask(str_replace(':roverName', $roverName, self::GET_ROVER_LOCATION_FACE_MESSAGE));
            $validator = Validator::make([
                'rover_location' => $location
            ], [
                'rover_location' => ['required', "regex:/\b([0-9]*)[[:space:]]([0-9]*)[[:space:]]\b(?i)(n|e|w|s)\b/"]
            ]);
        } while (
            $validator->errors()->getMessages() != [] ||
            $playGroundSize < (
                $result = resolve(NormalizeRoverLocationFace::class)($location)
            )->locationDTO
        );

        return $result;
    }
}
