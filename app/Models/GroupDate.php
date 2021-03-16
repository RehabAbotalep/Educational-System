<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupDate extends Model
{
    use HasFactory;

    protected $hidden = ['group_id', 'created_at', 'updated_at'];

    protected $fillable = ['day', 'from', 'to', 'group_id'];


}
