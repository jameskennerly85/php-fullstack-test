<?php
namespace App\Services;

use App\Exceptions\Model\MatchIsNotJoinableException;
use App\Models\Match;
use Illuminate\Support\Collection;

class MatchService
{
    /**
     * Return collection of all joinable matches.
     *
     * @return Collection
     */
    public function allJoinableMatches(): Collection
    {
        return Match::where('winner', 0)->get();
    }

    /**
     * Return a particular joinable match by ID.
     *
     * @param int $id
     * @return Match
     *
     * @throws MatchIsNotJoinableException
     */
    public function getJoinable($id): Match
    {
        $match = Match::where('id', $id)
            ->where('winner', 0)
            ->first()
        ;

        if (is_null($match)) {
            throw new MatchIsNotJoinableException;
        }

        return $match;
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