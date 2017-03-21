<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'department_id'
    ];

    /*public function user_position()
    {
        return $this->hasMany('App\Users', 'position_id');
    }*/

    public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }

    protected $dates = ['deleted_at'];
}
