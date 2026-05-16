<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class IndentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $flp = $user->flp;

        if (!$flp) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terdaftar sebagai FLP',
            ], 403);
        }

        $query = "
            SELECT 
                mgm.\"DeskripsiType\",
                indent.\"IDCustomer\", 
                mastercustomer.\"NamaCustomer\",
                CASE 
                    WHEN \"NamaLeasing\" IS NULL OR \"NamaLeasing\" = '' THEN 'CASH'
                    ELSE \"NamaLeasing\"
                END AS \"NamaLeasing\",
                CONCAT(\"Desc_Tipe\", '-', \"SpkDetail\".\"fk_warna\") AS \"kode_item\",
                w.warna
            FROM \"H1_DOS\".\"indent\"
            LEFT JOIN \"H1_DOS\".\"SpkDetail\" ON \"SpkDetail\".\"IdSPK\" = \"indent\".\"IDSpk\"
            LEFT JOIN \"H1_DOS\".spk ON spk.\"IDSpk\" = \"indent\".\"IDSpk\"
            LEFT JOIN \"H1_DOS\".mastercustomer ON mastercustomer.\"IDCustomer\" = \"indent\".\"IDCustomer\"
            LEFT JOIN \"H1_DOS\".mastergroupsegmenmotor mgm ON mgm.\"KodeType\" = \"indent\".\"Desc_Tipe\"
            LEFT JOIN public.tblwarna w ON w.kd_warna = \"SpkDetail\".\"fk_warna\"
            WHERE \"indent\".\"status_indent\" = 2 
              AND \"indent\".\"status_approval\" = 't'
              AND \"indent\".\"fk_dealer\" = ?
            ORDER BY mgm.\"DeskripsiType\", mastercustomer.\"NamaCustomer\"
        ";

        $results = DB::connection('pgsql_nms')->select($query, [$flp->kd_dlr]);

        if (empty($results)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data indent aktif',
            ], 404);
        }

        $grouped = [];
        foreach ($results as $row) {
            $grouped[$row->DeskripsiType][] = [
                'customer_id' => $row->IDCustomer,
                'customer_name' => $row->NamaCustomer,
                'leasing' => $row->NamaLeasing,
                'kode_item' => $row->kode_item,
                'warna' => $row->warna,
            ];
        }

        $data = [];
        foreach ($grouped as $tipe => $items) {
            $data[] = [
                'tipe' => $tipe,
                'items' => $items,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'dealer' => [
                    'kd_dlr' => $flp->kd_dlr,
                ],
                'indent' => $data,
            ],
        ]);
    }
}
