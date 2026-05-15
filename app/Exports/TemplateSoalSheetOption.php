<?php

namespace App\Exports;

use App\Models\Soal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;


class TemplateSoalSheetOption implements WithTitle,WithHeadings
{
    public function title(): string
    {
         return 'Option';
    }
    public function headings(): array
    {
        return ["No","Option","Deskripsi"];
    }
}
