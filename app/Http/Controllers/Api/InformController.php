<?php

namespace App\Http\Controllers\Api;

use App\Informe;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InformController extends Controller
{
    public function byUserLocation(Request $request)
    {
        // get the location where the user belongs
        $user = User::find($request->input('user_id'));
        $location_id = $user->location_id;

        // and the informs in that location
        $informs = Informe::where('location_id', $location_id)->get([
            'user_id', 'from_date', 'to_date',
            'created_at'
        ]);

        foreach ($informs as $inform) {
            $inform->user_name = $inform->user->name; // append the user name
            unset($inform->user); // but not include all the information

            $inform->from_date_format = $inform->from_date->format('d/m/Y');
            $inform->to_date_format = $inform->to_date->format('d/m/Y');
            unset($inform->from_date);
            unset($inform->to_date);
        }

        return $informs;
    }
}
