<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechSpec extends Model
{
    protected $fillable = [
        'category', 'key', 'value_es', 'value_de', 'value_en', 'sort_order',
    ];
}
