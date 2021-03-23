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
}
