<?php

namespace App\Http\Controllers\Api;

use App\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AreaController extends Controller
{
    public function all()
    {
        $areas = Area::all([
            'id',
            'name'
        ]);

        return $areas;
    }
}
