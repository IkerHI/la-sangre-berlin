<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    protected $fillable = [
        'bandsintown_id', 'date', 'venue_name', 'city', 'country',
        'ticket_url', 'status', 'views', 'title_es', 'title_de', 'title_en',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'views' => 'integer',
        ];
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now())->orderBy('date', 'asc');
    }

    public function scopePast($query)
    {
        return $query->where('date', '<', now())->orderBy('date', 'desc');
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
