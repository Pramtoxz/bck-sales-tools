<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\FlpResource;
use App\Models\User;
use App\Models\FlpDevice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah'],
            ]);
        }

        $flp = $user->flp;

        if (!$flp) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terdaftar sebagai FLP',
            ], 403);
        }

        if (!$flp->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun FLP tidak aktif',
            ], 403);
        }

        $token = $user->createToken($request->device_id)->plainTextToken;

        FlpDevice::updateOrCreate(
            [
                'id_flp' => $flp->id_flp,
                'device_id' => $request->device_id,
            ],
            [
                'user_id' => $user->id,
                'device_name' => $request->device_name,
                'device_type' => $request->device_type,
                'last_active' => now(),
            ]
        );

        $flp->update(['last_login' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'user' => new UserResource($user),
                'flp' => new FlpResource($flp),
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();

        if ($user->flp) {
            FlpDevice::where('id_flp', $user->flp->id_flp)->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout dari semua device berhasil',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $flp = $user->flp;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($user),
                'flp' => $flp ? new FlpResource($flp) : null,
            ],
        ]);
    }

    public function devices(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user->flp) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terdaftar sebagai FLP',
            ], 403);
        }

        $devices = FlpDevice::where('id_flp', $user->flp->id_flp)
            ->orderBy('last_active', 'desc')
            ->get()
            ->map(function ($device) {
                return [
                    'device_id' => $device->device_id,
                    'device_name' => $device->device_name,
                    'device_type' => $device->device_type,
                    'last_active' => $device->last_active?->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'devices' => $devices,
            ],
        ]);
    }
}
