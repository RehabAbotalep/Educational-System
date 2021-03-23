<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'student_id', 'total_degree'];

    protected $hidden  = ['created_at', 'updated_at'];
}
