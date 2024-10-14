<?php

namespace App\Imports;

use App\Models\WrittenMark;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WrittenMarkImport implements ToModel, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        // dd($row);
        return WrittenMark::create([
            'serial_no'         => $row['from_number'] ?? null,
            'bangla'            => $row['bangla'] ?? null,
            'english'           => $row['english'] ?? null,
            'math'              => $row['math'] ?? null,
            'science'           => $row['science'] ?? null,
            'general_knowledge' => $row['gk'] ?? null,
        ]);
    }
}
