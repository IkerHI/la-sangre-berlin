<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteStat extends Model
{
    public $timestamps = false;
    protected $fillable = ['metric', 'value'];

    public static function record(string $metric): void
    {
        static::firstOrCreate(
            ['metric' => $metric],
            ['value' => 0]
        );
        static::where('metric', $metric)->increment('value');
    }

    public static function fetch(string $metric): int
    {
        return (int) static::where('metric', $metric)->value('value');
    }
}
