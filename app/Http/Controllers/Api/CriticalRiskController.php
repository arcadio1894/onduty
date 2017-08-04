<?php

namespace App\Http\Controllers\Api;

use App\CriticalRisk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CriticalRiskController extends Controller
{
    public function all()
    {
        $areas = CriticalRisk::orderBy('name')->get([
            'id', 'name'
        ]);

        return $areas;
    }
}
