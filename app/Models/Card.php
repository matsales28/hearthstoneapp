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
        'playerClass',
        'img',
        'cardSet',
        'race',
        'type',
        'text',
        'mechanics',
        'flavor',
        'cost',
        'rarity'
    ];

    protected $casts = [
        'mechanics' => 'array'
    ];
}

