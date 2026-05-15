<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            User::where('is_admin', true)
                ->select('id', 'name', 'email', 'created_at')
                ->orderBy('created_at')
                ->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
        ]);

        return response()->json($user->only('id', 'name', 'email', 'created_at'), 201);
    }

    public function updatePassword(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'password' => ['required', Password::min(8)],
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['ok' => true]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'No puedes eliminar tu propia cuenta.'], 422);
        }

        $user->delete();

        return response()->json(null, 204);
    }
}
