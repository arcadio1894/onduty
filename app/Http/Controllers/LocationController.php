<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::where('enable', 1)->get();
        //dd($speakers);
        return view('location.index')->with(compact('locations'));
    }

    public function store( Request $request )
    {
        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre de la locación']);
        $location = Location::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'enable' => '1'
        ]);

        $location->save();
        return response()->json(['error' => false, 'message' => 'Locación registrada correctamente']);
    }
}
