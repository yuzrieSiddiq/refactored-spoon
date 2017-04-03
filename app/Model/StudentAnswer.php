<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = [
        'quiz_attempt_id', 'question_id', 'answer'
    ];

    // this answer belongs to a specific attempt
    public function quiz_attempt()
    {
        return $this->belongsTo('App\Model\QuizAttempt');
    }

    // this answer, answers one question
    public function question()
    {
        return $this->belongsTo('App\Model\Question');
    }
}
