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

    // Scope a search query to only include users of a given name.
    public function scopeSearch($query, $search)
    {
        return $query->application->where('name', 'like', '%' . $search . '%')
            ->orWhere('roll', 'like', '%' . $search . '%');
    }
}
