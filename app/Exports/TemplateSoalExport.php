<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class TemplateSoalExport implements FromCollection,WithHeadings
{
    public function collection(){

        $tmp[0] = ["1","Apa yang dimaksud dengan Human Resource Development (HRD)?","a","Proses perekrutan dan seleksi karyawan","b"];
        $tmp[1] = ["","","b","Proses perencanaan karir dan pengembangan individu dalam organisasi",""];
        $tmp[2] = ["","","c","Pengelolaan kompensasi dan tunjangan karyawan",""];
        $tmp[3] = ["","","d","Pengawasan kinerja dan disiplin karyawan",""];
        return collect($tmp);
    }

    public function headings(): array
    {
        return ["No","Soal","Option","Deskripsi","Jawaban"];
    }
}
