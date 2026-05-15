<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value_es', 'value_de', 'value_en'];

    public static function getValue(string $key, string $locale = 'es'): ?string
    {
        $col = "value_{$locale}";
        return static::where('key', $key)->value($col);
    }
}
