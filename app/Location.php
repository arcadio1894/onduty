<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'description'
    ];

    public function workfront()
    {
        return $this->hasMany('App\WorkFront', 'location_id');
    }

    public function informes()
    {
        return $this->hasMany('App\Informes', 'location_id');
    }

    protected $dates = ['deleted_at'];
}
