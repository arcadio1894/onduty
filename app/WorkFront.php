<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkFront extends Model
{
    protected $fillable = [
        'name', 'description', 'plant_id', 'enable'
    ];

    public function plant()
    {
        return $this->belongsTo('App\Plant');
    }
}