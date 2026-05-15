<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KaryawanExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Karyawan::join('public.users','users.kd_karyawan','karyawan.kd_karyawan')->select("nama_lengkap", "nama_panggilan", "jenis_kelamin","kd_agama","kd_status","jumlah_anak","no_hp","kd_jabatan","no_ktp","no_ketenagakerjaan","tempat_lahir","tanggal_lahir","kd_pendidikan","alamat","nama_pasangan","nama_ibu","tanggal_bergabung","kd_departement","no_kk","no_kesehatan","kode_jabatan_wlk","users.email")->limit(1)->get();
    }

    public function headings(): array
    {
        return ["Nama Lengkap","Nama Panggilan","Jenis Kelamin","Kode Agama","Kode Status","Jumlah Anak","No HP","Kode Jabatan","No KTP","No Ketenagakerjaan","Kota Tempat Lahir","Tanggal Lahir","Kode Pendidikan","Alamat","Nama Pasangan","Nama IBU","Tanggal Bergabung","Kode Departement","No KK","No Kesehatan","Kode Jabatan WLK","Email"];
    }
}
