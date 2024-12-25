<?php

namespace App\Imports;

use App\Models\ImportantApplication;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportantApplicationImport implements ToModel, WithHeadingRow
{
    /**
     * @param  Collection  $collection
     */
    public function model(array $row)
    {
        // dd($row);
        return ImportantApplication::create([
            'serial_no' => $row['roll'] ?? null,
        ]);
    }
}
