<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description'
    ];

    /*public function user_position()
    {
        return $this->hasMany('App\Users', 'position_id');
    }*/

    protected $dates = ['deleted_at'];
}
