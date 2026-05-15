<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = GalleryImage::orderBy('sort_order');

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        return response()->json($query->get());
    }
}
