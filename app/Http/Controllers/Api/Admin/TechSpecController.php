<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\TechSpec;
use App\Models\TechSpecPdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TechSpecController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'specs' => TechSpec::orderBy('sort_order')->get(),
            'pdfs'  => TechSpecPdf::all(),
        ]);
    }

    public function upsert(Request $request): JsonResponse
    {
        $data = $request->validate([
            'specs'               => ['required', 'array'],
            'specs.*.id'          => ['nullable', 'integer'],
            'specs.*.category'    => ['required', 'string', 'max:100'],
            'specs.*.key'         => ['required', 'string', 'max:200'],
            'specs.*.value_es'    => ['nullable', 'string'],
            'specs.*.value_de'    => ['nullable', 'string'],
            'specs.*.value_en'    => ['nullable', 'string'],
            'specs.*.sort_order'  => ['nullable', 'integer'],
        ]);

        foreach ($data['specs'] as $specData) {
            $id = $specData['id'] ?? null;
            unset($specData['id']);

            if ($id) {
                TechSpec::where('id', $id)->update($specData);
            } else {
                TechSpec::create($specData);
            }
        }

        return response()->json(TechSpec::orderBy('sort_order')->get());
    }

    public function uploadPdf(Request $request): JsonResponse
    {
        $request->validate([
            'pdf'      => ['required', 'file', 'mimes:pdf', 'max:20480'],
            'language' => ['required', 'in:es,de,en,all'],
        ]);

        $old = TechSpecPdf::where('language', $request->language)->first();
        if ($old) {
            Storage::disk('public')->delete($old->path);
            $old->delete();
        }

        $file = $request->file('pdf');
        $path = $file->store('riders', 'public');

        $pdf = TechSpecPdf::create([
            'language' => $request->language,
            'filename' => $file->getClientOriginalName(),
            'path'     => $path,
        ]);

        return response()->json($pdf, 201);
    }

    public function destroySpec(TechSpec $techSpec): JsonResponse
    {
        $techSpec->delete();

        return response()->json(null, 204);
    }
}
