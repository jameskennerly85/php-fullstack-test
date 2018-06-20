<?php
namespace App\Models;

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
        return explode(',', $value);
    }

    /**
     * @param array $value
     * @return void
     */
    public function setBoardAttribute(array $value)
    {
        $this->attributes['board'] = implode(',', $value);
    }
}