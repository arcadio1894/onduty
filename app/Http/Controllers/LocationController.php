<?php

namespace App\Http\Controllers;

use App\Location;
use App\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        //dd($speakers);
        return view('location.index')->with(compact('locations'));
    }

    public function store( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para crear una localización.']);
        
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
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para editar una localización.']);

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
        // TODO: Solo el que puede eliminar es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para eliminar una localización.']);

        $location = Location::find($request->get('id'));

        if($location == null)
            return response()->json(['error' => true, 'message' => 'No existe la localización especificada.']);
        
        // TODO: Validación si tiene plantas
        $plants = Plant::where('location_id', $location->id)->first();
        if($plants)
            return response()->json(['error' => true, 'message' => 'No se puede eliminar la localización porque hay plantas activas dentro de esta localización.']);

        $location->delete();
        
        return response()->json(['error' => false, 'message' => 'Localización eliminada correctamente.']);

    }
}
