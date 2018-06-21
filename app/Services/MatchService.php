<?php
namespace App\Services;

use App\Models\Match;
use Illuminate\Support\Collection;

class MatchService
{
    /**
     * @return Collection
     */
    public function allJoinableMatches(): Collection
    {
        return Match::where('winner', 0)->get();
    }

    /**
     * @param array $attributes
     * @return Match
     */
    public function createMatch(array $attributes = [])
    {
        return Match::create($attributes);
    }
}