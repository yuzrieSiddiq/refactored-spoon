<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = [
        'student_id', 'quiz_id', 'question_id', 'answer'
    ];

    // this answer belongs to a specific student
    public function student()
    {
        return $this->belongsTo('App\Model\Student');
    }

    // this answer belongs to a specific quiz
    public function quiz()
    {
        return $this->belongsTo('App\Model\Quiz');
    }

    // this answer belongs to a specific question
    public function question()
    {
        return $this->belongsTo('App\Model\Question');
    }
}
