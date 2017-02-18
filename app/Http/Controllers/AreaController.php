<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::where('enable', 1)->get();
        //dd($speakers);
        return view('area.index')->with(compact('areas'));
    }

    public function store( Request $request )
    {
        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del área']);
        $area = Area::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'enable' => 1
        ]);

        $area->save();
        return response()->json(['error' => false, 'message' => 'Área registrada correctamente']);
    }

    public function edit( Request $request )
    {
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
        $area = Area::find($request->get('id'));

        if($area == null)
            return response()->json(['error' => true, 'message' => 'No existe el área especificada.']);

        // TODO: Validaciones en el futuro

        $area->enable = 0;
        $area->save();

        return response()->json(['error' => false, 'message' => 'Área eliminada correctamente.']);

    }

}
