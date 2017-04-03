<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'code', 'name', 'description'
    ];

    // point form: unit content in about unit info
    public function unit_contents()
    {
        return $this->hasMany('App\Model\UnitContent');
    }

    // may have many lecturers teaching this unit
    public function lecturer_units()
    {
        return $this->hasMany('App\Model\LecturerUnit');
    }

    // may have many students in a list for one unit
    public function students()
    {
        return $this->hasMany('App\Model\Student');
    }

    public function quizzes()
    {
        return $this->hasMany('App\Model\Quiz');
    }
}
