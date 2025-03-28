<?php

namespace App\Traits;

use App\Jobs\SendSmsJob;

trait SmsTrait
{
    protected function fail($phone, $type)
    {
        // if (env('APP_DEBUG') == false) {
            $msg = 'বাংলাদেশ নৌবাহিনীর প্রতি আগ্রহের জন্য ধন্যবাদ। প্রতিযোগিতামূলক বাছাই প্রক্রিয়ায় আপনাকে নির্বাচন করা সম্ভব হয়নি। ভবিষ্যতের জন্য শুভকামনা। -বাংলাদেশ নৌবাহিনী';
            SendSmsJob::dispatch(user()->id, $phone, $msg, $type)->onQueue('default');
        // }
    }
}
