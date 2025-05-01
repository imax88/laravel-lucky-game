<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Providers\Contracts\GameServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller for handling game-related actions, including playing the game and viewing history.
 */
class GameController extends Controller
{
    protected $gameService;

    /**
     * GameController constructor.
     *
     * @param \App\Providers\Contracts\GameServiceInterface $gameService
     */
    public function __construct(GameServiceInterface $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * Play the game for the given link UUID and return the result.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance.
     * @param string $uuid The UUID of the link.
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the link is not found, inactive, or expired.
     */
    public function playLucky(Request $request, string $uuid)
    {
        $link = Link::where('uuid', $uuid)
            ->where('is_active', true)
            ->where('created_at', '>=', now()->subDays(config('game.link_active_days')))
            ->firstOrFail();

        $gameResult = $this->gameService->playGame($link->user_id);

        $result = [
            'random_number' => $gameResult['random_number'],
            'result' => $gameResult['result'],
            'winnings' => $gameResult['winnings'],
        ];

        Log::info('Game played', [
            'user_id' => $link->user_id,
            'link_uuid' => $uuid,
            'random_number' => $result['random_number'],
            'result' => $result['result'],
            'winnings' => $result['winnings'],
        ]);

        return view('link', [
            'uuid' => $uuid,
            'game_result' => $result,
        ]);
    }

    /**
     * Display the last 3 game history records for the given link UUID.
     *
     * @param string $uuid The UUID of the link.
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the link is not found, inactive, or expired.
     */
    public function history(string $uuid)
    {
        $link = Link::where('uuid', $uuid)
            ->where('is_active', true)
            ->where('created_at', '>=', now()->subDays(config('game.link_active_days')))
            ->firstOrFail();

        $history = $this->gameService->getGameHistory($link->user_id);

        Log::info('Game history accessed', ['user_id' => $link->user_id, 'link_uuid' => $uuid]);

        return view('history', ['uuid' => $uuid, 'history' => $history]);
    }
}
