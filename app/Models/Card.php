<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'name',
        'health',
        'attack',
        'faction',
        'race',
        'type',
        'text',
        'img',
        'mechanics'
    ];

    protected $casts = [
        'mechanics' => 'array'
    ];
}

