<?php

namespace App\Http\Controllers;

use App\Department;
use App\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('name')->get();
        //dd($speakers);
        return view('department.index')->with(compact('departments'));
    }

    public function store(Request $request)
    {
        // TODO: Solo el que puede crear es el super administrador o administrador
        $rules = array(
            'name' => 'required|min:2|unique:departments',
        );
        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del departamento',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
            'name.unique'=>'Existe un departamento con el mismo nombre.'
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para crear un departamento');
            }
        });

        if(!$validator->fails()) {
            $area = Department::create([
                'name' => $request->get('name'),
                'description' => $request->get('description')
            ]);
            $area->save();
        }
        //dd($validator->messages());
        return response()->json($validator->messages(), 200);

    }

    public function edit(Request $request)
    {
        $id = $request->input('id');

        // TODO: Solo puede crear el super administrador o administradores
        $rules = [
            'name' => "required|min:2|unique:departments,name,$id",
        ];
        $messages = [
            'name.required' => 'Es necesario ingresar el nombre del departamento',
            'name.min' => 'El nombre debe tener por lo menos 2 caracteres',
            'name.unique' => 'Existe un departamento con el mismo nombre.'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para editar un departamento');
            }
        });

        if(!$validator->fails()) {
            $area = Department::find($request->get('id'));
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
            'id' => 'exists:departments'
        );

        $messsages = array(
            'id.exists'=>'No existe el departamento especificado',
        );

        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) use ($request) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para eliminar un departamento');
            }
            $departments = Position::where('department_id', $request->get('id'))->first();
            if ($departments != null) {
                $validator->errors()->add('department', 'No puede eliminar este departamento por tiene cargos asignados');
            }
        });

        if(!$validator->fails()) {
            $area = Department::find($request->get('id'));
            $area->delete();
        }

        // TODO: Validaciones en el futuro

        return response()->json($validator->messages(), 200);

    }

    public function getDepartment( $idPosition ){
        $position = Position::find($idPosition);
        $department = Department::find($position->department_id);
        return response()->json($department);
    }

}
