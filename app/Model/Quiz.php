<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'unit_id', 'title', 'type', 'status', 'semester', 'year'
    ];

    // this quiz is made for a specific unit
    public function unit()
    {
        return $this->belongsTo('App\Model\Unit');
    }

    // one quiz can have answers from many students
    public function student_answers()
    {
        return $this->hasMany('App\Model\StudentAnswer');
    }

    // one quiz has many questions
    public function questions()
    {
        return $this->hasMany('App\Model\Question');
    }
}
