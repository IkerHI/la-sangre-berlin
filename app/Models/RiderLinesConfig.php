<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiderLinesConfig extends Model
{
    protected $fillable = [
        'config',
        'channels',
        'tech_notes_es',
        'tech_notes_de',
        'tech_notes_en',
    ];

    protected $casts = [
        'channels' => 'array',
    ];
}
