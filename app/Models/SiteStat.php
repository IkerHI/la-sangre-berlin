<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteStat extends Model
{
    public $timestamps = false;
    protected $fillable = ['metric', 'value'];

    // Note: firstOrCreate + increment are two separate queries (not atomic).
    // Under concurrent load, under-counting is possible but acceptable at current traffic.
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
