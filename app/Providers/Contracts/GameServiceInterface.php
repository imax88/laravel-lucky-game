<?php

namespace App\Providers\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface GameServiceInterface
{
    /**
     * Play the game for the given user and return the result.
     *
     * @param int $userId The ID of the user.
     * @return array
     */
    public function playGame(int $userId): array;

    /**
     * Retrieve the last 3 game history records for the given user.
     *
     * @param int $userId The ID of the user.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getGameHistory(int $userId): Collection;
}
