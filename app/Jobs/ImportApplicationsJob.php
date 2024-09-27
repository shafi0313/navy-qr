<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
