<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ApplicantsExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        // Get data grouped by district and designation
        $applicants = Application::selectRaw('eligible_district, candidate_designation, COUNT(id) as total')
            ->whereNotNull('scanned_at')
            ->groupBy('eligible_district', 'candidate_designation')
            ->get();

        $districts = $applicants->groupBy('eligible_district');
        $designations = $applicants->groupBy('candidate_designation')->keys();

        $data = [];

        // Table rows
        foreach ($districts as $district => $applicantGroup) {
            $districtTotal = $applicantGroup->sum('total');
            $row = [$district, $districtTotal];

            foreach ($designations as $designation) {
                $row[] = $applicantGroup->firstWhere('candidate_designation', $designation)->total ?? 0;
            }
            $data[] = $row;
        }

        // Total row
        $totalRow = ["Total", $applicants->sum('total')];
        foreach ($designations as $designation) {
            $totalRow[] = $applicants->where('candidate_designation', $designation)->sum('total');
        }
        $data[] = $totalRow;

        return $data;
    }

    public function headings(): array
    {
        // Get unique designations
        $designations = Application::select('candidate_designation')->distinct()->pluck('candidate_designation');

        return array_merge(["District", "Total"], $designations->toArray());
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header row bold
        ];
    }
}
