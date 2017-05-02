<?php

namespace App;

use Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // TODO: remove this -> unrelated to this project
    public function books()
    {
        return $this->hasMany('App\Book');
    }

    // for `role: lecturer`
    public function lecturer_units()
    {
        return $this->hasMany('App\Model\LecturerUnit');
    }

    // for `role: student`
    public function student_info()
    {
        return $this->hasOne('App\Model\StudentInfo');
    }

    // student listing `one user may have many student list entry`
    public function students()
    {
        return $this->hasMany('App\Model\Student');
    }
}
