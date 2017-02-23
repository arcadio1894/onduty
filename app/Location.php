<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'description', 'enable'
    ];

    public function plants()
    {
        return $this->hasMany('App\Plant', 'location_id');
    }

    protected $dates = ['deleted_at'];
}
