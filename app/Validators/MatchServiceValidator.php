<?php
namespace App\Validators;

use Prettus\Validator\LaravelValidator;

class MatchServiceValidator extends LaravelValidator
{
    /**
     * @var string
     */
    public static $RULE_MOVE = 'match_service_move';

    /**
     * @var array $rules
     */
    protected $rules = [
        'match_service_move' => [
            'id' => 'required|integer|exists:matches,id,winner,0',
            'position' => 'required|integer|min:0|max:8',
        ]
    ];
}
