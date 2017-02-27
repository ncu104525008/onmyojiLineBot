<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonsterDetail extends Model
{
    protected $fillable = [
        'stageDetailId', 'monsterId', 'number',
    ];

    public $timestamps = false;
}
