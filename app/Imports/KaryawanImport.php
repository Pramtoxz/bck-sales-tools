<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Helper\Helper;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class KaryawanImport implements WithStartRow,ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        return [];
    }

    
}
