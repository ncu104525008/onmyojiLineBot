<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'type', 'userId',
    ];

    public $timestamps = false;
}
