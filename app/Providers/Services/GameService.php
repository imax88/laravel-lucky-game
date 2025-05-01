<?php

namespace App\Providers\Services;

use App\Models\GameHistory;
use App\Providers\Contracts\GameServiceInterface;
use App\Providers\Contracts\WinningsCalculatorInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling game logic and history retrieval.
 */
class GameService implements GameServiceInterface
{
    protected $winningsCalculator;

    /**
     * GameService constructor.
     *
     * @param \App\Providers\Contracts\WinningsCalculatorInterface $winningsCalculator
     */
    public function __construct(WinningsCalculatorInterface $winningsCalculator)
    {
        $this->winningsCalculator = $winningsCalculator;
    }

    /**
     * Play the game for the given user and return the result.
     *
     * @param int $userId The ID of the user.
     * @return array
     */
    public function playGame(int $userId): array
    {

        $randomNumber = mt_rand(1, 1000);


        $result = $randomNumber % 2 === 0 ? 'Win' : 'Lose';


        $winnings = $this->winningsCalculator->calculate($randomNumber, $result);

       
        $gameHistory = GameHistory::create([
            'user_id' => $userId,
            'random_number' => $randomNumber,
            'result' => $result,
            'winnings' => $winnings,
            'created_at' => now(),
        ]);

        Log::info('Game result saved', [
            'user_id' => $userId,
            'random_number' => $randomNumber,
            'result' => $result,
            'winnings' => $winnings,
        ]);

        return [
            'random_number' => $randomNumber,
            'result' => $result,
            'winnings' => $winnings,
        ];
    }

    /**
     * Retrieve the last 3 game history records for the given user.
     *
     * @param int $userId The ID of the user.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getGameHistory(int $userId): Collection
    {
        return GameHistory::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }
}
