<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\SiteStat;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function index(): JsonResponse
    {
        SiteStat::record('total_visits');

        return response()->json(
            SiteSetting::all()->keyBy('key')
        );
    }
}
