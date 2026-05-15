<?php

namespace App\Exports;

use App\Models\Soal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;


class TemplateSoalSheetSoal implements WithTitle,WithHeadings
{
    public function title(): string
    {
         return 'Soal';
    }
    public function headings(): array
    {
        return ["No","Soal","Option","Deskripsi","Jawaban"];
    }

}
