<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'start', 'end', 'time', 'degree',
        'type_id', 'teacher_id', 'group_id', 'is_closed'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function type()
    {
        return $this->belongsTo(ExamType::class, 'type_id');
    }

    /*Automated marked Exams scope*/
    public function scopeAutomatedMarked($query, $is_automated_marked)
    {
        return $query->whereHas('type', function($query) use($is_automated_marked){
            $query->where('automated_marked', $is_automated_marked);
        });
    }
}
