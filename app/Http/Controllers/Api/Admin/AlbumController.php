<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Album::orderBy('sort_order')->orderByDesc('release_year')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'             => ['required', 'string', 'max:200'],
            'release_year'      => ['required', 'integer', 'min:1900', 'max:2100'],
            'spotify_embed_url' => ['nullable', 'string', 'max:500'],
            'description_es'    => ['nullable', 'string'],
            'description_de'    => ['nullable', 'string'],
            'description_en'    => ['nullable', 'string'],
            'sort_order'        => ['nullable', 'integer'],
            'cover_image'       => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('albums', 'public');
        }

        $album = Album::create($data);

        return response()->json($album, 201);
    }

    public function update(Request $request, Album $album): JsonResponse
    {
        $data = $request->validate([
            'title'             => ['sometimes', 'string', 'max:200'],
            'release_year'      => ['sometimes', 'integer', 'min:1900', 'max:2100'],
            'spotify_embed_url' => ['nullable', 'string', 'max:500'],
            'description_es'    => ['nullable', 'string'],
            'description_de'    => ['nullable', 'string'],
            'description_en'    => ['nullable', 'string'],
            'sort_order'        => ['nullable', 'integer'],
            'cover_image'       => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($album->cover_image) Storage::disk('public')->delete($album->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('albums', 'public');
        }

        $album->update($data);

        return response()->json($album);
    }

    public function destroy(Album $album): JsonResponse
    {
        if ($album->cover_image) Storage::disk('public')->delete($album->cover_image);
        $album->delete();

        return response()->json(null, 204);
    }
}
