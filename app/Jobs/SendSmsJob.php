<?php

namespace App\Jobs;

use App\Services\SMSService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $mobile;
    protected $msg;
    protected $type;
    /**
     * Create a new job instance.
     */
    public function __construct($user_id, $mobile, $msg, $type)
    {
        $this->user_id = $user_id;
        $this->mobile = $mobile;
        $this->msg = $msg;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sendSms($this->mobile, $this->msg);        
        SMSService::store($this->user_id, $this->mobile, $this->msg, $this->type);
    }
}
