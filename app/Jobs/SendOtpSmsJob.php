<?php

namespace App\Jobs;

use App\Services\SMSService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOtpSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $mobile;
    protected $otp;
    /**
     * Create a new job instance.
     */
    public function __construct($user_id, $mobile, $otp)
    {
        $this->user_id = $user_id;
        $this->mobile = $mobile;
        $this->otp = $otp;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sendOtpViaSms($this->mobile, $this->otp);
        SMSService::store($this->user_id, $this->mobile, $this->otp, 'OTP');
    }
}
