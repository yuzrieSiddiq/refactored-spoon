<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'unit_id', 'semester', 'year',
        'team_number', 'is_group_leader'
    ];
}
