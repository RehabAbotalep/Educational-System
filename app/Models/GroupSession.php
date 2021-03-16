<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSession extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'link', 'from', 'to', 'group_id', 'is_deleted'];

    protected $hidden = ['group_id', 'created_at', 'updated_at'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }


}
