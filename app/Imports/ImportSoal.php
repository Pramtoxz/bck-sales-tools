<?php

namespace App\Imports;

use App\Models\Soal;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportSoal implements WithStartRow,ToCollection
{
  
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        return [];
    }
    
}
