<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TechSpecPdf extends Model
{
    protected $fillable = ['language', 'filename', 'path'];

    protected $appends = ['url'];

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }
}
