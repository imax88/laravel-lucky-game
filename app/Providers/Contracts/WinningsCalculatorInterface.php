<?php

namespace App\Providers\Contracts;

/**
 * Interface for calculating game winnings.
 */
interface WinningsCalculatorInterface
{
    /**
     * Calculate the winnings for a game based on the random number and result.
     *
     * @param int $randomNumber The random number generated (1 to 1000).
     * @param string $result The game result ('Win' or 'Lose').
     * @return float The calculated winnings.
     */
    public function calculate(int $randomNumber, string $result): float;
}
