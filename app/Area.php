<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'description'
    ];

    public function reportes()
    {
        return $this->hasMany('App\Report');
    }
    
    protected $dates = ['deleted_at'];
}
