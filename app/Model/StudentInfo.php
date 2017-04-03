<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentInfo extends Model
{
    protected $fillable = [
        'user_id', 'student_id', 'locality'
    ];

    // this info belongs to one student
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
