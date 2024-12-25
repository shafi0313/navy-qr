<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ImportApplicationsJob implements ShouldQueue
{
    use Queueable;

    private $batchData;

    /**
     * Create a new job instance.
     */
    public function __construct($batchData)
    {
        $this->batchData = $batchData;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        DB::table('applications')->insert($this->batchData);
    }
}
