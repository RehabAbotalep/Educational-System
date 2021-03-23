<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'title'];

    protected $hidden  = ['created_at', 'updated_at', 'exam_id'];

    public function image()
    {
        return $this->hasOne(QuestionImage::class);
    }

    public function systemAnswer()
    {
        return $this->hasOne(SystemAnswer::class, 'question_id');
    }
}
