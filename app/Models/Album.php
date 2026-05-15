<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Album extends Model
{
    protected $fillable = [
        'title', 'release_year', 'cover_image', 'spotify_embed_url',
        'description_es', 'description_de', 'description_en', 'sort_order',
    ];

    protected $appends = ['cover_url'];

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image ? Storage::url($this->cover_image) : null;
    }
}
