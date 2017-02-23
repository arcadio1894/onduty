<?php

namespace App\Http\Controllers;

use App\Location;
use App\Plant;
use App\WorkFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlantController extends Controller
{
    public function index( $id )
    {
        $location = Location::find($id);
        $plants = Plant::where('location_id', $id)->with('location')->get();
        //dd($plants);
        return view('plant.index')->with(compact('plants', 'location'));
    }

    public function store( Request $request )
    {
        // TODO: Solo el que puede crear es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para crear una planta.']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre de la planta']);
        $plant = Plant::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'location_id' => $request->get('location'),
            'enable' => '1'
        ]);

        $plant->save();
        return response()->json(['error' => false, 'message' => 'Planta registrada correctamente']);
    }

    public function edit( Request $request )
    {
        // TODO: Solo el que puede editar es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para editar una planta.']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre de la planta']);

        $plant = Plant::find( $request->get('id') );
        $plant->name = $request->get('name');
        $plant->location_id = $request->get('location');
        $plant->description = $request->get('description');
        $plant->save();

        return response()->json(['error' => false, 'message' => 'Planta modificado correctamente']);
    }

    public function delete( Request $request )
    {
        // TODO: Solo el que puede eliminar es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para eliminar una planta.']);

        $plant = Plant::find($request->get('id'));
        if($plant == null)
            return response()->json(['error' => true, 'message' => 'No existe la planta especificada.']);

        // TODO: Validación si tiene frentes de trabajo
        $workfront = WorkFront::where('plant_id', $plant->id)->first();
        if($workfront)
            return response()->json(['error' => true, 'message' => 'No se puede eliminar la planta porque hay frentes de trabajo activas dentro de esta planta.']);

        $plant->delete();

        return response()->json(['error' => false, 'message' => 'Planta eliminada correctamente.']);

    }
}
