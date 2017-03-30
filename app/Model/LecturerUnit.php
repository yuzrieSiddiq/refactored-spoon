<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LecturerUnit extends Model
{
    protected $fillable = [
        'user_id', 'unit_id'
    ];
}
