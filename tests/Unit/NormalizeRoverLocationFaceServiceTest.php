<?php

namespace Tests\Unit;

use App\DTOs\LocationFaceDTO;
use App\Services\NormalizeRoverLocationFaceService;
use PHPUnit\Framework\TestCase;

class NormalizeRoverLocationFaceServiceTest extends TestCase
{
    public function testNormalizeRoverLocationFaceService()
    {
        //ARRANGE
        $sut = $this->resolveAction();

        // ACT
        $result = $sut('1 3 e');

        //ASSERT
        $this->assertInstanceOf(LocationFaceDTO::class, $result);
        $this->assertEquals(1, $result->locationDTO->x);
        $this->assertEquals(3, $result->locationDTO->y);
        $this->assertEquals(LocationFaceDTO::EAST, $result->facing);
    }

    private function resolveAction(): NormalizeRoverLocationFaceService
    {
        return resolve(NormalizeRoverLocationFaceService::class);
    }
}
