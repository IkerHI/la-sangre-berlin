<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    protected $fillable = [
        'filename', 'path', 'alt_es', 'alt_de', 'alt_en', 'type', 'sort_order',
    ];

    protected $appends = ['url'];

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }
}
