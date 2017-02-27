<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::orderBy('name')->get();
        //dd($speakers);
        return view('area.index')->with(compact('areas'));
    }

    public function store( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para crear un área.']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del área']);
        $area = Area::create([
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);

        $area->save();
        return response()->json(['error' => false, 'message' => 'Área registrada correctamente']);
    }

    public function edit( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para editar un área.']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del Área']);

        $area = Area::find( $request->get('id') );
        $area->name = $request->get('name');
        $area->description = $request->get('description');
        $area->save();

        return response()->json(['error' => false, 'message' => 'Área modificado correctamente']);
    }

    public function delete( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para eliminar un área.']);

        $area = Area::find($request->get('id'));

        if($area == null)
            return response()->json(['error' => true, 'message' => 'No existe el área especificada.']);

        // TODO: Validaciones en el futuro

        $area->delete();

        return response()->json(['error' => false, 'message' => 'Área eliminada correctamente.']);

    }

}
