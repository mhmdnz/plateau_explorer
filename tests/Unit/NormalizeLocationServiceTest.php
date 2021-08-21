<?php

namespace Tests\Unit;

use App\DTOs\LocationDTO;
use App\Services\NormalizeLocationService;
use PHPUnit\Framework\TestCase;

class NormalizeLocationServiceTest extends TestCase
{
    public function testNormalizeLocationService()
    {
        //ARRANGE
        $sut = $this->resolveAction();

        // ACT
        $result = $sut('1 3');

        //ASSERT
        $this->assertInstanceOf(LocationDTO::class, $result);
        $this->assertEquals(1, $result->x);
        $this->assertEquals(3, $result->y);
    }

    private function resolveAction(): NormalizeLocationService
    {
        return resolve(NormalizeLocationService::class);
    }
}
