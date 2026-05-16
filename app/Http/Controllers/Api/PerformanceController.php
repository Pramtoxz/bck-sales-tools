<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PerformanceController extends Controller
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

        $startDate = $request->query('start_date', date('Y-m-01'));
        $endDate = $request->query('end_date', date('Y-m-t'));
        $limit = $request->query('limit', 20);

        $rankings = DB::connection('pgsql_nms')->select("
            WITH target_summary AS (
                SELECT 
                    ttf.id_flp,
                    tblflp.nama,
                    SUM(ttf.target) as total_target
                FROM \"H1_DOS\".\"tbl_target_flp\" ttf
                LEFT JOIN \"H1_DOS\".\"tblflp\" ON tblflp.no_id = ttf.id_flp
                WHERE EXTRACT(MONTH FROM TO_DATE(ttf.bulan_tahun, 'MM/DD/YYYY')) = EXTRACT(MONTH FROM ?::date)
                  AND EXTRACT(YEAR FROM TO_DATE(ttf.bulan_tahun, 'MM/DD/YYYY')) = EXTRACT(YEAR FROM ?::date)
                GROUP BY ttf.id_flp, tblflp.nama
            ),
            actual_summary AS (
                SELECT 
                    spk.\"id_flp\",
                    COUNT(*) as total_terjual
                FROM \"H1_DOS\".\"fakturpenjualan\" fp
                JOIN \"H1_DOS\".\"salesorder\" so ON so.\"IDSO\" = fp.\"IDSO\"
                JOIN \"H1_DOS\".\"spk\" ON spk.\"IDSpk\" = so.\"IDSPK\"
                WHERE fp.\"TglPenjualan\" BETWEEN ? AND ?
                  AND spk.\"id_flp\" IS NOT NULL
                GROUP BY spk.\"id_flp\"
            )
            SELECT 
                ROW_NUMBER() OVER (ORDER BY 
                    CASE 
                        WHEN ts.total_target > 0 THEN (COALESCE(acs.total_terjual, 0)::float / ts.total_target * 100)
                        ELSE 0 
                    END DESC
                ) as rank,
                ts.id_flp,
                ts.nama,
                ts.total_target,
                COALESCE(acs.total_terjual, 0) as total_terjual,
                CASE 
                    WHEN ts.total_target > 0 THEN ROUND((COALESCE(acs.total_terjual, 0)::float / ts.total_target * 100)::numeric, 2)
                    ELSE 0 
                END as persentase
            FROM target_summary ts
            LEFT JOIN actual_summary acs ON acs.id_flp = ts.id_flp
            ORDER BY persentase DESC
            LIMIT ?
        ", [$startDate, $startDate, $startDate, $endDate, $limit]);

        $myRank = null;
        $leaderboard = [];

        foreach ($rankings as $index => $rank) {
            $rankData = [
                'rank' => (int)$rank->rank,
                'id_flp' => $rank->id_flp,
                'nama' => $rank->nama,
                'total_target' => (int)$rank->total_target,
                'total_terjual' => (int)$rank->total_terjual,
                'persentase' => (float)$rank->persentase,
            ];

            if ($rank->id_flp === $flp->no_id) {
                $myRank = $rankData;
            }

            $leaderboard[] = $rankData;
        }

        if (!$myRank) {
            $myRankQuery = DB::connection('pgsql_nms')->select("
                WITH target_summary AS (
                    SELECT 
                        ttf.id_flp,
                        tblflp.nama,
                        SUM(ttf.target) as total_target
                    FROM \"H1_DOS\".\"tbl_target_flp\" ttf
                    LEFT JOIN \"H1_DOS\".\"tblflp\" ON tblflp.no_id = ttf.id_flp
                    WHERE EXTRACT(MONTH FROM TO_DATE(ttf.bulan_tahun, 'MM/DD/YYYY')) = EXTRACT(MONTH FROM ?::date)
                      AND EXTRACT(YEAR FROM TO_DATE(ttf.bulan_tahun, 'MM/DD/YYYY')) = EXTRACT(YEAR FROM ?::date)
                    GROUP BY ttf.id_flp, tblflp.nama
                ),
                actual_summary AS (
                    SELECT 
                        spk.\"id_flp\",
                        COUNT(*) as total_terjual
                    FROM \"H1_DOS\".\"fakturpenjualan\" fp
                    JOIN \"H1_DOS\".\"salesorder\" so ON so.\"IDSO\" = fp.\"IDSO\"
                    JOIN \"H1_DOS\".\"spk\" ON spk.\"IDSpk\" = so.\"IDSPK\"
                    WHERE fp.\"TglPenjualan\" BETWEEN ? AND ?
                      AND spk.\"id_flp\" IS NOT NULL
                    GROUP BY spk.\"id_flp\"
                ),
                ranked_flp AS (
                    SELECT 
                        ROW_NUMBER() OVER (ORDER BY 
                            CASE 
                                WHEN ts.total_target > 0 THEN (COALESCE(acs.total_terjual, 0)::float / ts.total_target * 100)
                                ELSE 0 
                            END DESC
                        ) as rank,
                        ts.id_flp,
                        ts.nama,
                        ts.total_target,
                        COALESCE(acs.total_terjual, 0) as total_terjual,
                        CASE 
                            WHEN ts.total_target > 0 THEN ROUND((COALESCE(acs.total_terjual, 0)::float / ts.total_target * 100)::numeric, 2)
                            ELSE 0 
                        END as persentase
                    FROM target_summary ts
                    LEFT JOIN actual_summary acs ON acs.id_flp = ts.id_flp
                )
                SELECT * FROM ranked_flp WHERE id_flp = ?
            ", [$startDate, $startDate, $startDate, $endDate, $flp->no_id]);

            if (!empty($myRankQuery)) {
                $rank = $myRankQuery[0];
                $myRank = [
                    'rank' => (int)$rank->rank,
                    'id_flp' => $rank->id_flp,
                    'nama' => $rank->nama,
                    'total_target' => (int)$rank->total_target,
                    'total_terjual' => (int)$rank->total_terjual,
                    'persentase' => (float)$rank->persentase,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'my_rank' => $myRank,
                'leaderboard' => $leaderboard,
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
            ],
        ]);
    }
}
