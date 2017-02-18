<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $fillable = [
        'name', 'description', 'location_id', 'enable'
    ];

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function workfronts()
    {
        return $this->hasMany('App\WorkFront', 'plant_id');
    }
}
