<?php

namespace App\Services;

use App\Models\SMS;

class SMSService
{
    /**
     * Store SMS data in the database.
     *
     * @param int|null $userId
     * @param string $phone
     * @param string $message
     * @param string|null $type
     * @return SMS
     */
    public static function store(?int $userId, string $phone, string $message, ?string $type = null): SMS
    {
        return SMS::create([
            'user_id' => $userId ?? user()->id,
            'phone'   => $phone,
            'message' => $message,
            'type'    => $type,
        ]);
    }
}
