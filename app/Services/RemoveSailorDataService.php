<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RemoveSailorDataService
{
    protected $tables = [
        'personal_access_tokens',
        'exam_marks',
        'written_marks',
        'team_f_data',
        'application_urls',
        'important_applications',
        'applications',
    ];

    public function remove()
    {
        // Foreign key check সাময়িকভাবে বন্ধ করা
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($this->tables as $table) {
            DB::table($table)->truncate();
        }

        // আবার চালু করা
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return '✅ All sailor data truncated successfully (forced)!';
    }
}
