<?php

namespace App\Http\Controllers;

use App\Location;
use App\Plant;
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
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre de la localización']);
        $location = Location::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'enable' => '1'
        ]);

        $location->save();
        return response()->json(['error' => false, 'message' => 'Localización registrada correctamente']);
    }

    public function edit( Request $request )
    {
        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre de la localización']);

        $location = Location::find( $request->get('id') );
        $location->name = $request->get('name');
        $location->description = $request->get('description');
        $location->save();

        return response()->json(['error' => false, 'message' => 'Localización modificado correctamente']);
    }

    public function delete( Request $request )
    {
        $location = Location::find($request->get('id'));

        if($location == null)
            return response()->json(['error' => true, 'message' => 'No existe la localización especificada.']);
        
        // TODO: Validación si tiene plantas
        $plants = Plant::where('location_id', $location->id)->where('enable', 1)->first();
        if($plants)
            return response()->json(['error' => true, 'message' => 'No se puede eliminar la localización porque hay plantas activas dentro de esta localización.']);

        $location->enable = 0;
        $location->save();
        
        return response()->json(['error' => false, 'message' => 'Localización eliminada correctamente.']);

    }
}
