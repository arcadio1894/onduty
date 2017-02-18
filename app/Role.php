<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'description', 'enable'
    ];

    public function users()
    {
        return $this->hasMany('App\Users', 'role_id');
    }
}
