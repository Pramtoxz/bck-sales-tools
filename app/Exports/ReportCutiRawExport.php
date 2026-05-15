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
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ReportCutiRawExport implements FromCollection,WithHeadings,ShouldAutoSize, WithCustomStartCell, WithEvents, WithStyles
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
            $tmp[] = [
                $i++,
                $value['nama_lengkap'],
                $value['nama_jabatan'],
                $value['deskripsi'],
                $value['jenis_cuti'],
                $value['tanggal_cuti'],
                $value['alasan']
            ];
        }
        return collect($tmp);
    }

    public function headings(): array
    {
        return ["No","Nama Karyawan","Jabatan","Departement","Jenis Cuti","Tanggal Cuti","Alasan"];
    }

    public function startCell(): string
    {
        return 'A5'; // Data (headings and rows) will start here
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $headings = $this->headings();
                $lastColumn = Coordinate::stringFromColumnIndex(count($headings));

            
                $event->sheet->setCellValue('A2', 'Data Raw Cuti Karyawan');
                $event->sheet->mergeCells('A2:' . $lastColumn . '2');
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal('center')->setVertical('center');
                $event->sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);

                $tanggalRange = $this->time['tanggal_awal'] . ' s/d ' . $this->time['tanggal_akhir'];
                $event->sheet->setCellValue('A3', $tanggalRange);
                $event->sheet->mergeCells('A3:' . $lastColumn . '3');
                $event->sheet->getStyle('A3')->getAlignment()->setHorizontal('center')->setVertical('center');
                $event->sheet->getStyle('A3')->getFont()->setItalic(true);
            },
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
        $sheet->getStyle("A5:G$rowCount")->applyFromArray([
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
