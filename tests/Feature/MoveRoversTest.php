<?php

namespace Tests\Feature;

use App\Console\Commands\MoveRovers;
use Faker\Generator;
use Mockery\MockInterface;
use Tests\TestCase;

class MoveRoversTest extends TestCase
{
    /**
     * @dataProvider successfulProvider
     */
    public function testSuccessfully(
        string $playGroundSize,
        string $locationFaceOrientation,
        string $moves
    ):void {
        //ARRANGE
        $mockedGenerator = $this->mockGenerator();
        $messages = $this->getMessages();

        //Expects
        $mockedGenerator->shouldReceive('format')
            ->andReturn($messages['roverName']);

        //Assert
        $this->artisan('rovers:explore')
            ->expectsQuestion(MoveRovers::GET_PLAYGROUND_SIZE_MESSAGE, $playGroundSize)
            ->expectsQuestion($messages['roverLocationFaceMessage'], $locationFaceOrientation)
            ->expectsQuestion($messages['roverMoveMessage'], $moves)
            ->expectsConfirmation($messages['askForAnotherRoverMessage'], 'no')
            ->expectsOutput($messages['finalResultMessage']);
    }

    public function successfulProvider()
    {
        return [
            'normalDataCheck' => ['5 5', '1 3 e', 'mm'],
            'bigNumbersCheck' => ['100 100', '10 30 e', 'mm'],
            'upperCaseOrientationCheck' => ['100 100', '1 3 E', 'mm'],
            'upperCaseMovesCheck' => ['100 100', '1 3 W', 'MLR'],
        ];
    }

    /**
     * @dataProvider playGroundSizeProvider
     */
    public function testPlayGroundValidation(string $playGroundSize)
    {
        //ARRANGE
        $mockedGenerator = $this->mockGenerator();
        $messages = $this->getMessages();

        //Expects
        $mockedGenerator->shouldReceive('format')
            ->andReturn($messages['roverName']);

        //Assert
        $this->artisan('rovers:explore')
            ->expectsQuestion(MoveRovers::GET_PLAYGROUND_SIZE_MESSAGE, $playGroundSize)
            ->expectsQuestion(MoveRovers::GET_PLAYGROUND_SIZE_MESSAGE, '5 5')
            ->expectsQuestion($messages['roverLocationFaceMessage'], '1 3 e')
            ->expectsQuestion($messages['roverMoveMessage'], 'mm')
            ->expectsConfirmation($messages['askForAnotherRoverMessage'], 'no')
            ->expectsOutput($messages['finalResultMessage']);
    }

    public function playGroundSizeProvider()
    {
        return [
            'twoStringCheck' => ['ee'],
            'intWithStringCheck' => ['1 e'],
            'stringWithIntCheck' => ['e 1'],
            'startWithStringCheck' => ['e1 1'],
            'EndWithStringCheck' => ['1 1e'],
            'spaceWithStringCheck' => ['1 1 e'],
            'minesCheck' => ['-1 -1 e'],
        ];
    }

    /**
     * @dataProvider locationFaceProvider
     */
    public function testLocationFaceValidation(string $locationFaceOrientation)
    {
        //ARRANGE
        $mockedGenerator = $this->mockGenerator();
        $messages = $this->getMessages();

        //Expects
        $mockedGenerator->shouldReceive('format')
            ->andReturn($messages['roverName']);

        //Assert
        $this->artisan('rovers:explore')
            ->expectsQuestion(MoveRovers::GET_PLAYGROUND_SIZE_MESSAGE, '5 5')
            ->expectsQuestion($messages['roverLocationFaceMessage'], $locationFaceOrientation)
            ->expectsQuestion($messages['roverLocationFaceMessage'], '1 3 e')
            ->expectsQuestion($messages['roverMoveMessage'], 'mm')
            ->expectsConfirmation($messages['askForAnotherRoverMessage'], 'no')
            ->expectsOutput($messages['finalResultMessage']);
    }

    public function locationFaceProvider()
    {
        return [
            'twoStringCheck' => ['ee'],
            'intWithStringCheck' => ['1 e'],
            'stringWithIntCheck' => ['e 1'],
            'startWithStringCheck' => ['e1 1'],
            'EndWithStringCheck' => ['1 1e'],
            'wrongOrientationCheck' => ['1 1 o'],
            'xBiggerThanPlaygroundSizeCheck' => ['6 1 e'],
            'yBiggerThanPlaygroundSizeCheck' => ['3 6 e'],
            'minesXCheck' => ['-3 4 e'],
            'minesYCheck' => ['3 -4 e'],
            'signsCheck' => ['/ -4 e'],
        ];
    }

    /**
     * @dataProvider movesProvider
     */
    public function testMovesValidation(string $moves)
    {
        //ARRANGE
        $mockedGenerator = $this->mockGenerator();
        $messages = $this->getMessages();

        //Expects
        $mockedGenerator->shouldReceive('format')
            ->andReturn($messages['roverName']);

        //Assert
        $this->artisan('rovers:explore')
            ->expectsQuestion(MoveRovers::GET_PLAYGROUND_SIZE_MESSAGE, '5 5')
            ->expectsQuestion($messages['roverLocationFaceMessage'], '1 3 e')
            ->expectsQuestion($messages['roverMoveMessage'], $moves)
            ->expectsQuestion($messages['roverMoveMessage'], 'mm')
            ->expectsConfirmation($messages['askForAnotherRoverMessage'], 'no')
            ->expectsOutput($messages['finalResultMessage']);
    }

    public function movesProvider()
    {
        return [
            'wrongCharsCheck' => ['ee'],
            'wrongChars2Check' => ['AA'],
            'numbersCheck' => ['111'],
            'numbersStringsCheck' => ['mm12m'],
            'startNumbersCheck' => ['1mmm'],
            'spaceCheck' => [' mmm'],
            'spaceMiddleCheck' => ['m mm'],
        ];
    }

    private function mockGenerator(): MockInterface
    {
        return $this->mock(Generator::class);
    }

    private function getMessages(): array
    {
        $messages['roverName'] = 'Mohammad';

        $messages['roverLocationFaceMessage'] = str_replace(
            ':roverName',
            $messages['roverName'],
            MoveRovers::GET_ROVER_LOCATION_FACE_MESSAGE
        );
        $messages['roverMoveMessage'] = str_replace(':roverName',
            $messages['roverName'],
            MoveRovers::GET_ROVER_MOVES_MESSAGE
        );
        $messages['askForAnotherRoverMessage'] = str_replace(
            ':roverName',
            $messages['roverName'],
            MoveRovers::ASK_FOR_ANOTHER_ROVER_MESSAGE
        );
        $messages['finalResultMessage'] = MoveRovers::FINAL_RESULT_MESSAGE;

        return $messages;
    }
}
