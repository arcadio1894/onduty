<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $rules = array(
            'name' => 'required|min:2|unique:areas',
        );
        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del área',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
            'name.unique'=>'Existe un área con el mismo nombre.'
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para crear un área');
            }
        });

        if(!$validator->fails()) {
            $area = Area::create([
                'name' => $request->get('name'),
                'description' => $request->get('description')
            ]);
            $area->save();
        }
        //dd($validator->messages());
        return response()->json($validator->messages(), 200);

    }

    public function edit( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        $rules = array(
            'name' => 'required|min:2|unique:areas',
        );
        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del área',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
            'name.unique'=>'Existe un área con el mismo nombre.'
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para editar un área');
            }
        });

        if(!$validator->fails()) {
            $area = Area::find($request->get('id'));
            $area->name = $request->get('name');
            $area->description = $request->get('description');
            $area->save();
        }

        return response()->json($validator->messages(), 200);
    }

    public function delete( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        $rules = array(
            'id' => 'exists:areas'
        );
        
        $messsages = array(
            'id.exists'=>'No existe el área especificada',
        );
        
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para eliminar un área');
            }
        });

        if(!$validator->fails()) {
            $area = Area::find($request->get('id'));
            $area->delete();
        }
        
        // TODO: Validaciones en el futuro

        return response()->json($validator->messages(), 200);

    }

}
