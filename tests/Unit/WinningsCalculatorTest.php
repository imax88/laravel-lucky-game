<?php

namespace Tests\Unit;

use App\Providers\Services\WinningsCalculator;
use Tests\TestCase;
use RuntimeException;

/**
 * Test case for the WinningsCalculator service.
 */
class WinningsCalculatorTest extends TestCase
{
    protected WinningsCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new WinningsCalculator();
    }

    /**
     * Test winnings calculation for a losing game.
     */
    public function testCalculateReturnsZeroForLose(): void
    {
        $result = $this->calculator->calculate(500, 'Lose');
        $this->assertEquals(0.0, $result);
    }

    /**
     * Test winnings calculation for invalid result throws no exception but returns zero.
     */
    public function testCalculateReturnsZeroForInvalidResult(): void
    {
        $result = $this->calculator->calculate(500, 'invalid');
        $this->assertEquals(0.0, $result);
    }

    /**
     * Test winnings calculation throws exception when configuration is missing.
     */
    public function testCalculateThrowsExceptionWhenConfigMissing(): void
    {
        $this->app['config']->set('game', null);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Game configuration is missing.');

        $this->calculator->calculate(500, 'Win');
    }

    /**
     * Test winnings calculation for different random number thresholds.
     *
     * @dataProvider winningsDataProvider
     */
    public function testCalculateReturnsCorrectWinnings(int $randomNumber, float $expectedWinnings): void
    {
        $result = $this->calculator->calculate($randomNumber, 'Win');
        $this->assertEquals($expectedWinnings, $result);
    }

    /**
     * Data provider for winnings calculation test.
     *
     * @return array
     */
    public static function winningsDataProvider(): array
    {
        return [
            'high threshold (>900)' => [950, 950 * 0.7],
            'boundary high (900)' => [900, 900 * 0.5], // <=900 uses medium
            'medium threshold (>600)' => [700, 700 * 0.5],
            'boundary medium (600)' => [600, 600 * 0.3], // <=600 uses low
            'low threshold (>300)' => [400, 400 * 0.3],
            'boundary low (300)' => [300, 300 * 0.1], // <=300 uses default
            'default threshold (<300)' => [200, 200 * 0.1],
        ];
    }

    /**
     * Test winnings calculation for invalid random number throws no exception but processes correctly.
     */
    public function testCalculateHandlesInvalidRandomNumber(): void
    {
        // Assuming valid range is 1–1000, but method doesn't enforce it
        $result = $this->calculator->calculate(0, 'Win');
        $this->assertEquals(0 * 0.1, $result); // default threshold

        $result = $this->calculator->calculate(1001, 'Win');
        $this->assertEquals(1001 * 0.7, $result); // high threshold
    }
}
