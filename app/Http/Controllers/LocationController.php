<?php

namespace App\Http\Controllers;

use App\Location;
use App\Plant;
use App\WorkFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('name')->get();
        //dd($speakers);
        return view('location.index')->with(compact('locations'));
    }

    public function store( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        $rules = array(
            'name' => 'required|min:2',
        );
        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre de la localización',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para crear una localización');
            }
        });

        if(!$validator->fails()) {
            $location = Location::create([
                'name' => $request->get('name'),
                'description' => $request->get('description')
            ]);

            $location->save();
        }

        return response()->json($validator->messages(), 200);

    }

    public function edit( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        $rules = array(
            'name' => 'required|min:2',
        );
        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre de la localización',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para editar una localización');
            }
        });

        if(!$validator->fails()) {
            $location = Location::find( $request->get('id') );
            $location->name = $request->get('name');
            $location->description = $request->get('description');
            $location->save();
        }

        return response()->json($validator->messages(), 200);

    }

    public function delete( Request $request )
    {
        // TODO: Solo el que puede eliminar es el super administrador o administrador
        $rules = array(
            'id' => 'exists:locations',
        );

        $messsages = array(
            'id.exists'=>'No existe al localización especificada',
        );

        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) use ($request) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para eliminar una localización');
            }
            // TODO: Validación si tiene Frentes de Trabajo
            $workFront = WorkFront::where('location_id', $request->get('id'))->first();
            if ($workFront) {
                $validator->errors()->add('work', 'No puede eliminar porque hay frentes de trabajo dentro de esta localización.');
            }
        });

        if(!$validator->fails()) {
            $location = Location::find($request->get('id'));
            $location->delete();
        }

        return response()->json($validator->messages(), 200);

    }
}
