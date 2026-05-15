<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReportCutiExport implements FromArray, WithHeadings, WithCustomStartCell, WithEvents
{
    protected $cuti;
    protected $filter;
    protected $mergecells = [];

    public function __construct($cuti, $filter)
    {
        $this->cuti = $cuti;
        $this->filter = $filter;
    }

    public function array(): array
    {
        $rows = [];
        $rowNumber = 6;
        $no = 1;

        foreach ($this->cuti as $nama => $cutis) {
            $rowspan = count($cutis);
            $first = true;

            foreach ($cutis as $cuti) {
                $row = [];

                if ($first) {
                    $row[] = $no;
                    $row[] = $nama;
                    $this->mergecells[] = [
                        'columns' => ['A', 'B'],
                        'startRow' => $rowNumber,
                        'endRow' => $rowNumber + $rowspan - 1,
                    ];
                    $first = false;
                } else {
                    $row[] = '';
                    $row[] = '';
                }

                $row[] = $cuti['jenis_cuti'];
                $row[] = $cuti['tanggal_cuti'];

                if (!empty($this->filter['kode_karyawan'])) {
                    $row[] = $cuti['alasan'] ?? '-';
                }

                $rows[] = $row;
                $rowNumber++;
            }

            $no++;
        }

        return $rows;
    }

    public function headings(): array
    {
        $headings = ['No', 'Nama Karyawan', 'Jenis Cuti', 'Tanggal Cuti'];

        if (!empty($this->filter['kode_karyawan'])) {
            $headings[] = 'Alasan Cuti';
        }

        return $headings;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $headings = $this->headings();
                $lastColumn = Coordinate::stringFromColumnIndex(count($headings));

            
                $event->sheet->setCellValue('A2', 'Report Cuti Karyawan');
                $event->sheet->mergeCells('A2:' . $lastColumn . '2');
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal('center')->setVertical('center');
                $event->sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);

                $tanggalRange = $this->filter['tanggal_awal'] . ' s/d ' . $this->filter['tanggal_akhir'];
                $event->sheet->setCellValue('A3', $tanggalRange);
                $event->sheet->mergeCells('A3:' . $lastColumn . '3');
                $event->sheet->getStyle('A3')->getAlignment()->setHorizontal('center')->setVertical('center');
                $event->sheet->getStyle('A3')->getFont()->setItalic(true);
            },

            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

        
                foreach ($this->mergecells as $merge) {
                    $startA = $merge['columns'][0] . $merge['startRow'];
                    $endA = $merge['columns'][0] . $merge['endRow'];
                    $sheet->mergeCells("$startA:$endA");

                    $startB = $merge['columns'][1] . $merge['startRow'];
                    $endB = $merge['columns'][1] . $merge['endRow'];
                    $sheet->mergeCells("$startB:$endB");

                    $sheet->getStyle("$startA:$endA")->getAlignment()->setVertical('center')->setHorizontal('center');
                    $sheet->getStyle("$startB:$endB")->getAlignment()->setVertical('center')->setHorizontal('center');
                }

    
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                $sheet->getStyle('A5:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
            }
        ];
    }
}



// namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromArray;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\WithEvents;
// use Maatwebsite\Excel\Events\AfterSheet;

// class ReportCutiExport implements FromArray, WithHeadings, WithEvents
// {
//     protected $cuti;
//     protected $filter;

//     protected $mergecells = [];

//     public function __construct($cuti, $filter)
//     {
//         $this->cuti = $cuti;
//         $this->filter = $filter;
//     }

//     public function array(): array
//     {
//         $rows = [];
//         $rowNumber = 2; 
//         $no = 1;

//         foreach ($this->cuti as $nama => $cutis) {
//             $rowspan = count($cutis);
//             $first = true;

//             foreach ($cutis as $cuti) {
//                 $row = [];

//                 if ($first) {
//                     $row[] = $no;
//                     $row[] = $nama;
//                     $this->mergecells[] = [
//                         'columns' => ['A', 'B'],
//                         'startRow' => $rowNumber,
//                         'endRow' => $rowNumber + $rowspan - 1,
//                     ];
//                     $first = false;
//                 } else {
//                     $row[] = '';
//                     $row[] = '';
//                 }

//                 $row[] = $cuti['jenis_cuti'];
//                 $row[] = $cuti['tanggal_cuti'];

//                 if (!empty($this->filter['kode_karyawan'])) {
//                     $row[] = $cuti['alasan'] ?? '-';
//                 }

//                 $rows[] = $row;
//                 $rowNumber++;
//             }

//             $no++;
//         }

//         return $rows;
//     }

//     public function headings(): array
//     {
//         if (empty($this->filter['kode_karyawan'])) {
//             return ['No', 'Nama Karyawan', 'Jenis Cuti', 'Tanggal Cuti'];
//         } else {
//             return ['No', 'Nama Karyawan', 'Jenis Cuti', 'Tanggal Cuti', 'Alasan Cuti'];
//         }
//     }


//     public function registerEvents(): array
//     {
//         return [

//             AfterSheet::class => function(AfterSheet $event) {
//                 foreach ($this->mergecells as $merge) {
//                     $start = $merge['columns'][0] . $merge['startRow'];
//                     $end = $merge['columns'][0] . $merge['endRow'];
//                     $event->sheet->mergeCells("$start:$end");

//                     $start2 = $merge['columns'][1] . $merge['startRow'];
//                     $end2 = $merge['columns'][1] . $merge['endRow'];
//                     $event->sheet->mergeCells("$start2:$end2");

//                     $event->sheet->getStyle("$start:$end")->getAlignment()->setVertical('center')->setHorizontal('center');
//                     $event->sheet->getStyle("$start2:$end2")->getAlignment()->setVertical('center')->setHorizontal('center');
//                 }
        

//                 $event->sheet->getStyle('A1:' . $event->sheet->getHighestColumn() . $event->sheet->getHighestRow())
//                     ->applyFromArray([
//                         'borders' => [
//                             'allBorders' => [
//                                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
//                                 'color' => ['argb' => 'FF000000'],
//                             ],
//                         ],
//                     ]);
//             }
//         ];
//     }
// }
