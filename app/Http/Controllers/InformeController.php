<?php

namespace App\Http\Controllers;

use App\Informe;
use App\Location;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformeController extends Controller
{
    public function index()
    {
        $informes = Informe::with('location')->with('user')->get();
        $locations = Location::all();
        $users = User::where('id', '<>', 1)->get();
        //dd($informes);
        return view('informe.index')->with(compact('informes', 'locations', 'users'));
    }

    public function store( Request $request )
    {
        //dd($request->all());
        // TODO: Solo el que puede creas es el super administrador o administrador o responsable
        if (Auth::user()->role_id > 3)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para crear un informe.']);

        if ($request->get('location') == null OR $request->get('location') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar la localización']);

        if ($request->get('user') == null OR $request->get('user') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el usuario']);

        if ($request->get('fromdate') == null OR $request->get('fromdate') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar la la fecha de inicio de la visita']);

        if ($request->get('todate') == null OR $request->get('todate') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar la fecha de fin de la visita']);

        if ($request->get('fromdate') > $request->get('todate'))
            return response()->json(['error' => true, 'message' => 'Inconsistencia en las fechas ingresadas']);

        $informe = Informe::create([
            'location_id' => $request->get('location'),
            'user_id' => $request->get('user'),
            'from_date' => $request->get('fromdate'),
            'to_date' => $request->get('todate')
        ]);

        $informe->save();
        return response()->json(['error' => false, 'message' => 'Informe registrado correctamente']);
    }

    public function edit( Request $request )
    {
        //dd($request->all());
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 3)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para editar un informe.']);

        if ($request->get('location-select') == null OR $request->get('location-select') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar la localización']);

        if ($request->get('user-select') == null OR $request->get('user-select') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el usuario']);

        if ($request->get('fromdate') == null OR $request->get('fromdate') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar la la fecha de inicio de la visita']);

        if ($request->get('todate') == null OR $request->get('todate') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar la fecha de fin de la visita']);

        if ($request->get('fromdate') > $request->get('todate'))
            return response()->json(['error' => true, 'message' => 'Inconsistencia en las fechas ingresadas']);

        $informe = Informe::find( $request->get('id') );
        $informe->location_id = $request->get('location-select');
        $informe->user_id = $request->get('user-select');
        $informe->from_date = $request->get('fromdate');
        $informe->to_date = $request->get('todate');
        $informe->save();

        return response()->json(['error' => false, 'message' => 'Informe modificado correctamente']);
    }

    public function delete( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para eliminar un informe.']);

        $informe = Informe::find($request->get('id'));

        if($informe == null)
            return response()->json(['error' => true, 'message' => 'No existe el informe especificado.']);

        // TODO: Validaciones en el futuro

        $informe->delete();

        return response()->json(['error' => false, 'message' => 'Informe eliminado correctamente.']);

    }
}
