<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UnitContent extends Model
{
    protected $fillable = [
        'unit_id', 'content'
    ];

    // this info belongs to one unit
    public function unit()
    {
        return $this->belongsTo('App\Model\Unit');
    }
}
