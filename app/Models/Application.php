<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function applicationUrl()
    {
        return $this->belongsTo(ApplicationUrl::class);
    }

    public function examMark()
    {
        return $this->hasOne(ExamMark::class);
    }
}
