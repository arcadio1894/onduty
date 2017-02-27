<?php

namespace App\Http\Controllers;

use App\CriticalRisk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CriticalRiskController extends Controller
{
    public function index()
    {
        $risks = CriticalRisk::orderBy('name')->get();
        //dd($speakers);
        return view('critical_risk.index')->with(compact('risks'));
    }

    public function store( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para crear un riesgo crítico.']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del riesgo crítico']);
        $area = CriticalRisk::create([
            'name' => $request->get('name')
        ]);

        $area->save();
        return response()->json(['error' => false, 'message' => 'Riesgo crítico registrado correctamente']);
    }

    public function edit( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para editar un riesgo crítico.']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del riesgo crítico']);

        $area = CriticalRisk::find( $request->get('id') );
        $area->name = $request->get('name');
        $area->save();

        return response()->json(['error' => false, 'message' => 'Riesgo Crítico modificado correctamente']);
    }

    public function delete( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para eliminar un Riesgo Crítico.']);

        $area = CriticalRisk::find($request->get('id'));

        if($area == null)
            return response()->json(['error' => true, 'message' => 'No existe el Riesgo Crítico especificado.']);

        // TODO: Validaciones en el futuro

        $area->delete();

        return response()->json(['error' => false, 'message' => 'Riesgo Crítico eliminado correctamente.']);

    }
}
