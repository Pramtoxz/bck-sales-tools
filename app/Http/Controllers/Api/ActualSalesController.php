<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ActualSalesController extends Controller
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

        $perPage = $request->query('per_page', 15);
        $bulan = $request->query('bulan', date('n'));
        $tahun = $request->query('tahun', date('Y'));

        $query = DB::connection('pgsql_nms')
            ->table('H1_DOS.fakturpenjualan as fp')
            ->select(
                'fp.IDFakturPenjualan',
                'fp.TglPenjualan',
                'fp.Tdpp',
                'fp.Tppn',
                'fp.Tbbn',
                'fp.Tamount',
                'fp.status',
                'so.IDSPK',
                'spk.IDCustomer',
                'mastercustomer.NamaCustomer',
                'mastercustomer.NoHp',
                'SpkDetail.fk_tipe',
                'SpkDetail.fk_warna',
                'SpkDetail.DescMotorMKT',
                'SpkDetail.DescWarnaMotor',
                'SpkDetail.harga_unit',
                'spk.IDJenisPembayaran',
                'setupjenispembayaran.JenisPembayaran'
            )
            ->join('H1_DOS.salesorder as so', 'so.IDSO', '=', 'fp.IDSO')
            ->join('H1_DOS.spk', 'spk.IDSpk', '=', 'so.IDSPK')
            ->leftJoin('H1_DOS.mastercustomer', 'mastercustomer.IDCustomer', '=', 'spk.IDCustomer')
            ->leftJoin('H1_DOS.SpkDetail', 'SpkDetail.IdSPK', '=', 'spk.IDSpk')
            ->leftJoin('H1_DOS.setupjenispembayaran', 'setupjenispembayaran.IDJenisPembayaran', '=', 'spk.IDJenisPembayaran')
            ->where('spk.id_flp', $flp->no_id)
            ->whereRaw('EXTRACT(MONTH FROM fp."TglPenjualan") = ?', [$bulan])
            ->whereRaw('EXTRACT(YEAR FROM fp."TglPenjualan") = ?', [$tahun])
            ->orderBy('fp.TglPenjualan', 'desc');

        $sales = $query->paginate($perPage);

        $targetData = DB::connection('pgsql_nms')
            ->table('H1_DOS.tbl_target_flp')
            ->where('id_flp', $flp->no_id)
            ->whereRaw("TO_DATE(bulan_tahun, 'MM/DD/YYYY') = TO_DATE(?, 'MM/DD/YYYY')", [
                sprintf('%02d/01/%d', $bulan, $tahun)
            ])
            ->selectRaw('SUM(target) as total_target')
            ->first();

        $actualData = DB::connection('pgsql_nms')
            ->table('H1_DOS.fakturpenjualan as fp')
            ->join('H1_DOS.salesorder as so', 'so.IDSO', '=', 'fp.IDSO')
            ->join('H1_DOS.spk', 'spk.IDSpk', '=', 'so.IDSPK')
            ->where('spk.id_flp', $flp->no_id)
            ->whereRaw('EXTRACT(MONTH FROM fp."TglPenjualan") = ?', [$bulan])
            ->whereRaw('EXTRACT(YEAR FROM fp."TglPenjualan") = ?', [$tahun])
            ->selectRaw('COUNT(*) as total_actual')
            ->first();

        $target = (int) ($targetData->total_target ?? 0);
        $actual = (int) $actualData->total_actual;
        $persentase = $target > 0 ? round(($actual / $target) * 100, 2) : 0;

        return response()->json([
            'success' => true,
            'data' => $sales->items(),
            'summary' => [
                'bulan' => (int) $bulan,
                'tahun' => (int) $tahun,
                'target' => $target,
                'actual' => $actual,
                'persentase' => $persentase,
            ],
            'meta' => [
                'current_page' => $sales->currentPage(),
                'last_page' => $sales->lastPage(),
                'per_page' => $sales->perPage(),
                'total' => $sales->total(),
            ],
        ]);
    }
}
