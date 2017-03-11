<?php

namespace App\Http\Controllers;

use App\CriticalRisk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $rules = array(
            'name' => 'required|min:2|unique:critical_risks',
        );
        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del riesgo crítico',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
            'name.unique'=>'Existe un riesgo crítico con el mismo nombre'
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para crear un riesgo crítico');
            }
        });

        if(!$validator->fails()) {
            $area = CriticalRisk::create([
                'name' => $request->get('name')
            ]);
            $area->save();
        }

        return response()->json($validator->messages(), 200);

    }

    public function edit( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        $rules = array(
            'name' => 'required|min:2|unique:critical_risks',
        );
        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del riesgo crítico',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
            'name.unique'=>'Existe un riesgo crítico con el mismo nombre'
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para editar un riesgo crítco');
            }
        });

        if(!$validator->fails()) {
            $area = CriticalRisk::find($request->get('id'));
            $area->name = $request->get('name');
            $area->save();
        }

        return response()->json($validator->messages(), 200);

    }

    public function delete( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        $rules = array(
            'id' => 'exists:critical_risks'
        );

        $messsages = array(
            'id.exists'=>'No existe el riesgo crítico especificada',
        );

        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para eliminar un riesgo crítico');
            }
        });

        if(!$validator->fails()) {
            $risk = CriticalRisk::find($request->get('id'));
            $risk->delete();
        }
        // TODO: Validaciones en el futuro

        return response()->json($validator->messages(), 200);

    }
}
