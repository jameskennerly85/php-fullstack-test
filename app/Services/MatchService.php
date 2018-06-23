<?php
namespace App\Services;

use App\Exceptions\Model\MatchIsNotJoinableException;
use App\Models\Match;
use App\Validators\MatchServiceValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     * Return a particular match by ID.
     *
     * @param int $id
     * @return Match
     *
     * @throws ModelNotFoundException
     */
    public function getMatch($id): Match
    {
        return Match::findOrFail($id);
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
     * @throws \App\Exceptions\Model\InvalidMatchMoveException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function attemptMove(array $attributes): Match
    {
        $this->validator
            ->with($attributes)
            ->passesOrFail(MatchServiceValidator::$RULE_MOVE)
        ;

        $match = Match::find($attributes['id']);
        $match->playOnPosition($attributes['position'], $match->next);

        $this->checkForWinner($match);

        $nextPlayer = 1;

        if ($match->next === 1) {
            $nextPlayer = 2;
        }

        $match->next = $nextPlayer;
        $match->save();

        return $match;
    }

    /**
     * @param Match $match
     * @return void
     */
    public function checkForWinner(Match $match)
    {
        $board = $match->board;
        $player = $match->next;

        $winningLines = [
            'R1' => [0,1,2],
            'R2' => [3,4,5],
            'R3' => [6,7,8],

            'C1' => [0,3,6],
            'C2' => [1,4,7],
            'C3' => [2,5,8],

            'D1' => [0,4,8],
            'D2' => [6,4,2],
        ];

        foreach ($winningLines as $line) {
            $winner = true;

            foreach ($line as $pos) {
                $value = $board[$pos];

                if ($value === 0 || $value !== $player) {
                    $winner = false;
                    break;
                }
            }

            if ($winner) {
                break;
            }
        }

        if ($winner) {
            $match->winner = $player;
        }
    }
}