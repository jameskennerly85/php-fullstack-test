<?php
namespace App\Models;

use App\Exceptions\Model\InvalidBoardSettingException;
use App\Exceptions\Model\InvalidMatchMoveException;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    /**
     * @var string
     */
    protected $table = 'matches';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @var array
     */
    private $rules = [
        'name'   => 'string',
        'next'   => 'integer|in:1,2',
        'winner' => 'integer|in:0,1,2',
        'board'  => 'string|size:17',
    ];

    /**
     * @param $value
     * @return string
     */
    public function getNameAttribute($value): string
    {
        if (empty($value)) {
            return sprintf('untitled match %d', $this->id);
        }

        return $value;
    }

    /**
     * @param $value
     * @return array
     */
    public function getBoardAttribute($value): array
    {
        return array_map(function ($n) {
            return intval($n);
        }, explode(',', $value));
    }

    /**
     * @param array $value
     * @return void
     * 
     * @throws InvalidBoardSettingException
     */
    public function setBoardAttribute(array $value)
    {
        $strVal = implode(',', $value);
        
        if (preg_match('/^[0-2](,[0-2]){8}$/', $strVal) !== 1) {
            throw new InvalidBoardSettingException;
        }

        $this->attributes['board'] = $strVal;
    }

    /**
     * @param int $position
     * @return int
     */
    public function getBoardPosition(int $position): int
    {
        return $this->board[$position];
    }

    /**
     * @param int $position
     * @param int $value
     * @return Match
     */
    public function setBoardPosition($position, $value): Match
    {
        $board = $this->board;
        $board[$position] = $value;
        $this->board = $board;

        return $this;
    }

    /**
     * @param int $position
     * @param int $value
     * @return Match
     *
     * @throws InvalidMatchMoveException
     */
    public function playOnPosition($position, $value): Match
    {
        if ($this->getBoardPosition($position) !== 0) {
            throw new InvalidMatchMoveException;
        }

        return $this->setBoardPosition($position, $value);
    }
}