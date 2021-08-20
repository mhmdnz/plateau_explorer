<?php

namespace App\Console\Commands;

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

    public function handle()
    {
        $playerData = [];
        $playGroundSize = $this->getPlaygroundSize();

        $this->info('Ok, I will check the input data last, but lets start with ');
        while ($this->confirm('Do you wish to continue?')) {
            $playGroundSize = $this
                ->ask('Lets start with the size of our plateau, could you please enter the max X,Y?');
        }
        die('injas');
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
                'play_ground_size' => ['required', 'regex:/(?<=\d) (?=\d)/']
            ]);
        } while ($validator->errors()->getMessages() != []);

        return $playGroundSize;
    }
}
