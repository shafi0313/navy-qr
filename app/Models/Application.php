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
        return $this->hasOne(ExamMark::class)->withDefault([
            'bangla' => 0,
            'english' => 0,
            'math' => 0,
            'science' => 0,
            'general_knowledge' => 0
        ]);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('serial_no', 'like', '%'.$search.'%')
            ->orWhere('name', 'like', '%'.$search.'%')
            ->orWhere('eligible_district', 'like', '%'.$search.'%')
            ->orWhere('candidate_designation', 'like', '%'.$search.'%');
    }
}
