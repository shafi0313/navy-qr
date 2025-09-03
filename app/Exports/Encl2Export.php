<?php

namespace App\Exports;

use App\Traits\EnclTrait;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Encl2Export implements FromArray, WithHeadings, WithTitle, WithStyles
{
    use EnclTrait;

    public function array(): array
    {
        $applications = $this->encl2(); // Make sure EnclTrait has encl2()
        $rows = [];

        $i = 1;
        foreach ($applications as $app) {
            $rows[] = [
                $i++,
                ucfirst($app->eligible_district),
                $app->serial_no,
                '', // Local No
                $app->name,
                config('var.brCodes')[$app->br_code] ?? '',
                $app->ssc_gpa,
                $app->height,
                $app->current_phone,
                $app->hsc_dip_group ? 'Yes' : 'No',
                $app->hsc_gpa ?? '',
                '', // Documents column
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            ['CONFIDENTIAL'], // row 1
            ['NOMINAL LIST OF SAILORS (EXCEPT DEUC) - B-' . now()->year . ' BATCH'], // row 2
            ['CENTER: BNS DHAKA, KHILKHET, DHAKA'], // row 3
            [], // blank row
            [ // row 5 (first header row)
                'Ser',
                'District',
                'Roll No',
                'Local No',
                'Name (English & Bangla)',
                'Rank (As Per Branch Seniority)',
                'GPA (SSC)',
                'Height (Inch)',
                'Mobile No',
                'HSC Pass', '', // merged later
                'Documents to be Submitted to BNS SHER-E-BANGLA'
            ],
            [ // row 6 (second header row)
                '', '', '', '', '', '', '', '', '',
                'Yes/No',
                'GPA (If Applicable)',
                ''
            ],
        ];
    }

    public function title(): string
    {
        return 'Encl2';
    }

    public function styles(Worksheet $sheet)
    {
        // Merge title rows
        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');
        $sheet->mergeCells('A3:L3');

        // Merge headers (simulate rowspan/colspan)
        $sheet->mergeCells('A5:A6'); // Ser
        $sheet->mergeCells('B5:B6'); // District
        $sheet->mergeCells('C5:C6'); // Roll No
        $sheet->mergeCells('D5:D6'); // Local No
        $sheet->mergeCells('E5:E6'); // Name
        $sheet->mergeCells('F5:F6'); // Rank
        $sheet->mergeCells('G5:G6'); // GPA SSC
        $sheet->mergeCells('H5:H6'); // Height
        $sheet->mergeCells('I5:I6'); // Mobile No
        $sheet->mergeCells('J5:K5'); // HSC Pass
        $sheet->mergeCells('L5:L6'); // Documents

        // Style titles
        $sheet->getStyle('A1:L3')->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle('A1:L3')->getFont()->setBold(true);

        // Style header
        $sheet->getStyle('A5:L6')->getFont()->setBold(true);
        $sheet->getStyle('A5:L6')->getAlignment()->setHorizontal('center')->setVertical('center');

        // Borders
        $sheet->getStyle('A5:L6')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [];
    }
}
