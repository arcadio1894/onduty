<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Observation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'turn', 'supervisor_id', 'hse_id', 'man', 'woman',
        'turn_hours', 'observation'
    ];

    public function supervisor()
    {
        return $this->belongsTo('App\User');
    }

    public function hse()
    {
        return $this->belongsTo('App\User');
    }

    public function getTotalPeopleAttribute()
    {
        return ($this->man + $this->woman);
    }

    public function getWorkHoursAttribute()
    {
        return ($this->total_people * $this->turn_hours);
    }

    protected $dates = ['deleted_at'];
}
