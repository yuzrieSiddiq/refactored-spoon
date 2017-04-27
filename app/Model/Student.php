<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'unit_id', 'semester', 'year',
        'team_number', 'is_group_leader'
    ];

    // this entry (from a class list) belongs to one student
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // this entry (from a student list) belongs to one class/unit
    public function unit()
    {
        return $this->belongsTo('App\Model\Unit');
    }

    public function ranking()
    {
        return $this->hasMany('App\Model\Ranking');
    }
}
