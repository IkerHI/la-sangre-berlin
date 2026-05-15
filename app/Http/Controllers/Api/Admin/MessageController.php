<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            ContactMessage::orderByDesc('created_at')->get()
        );
    }

    public function markRead(ContactMessage $contactMessage): JsonResponse
    {
        $contactMessage->update(['read' => true]);

        return response()->json($contactMessage);
    }

    public function destroy(ContactMessage $contactMessage): JsonResponse
    {
        $contactMessage->delete();

        return response()->json(null, 204);
    }
}
