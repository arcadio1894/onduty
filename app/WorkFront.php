<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkFront extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'description', 'location_id'
    ];

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function reportes()
    {
        return $this->hasMany('App\Report');
    }

    protected $dates = ['deleted_at'];
}
