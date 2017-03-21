<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description'
    ];

    public function position()
    {
        return $this->hasMany('App\Position', 'department_id');
    }

    protected $dates = ['deleted_at'];
}
