<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'image', 'role_id', 'position_id', 'confirmed', 'confirmation_code', 'location_id'
    ];

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

    public function location()
    {
        return $this->belongsTo('App\Location');
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
