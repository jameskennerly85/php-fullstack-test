<?php
namespace App\Services;

use App\Models\Match;
use Illuminate\Support\Collection;

class MatchService
{
    /**
     *
     * Return collection of all joinable matches.
     * @return Collection
     */
    public function allJoinableMatches(): Collection
    {
        return Match::where('winner', 0)->get();
    }

    /**
     * Create new match.
     *
     * @param array $attributes
     * @return Match
     */
    public function createMatch(array $attributes = []): Match
    {
        return Match::create($attributes);
    }

    /**
     * Delete match by ID.
     *
     * @param int $matchId
     * @return void
     */
    public function deleteMatch(int $matchId)
    {
        Match::where('id', $matchId)->delete();
    }
}