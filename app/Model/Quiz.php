<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'unit_id', 'title', 'type', 'status'
    ];

    // this quiz is made for a specific unit
    public function unit()
    {
        return $this->belongsTo('App\Model\Unit');
    }

    // one quiz can have multiple attempts from different students (1 student 1 attempt)
    public function quiz_attempts()
    {
        return $this->hasMany('App\Model\QuizAttempt');
    }

    // one quiz has many questions
    public function questions()
    {
        return $this->hasMany('App\Model\Question');
    }
}
