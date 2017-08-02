<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'quiz_id',
        'group_number',
        'is_open',
        'is_randomized',
        'test_date',
        'duration',
    ];

    public function quiz()
    {
        return $this->belongsTo('App\Model\Quiz');
    }
}
