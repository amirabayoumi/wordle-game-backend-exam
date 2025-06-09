<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'date',
        'token',
        'try1',
        'try2',
        'try3',
        'try4',
        'try5',
        'try6'
    ];
}
