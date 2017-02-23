<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plant extends Model
{
    use SoftDeletes;
    
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

    protected $dates = ['deleted_at'];
}
