<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\JsonResponse;

class AlbumController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Album::orderBy('sort_order')->orderByDesc('release_year')->get()
        );
    }
}
