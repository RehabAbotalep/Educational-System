<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExamAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['student_exam_id', 'question_id', 'answer', 'degree'];

    protected $hidden  = ['created_at', 'updated_at'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function questionAnswer()
    {
        return $this->hasOne(SystemAnswer::class, 'question_id', 'question_id');
    }
}
