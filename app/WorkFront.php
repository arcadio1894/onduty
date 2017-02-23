<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkFront extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'description', 'plant_id', 'enable'
    ];

    public function plant()
    {
        return $this->belongsTo('App\Plant');
    }

    protected $dates = ['deleted_at'];
}
