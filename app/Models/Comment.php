<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['discussion_id', 'comment', 'user_id'];

    protected $hidden = ['created_at', 'updated_at'];
}
