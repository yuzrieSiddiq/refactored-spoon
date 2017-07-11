<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LecturerUnit extends Model
{
    protected $fillable = [
        'user_id', 'unit_id', 'semester', 'year'
    ];

    // this belongs to a lecturer (user)
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // this belongs to a unit
    public function unit()
    {
        return $this->belongsTo('App\Model\Unit');
    }
}
