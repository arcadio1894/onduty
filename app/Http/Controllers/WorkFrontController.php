<?php

namespace App\Http\Controllers;

use App\Plant;
use App\WorkFront;
use Illuminate\Http\Request;

class WorkFrontController extends Controller
{
    public function index( $id )
    {
        $plant = Plant::with('location')->find($id);
        $workFronts = WorkFront::where('plant_id', $id)->where('enable', 1)->with('plant')->get();
        //dd($plant);
        return view('workfront.index')->with(compact('plant', 'workFronts'));
    }

    public function store( Request $request )
    {
        // TODO: Solo el que puede crear es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para crear un frente de trabajo.']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del frente de trabajo']);
        $workFront = WorkFront::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'plant_id' => $request->get('plant'),
            'enable' => '1'
        ]);

        $workFront->save();
        return response()->json(['error' => false, 'message' => 'Frente de trabajo registrado correctamente']);
    }

    public function edit( Request $request )
    {
        // TODO: Solo el que puede editar es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para editar un frente de trabajo.']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del frente de trabajo']);

        $workFronts = WorkFront::find( $request->get('id') );
        $workFronts->name = $request->get('name');
        $workFronts->plant_id = $request->get('plant');
        $workFronts->description = $request->get('description');
        $workFronts->save();

        return response()->json(['error' => false, 'message' => 'Frente de trabajo modificado correctamente']);
    }

    public function delete( Request $request )
    {
        // TODO: Solo el que puede eliminar es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para eliminar un frente de trabajo.']);

        $workFronts = WorkFront::find($request->get('id'));
        if($workFronts == null)
            return response()->json(['error' => true, 'message' => 'No existe el frente de trabajo especificada.']);

        $workFronts->enable = 0;
        $workFronts->save();

        return response()->json(['error' => false, 'message' => 'Frente de trabajo eliminado correctamente.']);

    }
}
