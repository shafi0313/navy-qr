<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WrittenMark extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(
            [
                'name' => 'N/A',
                'team' => 'N/A',
            ]
        );
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'serial_no', 'serial_no');
    }
}
