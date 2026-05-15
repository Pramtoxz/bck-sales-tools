<?php

namespace App\Exports;

use App\Models\Soal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;


class TemplateSoalSheetJawaban implements WithTitle,WithHeadings
{
    public function title(): string
    {
         return 'Jawaban';
    }
    public function headings(): array
    {
        return ["No","Jawaban"];
    }
}
