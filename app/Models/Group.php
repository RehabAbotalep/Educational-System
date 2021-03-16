<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'body', 'image', 'teacher_id', 'created_by'
    ];

    protected $hidden = ['created_at', 'updated_at', 'created_by', 'teacher_id'];


    /*public function getImageAttribute($value): string
    {
        return env('APP_URL') . 'images/groups/' .$value;
    }*/

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function dates()
    {
        return $this->hasMany(GroupDate::class);
    }

    public function groupStudents()
    {
        return $this->hasMany(GroupStudent::class, 'group_id');
    }
}
