<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'title', 'user_id'];

    protected $hidden = ['created_at', 'updated_at', 'group_id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
