<?php

namespace App\Providers\Services;

use App\Providers\Contracts\WinningsCalculatorInterface;
use RuntimeException;

/**
 * Service for calculating game winnings based on random number and result.
 */
class WinningsCalculator implements WinningsCalculatorInterface
{
    /**
     * Calculate the winnings for a game based on the random number and result.
     *
     * @param int $randomNumber The random number generated (1 to 1000).
     * @param string $result The game result ('Win' or 'Lose').
     * @return float The calculated winnings.
     * @throws \RuntimeException If game configuration is missing.
     */
    public function calculate(int $randomNumber, string $result): float
    {
        if ($result !== 'Win') {
            return 0.0;
        }

        $thresholds = config('game.thresholds');
        $percentages = config('game.percentages');

        if (!$thresholds || !$percentages) {
            throw new RuntimeException('Game configuration is missing.');
        }

        return match (true) {
            $randomNumber > $thresholds['high'] => $randomNumber * $percentages['high'],
            $randomNumber > $thresholds['medium'] => $randomNumber * $percentages['medium'],
            $randomNumber > $thresholds['low'] => $randomNumber * $percentages['low'],
            default => $randomNumber * $percentages['default'],
        };
    }
}
