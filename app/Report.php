<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'informe_id', 'user_id', 'work_front_id', 'area_id', 'responsible_id', 'aspect', 'critical_risks_id', 'potential',
        'state', 'inspections', 'description', 'actions', 'observations', 'image', 'image_action',
        'planned_date', 'deadline'

    ];

    public function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }

    public function work_front()
    {
        return $this->belongsTo('App\WorkFront');
    }

    public function area()
    {
        return $this->belongsTo('App\Area');
    }

    public function responsible()
    {
        return $this->belongsTo('App\User');
    }

    public function critical_risks()
    {
        return $this->belongsTo('App\CriticalRisk');
    }

    protected $dates = ['deleted_at'];
}
