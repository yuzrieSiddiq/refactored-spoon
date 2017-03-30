<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'quiz_id', 'answer_type', 'question', 'correct_answer',
        'answer1', 'answer2', 'answer3', 'answer4', 'answer5'
    ];
}
