<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentInfo extends Model
{
    protected $fillable = [
        'user_id', 'student_id', 'locality'
    ];
}
