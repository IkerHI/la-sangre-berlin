<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TechSpec;
use App\Models\TechSpecPdf;
use Illuminate\Http\JsonResponse;

class TechSpecController extends Controller
{
    public function index(): JsonResponse
    {
        $specs = TechSpec::orderBy('sort_order')->get()->groupBy('category');
        $pdfs  = TechSpecPdf::all();

        return response()->json([
            'specs' => $specs,
            'pdfs'  => $pdfs,
        ]);
    }
}
