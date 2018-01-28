<?php

namespace App\Http\Controllers;

use App\Informe;
use App\Observation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ObservationController extends Controller
{
    public function index($id)
    {
        $inform = Informe::with('location')->with('user')->find($id);
        $users = User::where('id', '<>', 1)->where('location_id', $inform->location_id)
            ->orderBy('name')->get();
        $observations = Observation::where('informe_id', $id )->get();

        return view('observation.index')->with(compact('inform', 'users', 'observations'));
    }

    public function store(Request $request)
    {
        // Solo el que puede crear es el super administrador o administrador
        $rules = array(
            'turn' => 'required',
            'supervisor' => 'required',
            'hse' => 'required',
            'man' => 'required',
            'woman' => 'required',
            'turn_hours' => 'required',
        );
        $messages = array(
            'turn.required'=>'Es necesario escoger un turno',
            'supervisor.required'=>'Es necesario escoger un supervisor',
            'hse.required'=>'Es necesario escoger un HSE en turno',
            'man.required'=>'Es necesario ingresar la cantidad de hombres',
            'woman.required'=>'Es necesario ingresar la cantidad de mujeres',
            'turn_hours.required'=>'Es necesario ingresar la cantidad de horas en el turno',
        );
        $validator = Validator::make($request->all(), $rules, $messages);

        $informId = $request->get('informe_id');
        $inform = Informe::find($informId);

        $validator->after(function ($validator) use ($inform) {
            if (auth()->user()->role_id > 2 || $inform->user_id == auth()->id()) {
                $validator->errors()->add('role', 'No tiene permisos para crear una observación');
            }
        });

        if (!$validator->fails()) {
            Observation::create([
                'turn' => $request->get('turn'),
                'informe_id' => $informId,
                'supervisor_id' => $request->get('supervisor'),
                'hse_id' => $request->get('hse'),
                'man' => $request->get('man'),
                'woman' => $request->get('woman'),
                'turn_hours' => $request->get('turn_hours'),
                'observation' => $request->get('observation') ?: ''
            ]);
        }
        //dd($validator->messages());
        return response()->json($validator->messages(), 200);
    }

    public function edit(Request $request)
    {
        // TODO: Solo el que puede creas es el super administrador o administrador
        $rules = array(
            'turn_edit' => 'required',
            'supervisor_edit' => 'required',
            'hse_edit' => 'required',
            'man_edit' => 'required',
            'woman_edit' => 'required',
            'turn_hours_edit' => 'required',
        );
        $messages = array(
            'turn_edit.required'=>'Es necesario escoger un turno',
            'supervisor_edit.required'=>'Es necesario escoger un supervisor',
            'hse_edit.required'=>'Es necesario escoger un HSE en turno',
            'man_edit.required'=>'Es necesario ingresar la cantidad de hombres',
            'woman_edit.required'=>'Es necesario ingresar la cantidad de mujeres',
            'turn_hours_edit.required'=>'Es necesario ingresar la cantidad de horas en el turno',
        );
        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para editar una observación');
            }
        });

        if(!$validator->fails()) {
            $observation = Observation::find($request->get('id'));
            $observation->turn = $request->get('turn_edit');
            $observation->supervisor_id = $request->get('supervisor_edit');
            $observation->hse_id = $request->get('hse_edit');
            $observation->man = $request->get('man_edit');
            $observation->woman = $request->get('woman_edit');
            $observation->turn_hours = $request->get('turn_hours_edit');
            $observation->observation = $request->get('observation_edit') ?: '';
            $observation->save();
        }

        return response()->json($validator->messages(), 200);
    }

    public function delete(Request $request)
    {
        //dd($request->all());
        // TODO: Solo el que puede creas es el super administrador o administrador
        $rules = array(
            'id' => 'exists:observations'
        );

        $messages = array(
            'id.exists'=>'No existe la observación especificada',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para eliminar una observación');
            }
        });

        if(!$validator->fails()) {
            $observation = Observation::find($request->get('id'));
            $observation->delete();
        }

        // TODO: Validaciones en el futuro

        return response()->json($validator->messages(), 200);

    }

}
