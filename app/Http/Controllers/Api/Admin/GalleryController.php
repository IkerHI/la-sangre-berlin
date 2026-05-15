<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(GalleryImage::orderBy('sort_order')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image'      => ['required', 'image', 'max:10240'],
            'type'       => ['required', 'in:epk,normal'],
            'alt_es'     => ['nullable', 'string', 'max:200'],
            'alt_de'     => ['nullable', 'string', 'max:200'],
            'alt_en'     => ['nullable', 'string', 'max:200'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $file = $request->file('image');
        $path = $file->store('gallery', 'public');

        $image = GalleryImage::create([
            'filename'   => $file->getClientOriginalName(),
            'path'       => $path,
            'type'       => $request->type,
            'alt_es'     => $request->alt_es,
            'alt_de'     => $request->alt_de,
            'alt_en'     => $request->alt_en,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return response()->json($image, 201);
    }

    public function update(Request $request, GalleryImage $galleryImage): JsonResponse
    {
        $request->validate([
            'type'   => ['sometimes', 'in:epk,normal'],
            'alt_es' => ['nullable', 'string', 'max:200'],
            'alt_de' => ['nullable', 'string', 'max:200'],
            'alt_en' => ['nullable', 'string', 'max:200'],
        ]);

        $galleryImage->update($request->only(['type', 'alt_es', 'alt_de', 'alt_en']));

        return response()->json($galleryImage);
    }

    public function destroy(GalleryImage $galleryImage): JsonResponse
    {
        Storage::disk('public')->delete($galleryImage->path);
        $galleryImage->delete();

        return response()->json(null, 204);
    }
}
