<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            SiteSetting::all()->keyBy('key')
        );
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'settings'              => ['required', 'array'],
            'settings.*.key'        => ['required', 'string', 'max:100'],
            'settings.*.value_es'   => ['nullable', 'string'],
            'settings.*.value_de'   => ['nullable', 'string'],
            'settings.*.value_en'   => ['nullable', 'string'],
        ]);

        foreach ($data['settings'] as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value_es' => $setting['value_es'] ?? null,
                    'value_de' => $setting['value_de'] ?? null,
                    'value_en' => $setting['value_en'] ?? null,
                ]
            );
        }

        return response()->json(SiteSetting::all()->keyBy('key'));
    }
}
