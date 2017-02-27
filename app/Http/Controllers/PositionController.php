<?php

namespace App\Http\Controllers;

use App\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::orderBy('name')->get();
        //dd($speakers);
        return view('position.index')->with(compact('positions'));
    }

    public function store( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para crear un cargo.']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del cargo']);
        $area = Position::create([
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);

        $area->save();
        return response()->json(['error' => false, 'message' => 'Cargo registrado correctamente']);
    }

    public function edit( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para editar un cargo.']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del cargo']);

        $area = Position::find( $request->get('id') );
        $area->name = $request->get('name');
        $area->description = $request->get('description');
        $area->save();

        return response()->json(['error' => false, 'message' => 'Cargo modificado correctamente']);
    }

    public function delete( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para eliminar un cargo.']);

        $position = Position::find($request->get('id'));

        if($position == null)
            return response()->json(['error' => true, 'message' => 'No existe el cargo especificada.']);

        // TODO: Validaciones en el futuro

        $position->delete();

        return response()->json(['error' => false, 'message' => 'Cargo eliminado correctamente.']);

    }
}
