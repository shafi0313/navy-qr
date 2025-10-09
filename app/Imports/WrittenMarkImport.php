<?php

namespace App\Imports;

use App\Models\WrittenMark;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use RealRashid\SweetAlert\Facades\Alert;

class WrittenMarkImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $validatedData = [];
        $errors = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // Heading row is 1, data starts from 2
            $data = $row->toArray();

            // Normalize headers
            $normalized = [];
            foreach ($data as $key => $value) {
                $key = trim(Str::lower($key));
                $normalized[$key] = $value;
            }

            // Required columns
            $required = ['from_number', 'bangla', 'english', 'math', 'science', 'gk'];

            foreach ($required as $field) {
                if (! array_key_exists($field, $normalized)) {
                    $errors[] = "Row {$rowNumber}: Missing column '{$field}'";

                    continue;
                }

                if (! is_numeric($normalized[$field]) || intval($normalized[$field]) != $normalized[$field]) {
                    $errors[] = "Row {$rowNumber}: Invalid (non-integer) value in '{$field}'";

                    continue;
                }

                if ($field !== 'from_number' && ((int) $normalized[$field]) % 2 !== 0) {
                    $errors[] = "Row {$rowNumber}: Value in '{$field}' must be even";
                }
            }

            // Save validated row only if no error for this row
            if (! in_array(true, array_map(fn ($e) => str_starts_with($e, "Row {$rowNumber}"), $errors))) {
                $validatedData[] = [
                    'serial_no' => (int) $normalized['from_number'],
                    'bangla' => (int) $normalized['bangla'],
                    'english' => (int) $normalized['english'],
                    'math' => (int) $normalized['math'],
                    'science' => (int) $normalized['science'],
                    'general_knowledge' => (int) $normalized['gk'],
                ];
            }
        }

        // If any errors, throw combined exception
        if (! empty($errors)) {
            throw new Exception(implode("\n", $errors));
            // Alert::error('Import failed: '.implode('<br>', $errors))->html()->persistent('OK');
            // return back();
        }

        // Insert all validated data
        WrittenMark::insert($validatedData);
    }
}

// use App\Models\WrittenMark;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;

// class WrittenMarkImport implements ToModel, WithHeadingRow
// {
//     /**
//      * @param  Collection  $collection
//      */
//     public function model(array $row)
//     {
//         // dd($row);
//         return WrittenMark::create([
//             'serial_no' => $row['from_number'] ?? null,
//             'bangla' => $row['bangla'] ?? null,
//             'english' => $row['english'] ?? null,
//             'math' => $row['math'] ?? null,
//             'science' => $row['science'] ?? null,
//             'general_knowledge' => $row['gk'] ?? null,
//         ]);
//     }
// }
