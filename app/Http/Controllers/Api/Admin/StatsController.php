<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\ContactMessage;
use App\Models\SiteStat;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'total_visits'     => SiteStat::fetch('total_visits'),
            'unread_messages'  => ContactMessage::where('read', false)->count(),
            'total_concerts'   => Concert::count(),
            'upcoming_concerts'=> Concert::upcoming()->count(),
            'top_concerts'     => Concert::orderByDesc('views')->take(5)->get(['id', 'venue_name', 'city', 'date', 'views']),
        ]);
    }
}
