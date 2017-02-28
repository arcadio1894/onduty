<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image', 'role_id', 'position_id', 'confirmed', 'confirmation_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    public function informes()
    {
        return $this->hasMany('App\Informe', 'onduty');
    }

    public function reportes()
    {
        return $this->hasMany('App\Report');
    }

    protected $dates = ['deleted_at'];
}
