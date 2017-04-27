<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    protected $fillable = [
        'student_id', 'quiz_id', 'rank_no', 'score'
    ];

    public function quiz()
    {
        return $this->belongsTo('App\Model\Quiz');
    }

    public function student()
    {
        return $this->belongsTo('App\Model\Student');
    }
}
