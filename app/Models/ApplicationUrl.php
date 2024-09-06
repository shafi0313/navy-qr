<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationUrl extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function application()
    {
        return $this->hasOne(Application::class, 'application_url_id');
    }
}
