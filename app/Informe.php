<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Informe extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'location_id', 'user_id', 'from_date', 'to_date', 'active'
    ];

    protected $dates = [
        'deleted_at',
        'from_date', 'to_date'
    ];

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }

}
