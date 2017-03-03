<?php

namespace App\Http\Controllers;

use App\Location;
use App\Plant;
use App\WorkFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WorkFrontController extends Controller
{
    public function index( $id )
    {
        $location = Location::find($id);
        $workFronts = WorkFront::where('location_id', $id)->with('location')->orderBy('name')->get();
        //dd($plant);
        return view('workfront.index')->with(compact('location', 'workFronts'));
    }

    public function store( Request $request )
    {
        // TODO: Solo el que puede crear es el super administrador o administrador
        $rules = array(
            'name' => 'required|min:2',
            'location_id' => 'exists:locations, id'
        );
        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del frente de trabajo',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
            'location_id'=> 'No existe la localización de este frente de trabajo',
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para crear un frente de trabajo');
            }
        });

        if(!$validator->fails()) {
            $workFront = WorkFront::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'location_id' => $request->get('location')
            ]);
            $workFront->save();
        }

        return response()->json($validator->messages(), 200);

    }

    public function edit( Request $request )
    {
        // TODO: Solo el que puede editar es el super administrador o administrador
        $rules = array(
            'name' => 'required|min:2',
            'location_id' => 'exists:locations, id',
        );
        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del frente de trabajo',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
            'location_id'=> 'No existe la localización de este frente de trabajo',
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para editar un frente de trabajo');
            }
        });

        if(!$validator->fails()) {
            $workFronts = WorkFront::find( $request->get('id') );
            $workFronts->name = $request->get('name');
            $workFronts->location_id = $request->get('location');
            $workFronts->description = $request->get('description');
            $workFronts->save();
        }

        return response()->json($validator->messages(), 200);

    }

    public function delete( Request $request )
    {
        // TODO: Solo el que puede eliminar es el super administrador o administrador
        $rules = array(
            'id' => 'exists:work_fronts'
        );

        $messsages = array(
            'id.exists'=>'No existe el frente de trabajo especificado',
        );

        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para eliminar un frente de trabajo');
            }
        });

        if(!$validator->fails()) {
            $workFronts = WorkFront::find($request->get('id'));
            $workFronts->delete();
        }

        return response()->json($validator->messages(), 200);

    }
}
