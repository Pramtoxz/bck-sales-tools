<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportAbsensiExport implements FromCollection,WithHeadings,ShouldAutoSize, WithCustomStartCell, WithEvents, WithStyles
{
    protected $data;
    protected $time;

    public function __construct($data,$time)
    {
        $this->data = $data;
        $this->time = $time;
    }

    public function collection(){
        $tmp = [];
        $i = 1;
        foreach($this->data as $value){
            $alfa = max(0, $value['jumlah_hari_kerja'] - $value['jumlah_kehadiran']);
            $tmp[] = [
                $i++,
                $value['nama_karyawan'],
                (string) $value['jumlah_hari_kerja'],
                (string) $value['jumlah_kehadiran'],
                (string) $value['jumlah_tepat_waktu'],
                (string) $value['jumlah_telat'],
                (string) $value['jumlah_cuti'],
                (string) $value['jumlah_sakit'],
                (string) $value['jumlah_supervisi'],
                (string) $alfa
            ];
        }
        return collect($tmp);
    }

    public function headings(): array
    {
        return ["No","Nama","Hari Kerja","Kehadrian","On Time","Telat","Cuti","Sakit","Supervisi","Alfa"];
    }

    public function startCell(): string
    {
        return 'A5'; // Data (headings and rows) will start here
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $event->sheet->setCellValue('A2', 'Daftar Hadir Karyawan');
                $event->sheet->mergeCells('A2:B2');
                $event->sheet->setCellValue('A3', date("M-Y",strtotime($this->time)));
                $event->sheet->mergeCells('A3:B3');
            }
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply font size to entire column A
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14); // Adjust size as needed
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14); // Adjust size as needed

        // Count the number of rows (including headings)
        $rowCount = count($this->data) + 1 + 4;

        // Apply borders to range A1:C{rowCount}
        $sheet->getStyle("A5:J$rowCount")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        return [];
    }
}
