<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
        
    protected $fillable = [
        'name', 'description', 'enable'
    ];

    public function users()
    {
        return $this->hasMany('App\Users', 'role_id');
    }

    protected $dates = ['deleted_at'];
}
