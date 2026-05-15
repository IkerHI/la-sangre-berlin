<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactNotification;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(ContactFormRequest $request): JsonResponse
    {
        $msg = ContactMessage::create($request->validated());

        try {
            Mail::to(config('mail.to.address'))
                ->send(new ContactNotification($msg));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('ContactNotification mail failed: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Message sent successfully'], 201);
    }
}
