<?php

namespace App\Exports;

use App\Traits\EnclTrait;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Encl1Export implements FromArray, WithHeadings, WithTitle, WithStyles
{
    use EnclTrait;

    public function array(): array
    {
        $applications = $this->encl1();
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
                str_replace('English : ', '', $app->ssc_english ?? ''),
                str_replace('Math : ', '', $app->ssc_math ?? ''),
                str_replace('Physics : ', '', $app->ssc_physics ?? ''),
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
        // We’ll build multi-row headings
        return [
            ['CONFIDENTIAL'], // row 1
            ['NOMINAL LIST OF DEUC SAILORS - B-' . now()->year . ' BATCH'], // row 2
            ['CENTER: BNS DHAKA, KHILKHET, DHAKA'], // row 3
            [], // blank row 4
            [ // row 5 - first header row
                'Ser',
                'District',
                'Roll No',
                'Local No',
                'Name (English & Bangla)',
                'Rank (As Per Branch Seniority)',
                'GPA (SSC)',
                'Height (Inch)',
                'SSC Result', '', '', // merged later
                'Mobile No',
                'HSC Pass', '', // merged later
                'Documents to be Submitted to BNS SHER-E-BANGLA'
            ],
            [ // row 6 - second header row
                '', '', '', '', '', '', '', '',
                'Eng', 'Math', 'Phy',
                '',
                'Yes/No', 'GPA (If Applicable)',
                ''
            ],
        ];
    }

    public function title(): string
    {
        return 'Encl1';
    }

    public function styles(Worksheet $sheet)
    {
        // Merge title rows
        $sheet->mergeCells('A1:O1');
        $sheet->mergeCells('A2:O2');
        $sheet->mergeCells('A3:O3');

        // Merge parent headers with "rowspan" effect
        $sheet->mergeCells('A5:A6'); // Ser
        $sheet->mergeCells('B5:B6'); // District
        $sheet->mergeCells('C5:C6'); // Roll No
        $sheet->mergeCells('D5:D6'); // Local No
        $sheet->mergeCells('E5:E6'); // Name
        $sheet->mergeCells('F5:F6'); // Rank
        $sheet->mergeCells('G5:G6'); // GPA SSC
        $sheet->mergeCells('H5:H6'); // Height
        $sheet->mergeCells('I5:K5'); // SSC Result (3 cols)
        $sheet->mergeCells('L5:L6'); // Mobile
        $sheet->mergeCells('M5:N5'); // HSC Pass (2 cols)
        $sheet->mergeCells('O5:O6'); // Documents

        // Title styling
        $sheet->getStyle('A1:O3')->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle('A1:O3')->getFont()->setBold(true);

        // Header styling
        $sheet->getStyle('A5:O6')->getFont()->setBold(true);
        $sheet->getStyle('A5:O6')->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getRowDimension(5)->setRowHeight(25);
        $sheet->getRowDimension(6)->setRowHeight(20);

        // Borders
        $sheet->getStyle('A5:O6')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [];
    }
}
