<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $today = now()->toDateString();

        $banners = DB::connection('pgsql_nms')
            ->table('public.banners')
            ->select('id', 'title', 'image_path', 'start_date', 'end_date', 'sort_order', 'created_at')
            ->where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($banner) {
                $banner->image_url = url($banner->image_path);
                return $banner;
            });

        return response()->json([
            'success' => true,
            'data' => $banners,
        ]);
    }

    public function show(Request $request): JsonResponse
    {
        $id = $request->query('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter id diperlukan',
            ], 422);
        }

        $banner = DB::connection('pgsql_nms')
            ->table('public.banners')
            ->select('id', 'title', 'image_path', 'start_date', 'end_date', 'sort_order', 'is_active', 'created_at', 'updated_at')
            ->where('id', $id)
            ->first();

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner tidak ditemukan',
            ], 404);
        }

        $banner->image_url = url($banner->image_path);

        return response()->json([
            'success' => true,
            'data' => $banner,
        ]);
    }
}
