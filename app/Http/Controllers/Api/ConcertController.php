<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use Illuminate\Http\JsonResponse;

class ConcertController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'upcoming' => Concert::upcoming()->get(),
            'past'     => Concert::past()->get(),
        ]);
    }

    public function show(Concert $concert): JsonResponse
    {
        $concert->incrementViews();

        return response()->json($concert);
    }
}
