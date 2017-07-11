<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'name', 'value'
    ];
    // name      |  value
    // semester,    'S1'
    // year,        '2017'
}
