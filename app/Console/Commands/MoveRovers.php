<?php

namespace App\Console\Commands;

use App\DTOs\RoverDTO;
use Faker\Generator as Faker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\ArrayShape;

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
        $createdRovers = $this->createRovers($faker, $normalizedPlayGroundSize);
        die(json_encode($createdRovers));
        return 0;
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

    #[ArrayShape(['x' => "string", 'y' => "string"])] private function normalizeLocationPath(string $pathWithSpace): array
    {
        $explodePath = explode(' ', $pathWithSpace);

        return [
            'x' => $explodePath[0],
            'y' => $explodePath[1]
        ];
    }

    private function createRovers(Faker $faker, array $normalizedPlayGroundSize): array
    {
        do {
            $roverName = $faker->name;
            $roversLocation[$roverName] = $this->createRover($roverName, $normalizedPlayGroundSize);
        } while ($this->confirm('Do you have another rover?'));

        return $roversLocation;
    }

    private function createRover(string $roverName, array $normalizedPlayGroundSize): RoverDTO
    {
        $location = $this->getRoverLocation($roverName, $normalizedPlayGroundSize);
        $moves = $this->getRoverMoves($roverName);

        return new RoverDTO($location, $moves);
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

        return $moves;
    }

    private function getRoverLocation(string $roverName, array $normalizedPlayGroundSize): string
    {
        do {
            $location = $this
                ->ask("Ok I named one of your rover '$roverName' now let me know what is the location of $roverName ? example 1 3 e");
            $maxXBoarder = $normalizedPlayGroundSize['x'];
            $maxYBoarder = $normalizedPlayGroundSize['y'];
            $validator = Validator::make([
                'rover_location' => $location
            ], [
                'rover_location' => ['required', "regex:/\b([0-$maxXBoarder])[[:space:]]([0-$maxYBoarder])[[:space:]]\b(?i)(n|e|w|s)\b/"]
            ]);
        } while ($validator->errors()->getMessages() != []);

        return $location;
    }
}
