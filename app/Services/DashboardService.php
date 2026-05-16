<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardService
{
    public function getDealerInfo($idFlp)
    {
        $flp = DB::connection('pgsql_nms')
            ->table('H1_DOS.tblflp')
            ->where('no_id', $idFlp)
            ->first();

        if (!$flp) {
            return null;
        }

        $dealer = DB::connection('pgsql_nms')
            ->table('H1_DOS.masterdealer')
            ->where('KodeDealer', $flp->kd_dlr)
            ->first();

        return [
            'dealer_code' => $flp->kd_dlr,
            'dealer_name' => $dealer->NamaDealer ?? 'Unknown',
        ];
    }

    public function getPencapaianHariIni($idFlp, $dealerCode)
    {
        $today = Carbon::today()->format('Y-m-d');
        $startOfMonth = Carbon::today()->startOfMonth()->format('Y-m-d');

        $targetBulanIni = DB::connection('pgsql_nms')
            ->table('H1_DOS.tbl_target_flp')
            ->where('id_flp', $idFlp)
            ->where('fk_dealer', $dealerCode)
            ->whereRaw("SUBSTRING(bulan_tahun, 4) = ?", [Carbon::today()->format('m/Y')])
            ->sum('target');

        $terjualHariIni = DB::connection('pgsql_nms')
            ->table('H1_DOS.stokunit as s')
            ->join('H1_DOS.fakturpenjualan as f', 's.no_so_dlr', '=', 'f.IDSO')
            ->where('s.id_sales_people', $idFlp)
            ->where('f.fk_dealer', $dealerCode)
            ->whereDate('f.TglPenjualan', $today)
            ->count();

        $terjualBulanIni = DB::connection('pgsql_nms')
            ->table('H1_DOS.stokunit as s')
            ->join('H1_DOS.fakturpenjualan as f', 's.no_so_dlr', '=', 'f.IDSO')
            ->where('s.id_sales_people', $idFlp)
            ->where('f.fk_dealer', $dealerCode)
            ->whereBetween('f.TglPenjualan', [$startOfMonth, $today])
            ->count();

        $persentase = $targetBulanIni > 0 
            ? round(($terjualBulanIni / $targetBulanIni) * 100, 2) 
            : 0;

        return [
            'pencapaian_persen' => $persentase,
            'terjual_hari_ini' => $terjualHariIni,
            'terjual_bulan_ini' => $terjualBulanIni,
            'target_bulan_ini' => $targetBulanIni,
        ];
    }

    public function getSummaryMetrics($idFlp, $dealerCode)
    {
        $totalSpk = DB::connection('pgsql_nms')
            ->table('H1_DOS.spk')
            ->where('id_flp', $idFlp)
            ->whereMonth('TglSPK', Carbon::today()->month)
            ->count();

        $totalIndent = DB::connection('pgsql_nms')
            ->table('H1_DOS.indent')
            ->where('fk_dealer', $dealerCode)
            ->where('status_indent', 2)
            ->where('status_approval', 't')
            ->count();

        return [
            'total_spk' => $totalSpk,
            'total_indent' => $totalIndent,
        ];
    }
}
