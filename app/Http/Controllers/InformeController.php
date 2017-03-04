<?php

namespace App\Http\Controllers;

use App\Informe;
use App\Location;
use App\Report;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        $rules = array(
            'location' => 'required',
            'user' => 'required',
            'fromdate' => 'required',
            'todate' => 'required',
        );

        $messages = array(
            'location.required'=>'Es necesario ingresar la localización',
            'user.required'=>'Es necesario ingresar el usuario',
            'fromdate.required' => 'Es necesario ingresar la la fecha de inicio de la visita',
            'todate.required' => 'Es necesario ingresar la fecha de fin de la visita',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request){
            if (Auth::user()->role_id > 3) {
                $validator->errors()->add('role', 'No tiene permisos para crear un informe');
            }

            if ($request->get('fromdate') > $request->get('todate')) {
                $validator->errors()->add('inconsistency', 'Inconsistencia en las fechas ingresadas');
            }
        });

        if(!$validator->fails()) {
            $informe = Informe::create([
                'location_id' => $request->get('location'),
                'user_id' => $request->get('user'),
                'from_date' => $request->get('fromdate'),
                'to_date' => $request->get('todate')
            ]);

            $informe->save();
        }

        return response()->json($validator->messages(), 200);

    }

    public function edit( Request $request )
    {
        //dd($request->all());
        // TODO: Solo el que puede creas es el super administrador o administrador
        $rules = array(
            'location-select' => 'required',
            'user-select' => 'required',
            'fromdate' => 'required',
            'todate' => 'required',
        );

        $messages = array(
            'location-select.required'=>'Es necesario ingresar la localización',
            'user-select.required'=>'Es necesario ingresar el usuario',
            'fromdate.required' => 'Es necesario ingresar la la fecha de inicio de la visita',
            'todate.required' => 'Es necesario ingresar la fecha de fin de la visita',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request){
            if (Auth::user()->role_id > 3) {
                $validator->errors()->add('role', 'No tiene permisos para crear un informe');
            }

            if ($request->get('fromdate') > $request->get('todate')) {
                $validator->errors()->add('inconsistency', 'Inconsistencia en las fechas ingresadas');
            }
        });

        if(!$validator->fails()) {
            $informe = Informe::find( $request->get('id') );
            $informe->location_id = $request->get('location-select');
            $informe->user_id = $request->get('user-select');
            $informe->from_date = $request->get('fromdate');
            $informe->to_date = $request->get('todate');
            $informe->save();
        }

        return response()->json($validator->messages(), 200);

    }

    public function delete( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        $rules = array(
            'id' => 'exists:informes'
        );

        $messsages = array(
            'id.exists'=>'No existe el informe especificado',
        );

        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) use($request) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para eliminar un informe');
            }

            $report = Report::where('informe_id', $request->get('id'))->first();
            if ($report) {
                $validator->errors()->add('report', 'No puede eliminar porque hay reportes dentro de este informe.');
            }
        });

        if(!$validator->fails()) {
            $informe = Informe::find($request->get('id'));
            $informe->delete();
        }

        // TODO: Validaciones en el futuro

        return response()->json($validator->messages(), 200);

    }
}
