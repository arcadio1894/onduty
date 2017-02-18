<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name', 'description', 'enable'
    ];

    public function plants()
    {
        return $this->hasMany('App\Plant', 'location_id');
    }
}
