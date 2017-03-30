<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = [
        'quiz_attempt_id', 'question_id', 'answer'
    ];
}
