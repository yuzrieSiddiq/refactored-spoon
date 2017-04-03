<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'quiz_id', 'answer_type', 'question', 'correct_answer',
        'answer1', 'answer2', 'answer3', 'answer4', 'answer5'
    ];

    // this (one) question belongs to a specific quiz
    public function quiz()
    {
        return $this->belongsTo('App\Model\Quiz');
    }

    // has many student answers (1 student 1 answer)
    public function student_answers()
    {
        return $this->hasMany('App\Model\StudentAnswer');
    }
}
