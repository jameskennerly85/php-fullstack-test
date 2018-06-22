<?php
namespace App\Services;

use App\Exceptions\Model\MatchIsNotJoinableException;
use App\Models\Match;
use App\Validators\MatchServiceValidator;
use Illuminate\Support\Collection;

class MatchService
{
    /**
     * @var MatchServiceValidator
     */
    protected $validator;

    /**
     * @param MatchServiceValidator $validator
     */
    public function __construct(MatchServiceValidator $validator)
    {
        $this->validator = $validator;
    }

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
        return Match::create($attributes)->refresh();
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

    /**
     * @param array $attributes
     * @return Match
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     * @throws \App\Exceptions\Model\InvalidMatchMoveException
     */
    public function attemptMove(array $attributes): Match
    {
        $this->validator
            ->with($attributes)
            ->passesOrFail(MatchServiceValidator::$RULE_MOVE)
        ;

        $match = Match::find($attributes['id']);
        $match->playOnPosition($attributes['position'], $match->next);

        $nextPlayer = 1;

        if ($match->next === 1) {
            $nextPlayer = 2;
        }

        $match->next = $nextPlayer;
        $match->save();

        return $match;
    }
}