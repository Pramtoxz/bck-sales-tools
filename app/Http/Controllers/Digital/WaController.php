<?php

namespace App\Http\Controllers\Digital;

use App\Http\Controllers\Controller;
use App\Models\Digital\WaMsgTmp;
use Illuminate\Http\Request;

class WaController extends Controller
{
    // khusus RPA 1// 
    public function getAllData()
    {
        $tanggalSekarang = date("Y-m-d H:i:s");
        $tanggal = date("Y-m-d");
        $kodeDealerRpa1 = ['06732', '06750', '08199'];

        $data = WaMsgTmp::select('no_hp', 'jenis_msg', 'message', 'attachment_path', 'kode_dealer', 'id')
            ->whereNotNull('message')
            ->where('is_proses', true)
            ->whereNull('flag_kirim')
            ->whereIn('kode_dealer', $kodeDealerRpa1)

            ->whereDate("created_at", $tanggal)
            ->where("created_at", "<=", $tanggalSekarang)
            ->orderBy("status", "DESC")
            ->limit(25)
            ->get();
        $tmp = [];

        foreach ($data as $value) {
            $tmp[] = $value['id'];
        }

        $updateDataWa = WaMsgTmp::whereIn('id', $tmp)
            ->update([
                "flag_kirim" => true
            ]);

        return response()->json([
            "code" => 200,
            "status" => true,
            "data" => $data
        ]);
    }


    public function updateData(Request $r)
    {
        try {
            $data = $r->data;
            $kodeDealerRpa1 = ['06732', '06750', '08199'];
            if (!empty($data)) {
                $tmp = [];
                $jumlahDataUpdate = 0;
                foreach ($data as $value) {
                    $tmp[] = [
                        "id" => $value['id_nms'],
                        "status" => $value['status'],
                        "process_time" => $value['process_time']
                    ];
                    WaMsgTmp::where('id', $value['id_nms'])
                        ->whereIn('kode_dealer', $kodeDealerRpa1)
                        ->update([
                            "status" => $value['status'],
                            "process_time" => $value['process_time'],
                            'flag_rpa' => 'RPA1'
                        ]);
                    $jumlahDataUpdate += 1;
                }
                return response()->json([
                    "status" => true,
                    "message" => "Berhasil Update Data Dengan Jumlah Data Update : " . $jumlahDataUpdate
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Data Yang Dikirim Kosong"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => "Gagal Update Data : " . $th->getMessage()
            ]);
        }
    }

    // khusus RPA 2// 
    public function getDataRpa2()
    {
        $tanggalSekarang = date("Y-m-d H:i:s");
        $tanggal = date("Y-m-d");
        $kodeDealerDikecualikan = ['06732', '06750', '08199'];
        $keteranganDiizinkanDealer10874 = [
            'Notifikasi New Leads For Sales People',
            'Notifikasi On Going Overdue Sales People'
        ];

        $data = WaMsgTmp::select('no_hp', 'jenis_msg', 'message', 'attachment_path', 'kode_dealer', 'id')
            ->whereNotNull('message')
            ->where('is_proses', true)
            ->whereNull('flag_kirim')
            ->whereNotIn('kode_dealer', $kodeDealerDikecualikan)
            ->where(function ($query) use ($keteranganDiizinkanDealer10874) {
                $query->where('kode_dealer', '!=', '10874')
                    ->orWhere(function ($query2) use ($keteranganDiizinkanDealer10874) {
                        $query2->where('kode_dealer', '10874')
                            ->whereNotNull('keterangan')
                            ->whereIn('keterangan', $keteranganDiizinkanDealer10874);
                    });
            })
            ->whereDate("created_at", $tanggal)
            ->where("created_at", "<=", $tanggalSekarang)
            ->orderBy("status", "DESC")
            ->limit(25)
            ->get();

        $tmp = [];
        foreach ($data as $value) {
            $tmp[] = $value['id'];
        }

        $updateDataWa = WaMsgTmp::whereIn('id', $tmp)
            ->update([
                "flag_kirim" => true
            ]);

        return response()->json([
            "code" => 200,
            "status" => true,
            "data" => $data
        ]);
    }

    public function updateDataRpa2(Request $r)
    {
        try {
            $data = $r->data;
            $kodeDealerDikecualikan = ['06732', '06750', '08199'];
            $keteranganDiizinkanDealer10874 = [
                'Notifikasi New Leads For Sales People',
                'Notifikasi On Going Overdue Sales People'
            ];

            if (!empty($data)) {
                $tmp = [];
                $jumlahDataUpdate = 0;
                foreach ($data as $value) {
                    $tmp[] = [
                        "id" => $value['id_nms'],
                        "status" => $value['status'],
                        "process_time" => $value['process_time']
                    ];

                    WaMsgTmp::where('id', $value['id_nms'])
                        ->whereNotIn('kode_dealer', $kodeDealerDikecualikan)
                        ->where(function ($query) use ($keteranganDiizinkanDealer10874) {
                            $query->where('kode_dealer', '!=', '10874')
                                ->orWhere(function ($query2) use ($keteranganDiizinkanDealer10874) {
                                    $query2->where('kode_dealer', '10874')
                                        ->whereNotNull('keterangan')
                                        ->whereIn('keterangan', $keteranganDiizinkanDealer10874);
                                });
                        })
                        ->update([
                            "status" => $value['status'],
                            "process_time" => $value['process_time'],
                            'flag_rpa' => 'RPA2'
                        ]);

                    $jumlahDataUpdate += 1;
                }

                return response()->json([
                    "status" => true,
                    "message" => "Berhasil Update Data Dengan Jumlah Data Update : " . $jumlahDataUpdate
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Data Yang Dikirim Kosong"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => "Gagal Update Data : " . $th->getMessage()
            ]);
        }
    }

    public function getReportWa(Request $r)
    {
        $tanggal_awal = $r->i_tgl_awal;
        $tanggal_akhir = $r->i_tgl_akhir;
        $data = WaMsgTmp::select("id", "module", "no_hp", "jenis_msg", "attachment_path", "status", "kode_dealer", "created_at", "updated_at", "process_time", "nama_konsumen", "flag_kirim", "kd_karyawan", "no_mesin", "keterangan", "is_proses", "jenis_kelamin", "id_referensi", "tgl_jatuh_tempo", "km", "kpb", "hari", "jam", "tipe_wa", "no_polisi")
            ->whereNotNull("process_time")
            ->whereDate("created_at", ">=", $tanggal_awal)
            ->whereDate("created_at", "<=", $tanggal_akhir)
            ->orderBy("created_at", "ASC")
            ->get();
        return response()->json($data);
    }

    // khusus Mandiri// 
    public function getDataMandiri()
    {
        $tanggalSekarang = date("Y-m-d H:i:s");
        $tanggal = date("Y-m-d");
        $kodeDealer = ['10874'];
        $keterangandikecualikan = [
            'Notifikasi New Leads For Sales People',
            'Notifikasi On Going Overdue Sales People'
        ];
        $data = WaMsgTmp::select('no_hp', 'jenis_msg', 'message', 'attachment_path', 'kode_dealer', 'id')
            ->whereNotNull('message')
            ->where('is_proses', true)
            ->whereNull('flag_kirim')
            ->whereIn('kode_dealer', $kodeDealer)
            ->where(function ($query) use ($keterangandikecualikan) {
                $query->whereNotIn('keterangan', $keterangandikecualikan)
                    ->orWhereNull('keterangan');
            })
            ->whereDate("created_at", $tanggal)
            ->where("created_at", "<=", $tanggalSekarang)
            ->orderBy("status", "DESC")
            ->limit(25)
            ->get();
        $tmp = [];
        foreach ($data as $value) {
            $tmp[] = $value['id'];
        }
        $updateDataWa = WaMsgTmp::whereIn('id', $tmp)
            ->update([
                "flag_kirim" => true
            ]);
        return response()->json([
            "code" => 200,
            "status" => true,
            "data" => $data
        ]);
    }

    public function updateDataMandiri(Request $r)
    {
        try {
            $data = $r->data;
            $kodeDealer = ['10874'];
            if (!empty($data)) {
                $tmp = [];
                $jumlahDataUpdate = 0;
                foreach ($data as $value) {
                    $tmp[] = [
                        "id" => $value['id_nms'],
                        "status" => $value['status'],
                        "process_time" => $value['process_time']
                    ];
                    WaMsgTmp::where('id', $value['id_nms'])
                        ->whereIn('kode_dealer', $kodeDealer)
                        ->update([
                            "status" => $value['status'],
                            "process_time" => $value['process_time'],
                            'flag_rpa' => 'RPA1'
                        ]);
                    $jumlahDataUpdate += 1;
                }
                return response()->json([
                    "status" => true,
                    "message" => "Berhasil Update Data Dengan Jumlah Data Update : " . $jumlahDataUpdate
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Data Yang Dikirim Kosong"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => "Gagal Update Data : " . $th->getMessage()
            ]);
        }
    }
}
