<?php

namespace App\Http\Controllers\Api\External;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
            ->select(['id', 'username', 'email', 'password', 'fk_dealer', 'is_kacab'])
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        $allowedDealers = ['5670', '6732', '08199', '00399', '09164'];

        if (!$user->is_kacab || !in_array((string) $user->fk_dealer, $allowedDealers, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. User tidak memiliki hak akses.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Autentikasi berhasil.',
            'data' => [
                'username'  => $user->username,
                'email'     => $user->email,
                'fk_dealer' => $user->fk_dealer,
                'is_kacab'  => $user->is_kacab,
            ],
        ]);
    }
}
