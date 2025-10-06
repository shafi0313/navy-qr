<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SeederWriter
{
    protected $tables = [
        'applications',
        'important_applications',
        'application_urls',
        'exam_marks',
        'written_marks',
        'team_f_data',
    ];

    public function generate()
    {
        foreach ($this->tables as $table) {
            $data = DB::table($table)->get()->map(function ($row) {
                return (array) $row;
            })->toArray();

            $this->writeSeeder($table, $data);
        }
    }

    protected function writeSeeder($table, $data)
    {
        $className = Str::studly(Str::singular($table)).'Seeder';
        $filePath = database_path("seeders/{$className}.php");

        $arrayString = var_export($data, true);

        $content = <<<PHP
        <?php

        namespace Database\Seeders;

        use Illuminate\Database\Seeder;
        use Illuminate\Support\Facades\DB;

        class {$className} extends Seeder
        {
            public function run()
            {
                DB::table('{$table}')->insert($arrayString);
            }
        }
        PHP;

        File::put($filePath, $content);
    }
}
