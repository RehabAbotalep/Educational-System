<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'student_id', 'total_degree'];

    protected $hidden  = ['created_at', 'updated_at', 'exam_id'];

    public function studentExamAnswers()
    {
        return $this->hasMany(StudentExamAnswer::class, 'student_exam_id' );
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
