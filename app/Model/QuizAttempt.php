<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'user_id', 'quiz_id', 'is_attempted'
    ];

    // this attempt was done by a specific student
    public function student()
    {
        return $this->belongsTo('App\Model\Student');
    }

    // this attempt was meant for a specific quiz
    public function quiz()
    {
        return $this->belongsTo('App\Model\Quiz');
    }

    // one attempt answers many questions
    public function student_answers()
    {
        return $this->hasMany('App\Model\StudentAnswer');
    }
}
