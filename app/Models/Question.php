<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'title'];

    protected $hidden  = ['created_at', 'updated_at'];

    public function image()
    {
        return $this->hasOne(QuestionImage::class);
    }
}
