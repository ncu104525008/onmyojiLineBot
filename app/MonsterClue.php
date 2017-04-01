<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonsterClue extends Model
{
    protected $fillable = [
        'monsterId', 'clue',
    ];

    public $timestamps = false;
}
