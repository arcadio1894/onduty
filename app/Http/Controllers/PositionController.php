<?php

namespace App\Http\Controllers;

use App\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $rules = array(
            'name' => 'required|min:2|unique:positions',
        );
        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del cargo',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
            'name.unique'=>'Existe un cargo con el mismo nombre.'
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para crear un cargo');
            }
        });

        if(!$validator->fails()) {
            $area = Position::create([
                'name' => $request->get('name'),
                'description' => $request->get('description')
            ]);
            $area->save();
        }

        return response()->json($validator->messages(), 200);
    }

    public function edit( Request $request )
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        $rules = array(
            'name' => 'required|min:2|unique:positions',
        );
        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del cargo',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
            'name.unique'=>'Existe un cargo con el mismo nombre.'
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para editar un cargo');
            }
        });

        if(!$validator->fails()) {
            $area = Position::find( $request->get('id') );
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
            'id' => 'exists:positions'
        );

        $messsages = array(
            'id.exists'=>'No existe el cargo especificado',
        );

        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para eliminar un cargo');
            }
        });

        if(!$validator->fails()) {
            $position = Position::find($request->get('id'));
            $position->delete();
        }

        // TODO: Validaciones en el futuro

        return response()->json($validator->messages(), 200);

    }
}
