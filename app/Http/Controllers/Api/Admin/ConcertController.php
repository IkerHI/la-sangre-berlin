<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Services\BandsintownService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConcertController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Concert::orderBy('date', 'asc')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'date'       => ['required', 'date'],
            'venue_name' => ['required', 'string', 'max:200'],
            'city'       => ['required', 'string', 'max:100'],
            'country'    => ['required', 'string', 'max:100'],
            'ticket_url' => ['nullable', 'url'],
            'title_es'   => ['nullable', 'string', 'max:200'],
            'title_de'   => ['nullable', 'string', 'max:200'],
            'title_en'   => ['nullable', 'string', 'max:200'],
        ]);

        $data['status'] = now()->gt($data['date']) ? 'past' : 'upcoming';
        $concert = Concert::create($data);

        return response()->json($concert, 201);
    }

    public function update(Request $request, Concert $concert): JsonResponse
    {
        $data = $request->validate([
            'date'       => ['sometimes', 'date'],
            'venue_name' => ['sometimes', 'string', 'max:200'],
            'city'       => ['sometimes', 'string', 'max:100'],
            'country'    => ['sometimes', 'string', 'max:100'],
            'ticket_url' => ['nullable', 'url'],
            'title_es'   => ['nullable', 'string', 'max:200'],
            'title_de'   => ['nullable', 'string', 'max:200'],
            'title_en'   => ['nullable', 'string', 'max:200'],
        ]);

        if (isset($data['date'])) {
            $data['status'] = now()->gt($data['date']) ? 'past' : 'upcoming';
        }

        $concert->update($data);

        return response()->json($concert);
    }

    public function destroy(Concert $concert): JsonResponse
    {
        $concert->delete();

        return response()->json(null, 204);
    }

    public function sync(BandsintownService $bandsintown): JsonResponse
    {
        $result = $bandsintown->syncConcerts();

        return response()->json($result);
    }
}
