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

    protected $dates = ['deleted_at'];



    // relationships

    public function inform()
    {
        return $this->belongsTo(Informe::class, 'informe_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }

    public function work_front()
    {
        return $this->belongsTo('App\WorkFront')->withTrashed();
    }

    public function area()
    {
        return $this->belongsTo('App\Area')->withTrashed();
    }

    public function responsible()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }

    public function critical_risks()
    {
        return $this->belongsTo('App\CriticalRisk')->withTrashed();
    }

}
