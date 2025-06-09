<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    // List all players (optionally filter by date)
    public function index(Request $request)
    {
        //give only players for today 
        $date = $request->query('date', now()->toDateString());
        $players = Player::where('date', $date)->get();

        // Get all valid English words from CSV
        $csvWords = array_map('strtoupper', array_map('trim', file(base_path('english5letterwords.csv'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)));

        // All time ranking
        $tries = Player::all()->flatMap(function ($player) {
            return collect([
                $player->try1,
                $player->try2,
                $player->try3,
                $player->try4,
                $player->try5,
                $player->try6,
            ]);
        })->filter()
            ->map(fn($word) => strtoupper($word))
            ->filter(fn($word) => in_array($word, $csvWords))
            ->countBy()
            ->sortDesc()
            ->take(10);

        // Today's ranking
        $todayTries = Player::where('date', $date)->get()->flatMap(function ($player) {
            return collect([
                $player->try1,
                $player->try2,
                $player->try3,
                $player->try4,
                $player->try5,
                $player->try6,
            ]);
        })->filter()
            ->map(fn($word) => strtoupper($word))
            ->filter(fn($word) => in_array($word, $csvWords))
            ->countBy()
            ->sortDesc()
            ->take(10);

        return view('players.index', compact('players', 'tries', 'todayTries'));
    }

    // Show a single player's tries for today (or a given date)
    public function show(Request $request, $id)
    {
        $date = $request->query('date', now()->toDateString());
        $player = Player::where('id', $id)->where('date', $date)->firstOrFail();
        return view('players.show', compact('player'));
    }
}
