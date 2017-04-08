<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\WorkFront;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkFrontController extends Controller
{
    public function byUserLocation(Request $request)
    {
        // get the location where the user belongs
        $user = User::find($request->input('user_id'));
        $location_id = $user->location_id;

        // and the work fronts in that location
        $workFronts = WorkFront::where('location_id', $location_id)->get([
            'id',
            'name'
        ]);

        return $workFronts;
    }
}
