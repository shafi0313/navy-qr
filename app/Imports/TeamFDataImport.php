<?php

namespace App\Imports;

use App\Models\TeamFData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeamFDataImport implements ToModel, WithHeadingRow
{
    /**
     * @param  Collection  $collection
     */
    public function model(array $row)
    {
        // dd($row);
        return TeamFData::create([
            'serial_no' => $row['roll_no'] ?? null,
        ]);
    }
}
