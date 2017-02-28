<?php

namespace App\Http\Controllers;

use App\Area;
use App\CriticalRisk;
use App\Informe;
use App\Location;
use App\User;
use App\WorkFront;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index( $id )
    {
        $informe = Informe::with('location')->with('user')->find($id);
        $users = User::where('id', '<>', 1)->get();
        $workfronts = WorkFront::all();
        $areas = Area::all();
        $risks = CriticalRisk::all();
        
        //dd($informes);
        return view('report.index')->with(compact('informe', 'workfronts', 'areas', 'users', 'risks'));
    }

    public function getLocations()
    {
        $locations = Location::all();
        return response()->json($locations);
    }

    public function getUsers()
    {
        $users = User::where('id', '<>', 1)->get();
        return response()->json($users);
    }
}
