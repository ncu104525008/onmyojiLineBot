<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StageDetail extends Model
{
    protected $fillable = [
        'stageId', 'name',
    ];

    public $timestamps = false;
}
