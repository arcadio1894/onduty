<?php

namespace App\Http\Controllers;

use App\Area;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        //dd($speakers);
        return view('role.index')->with(compact('roles'));
    }

    public function store( Request $request )
    {
        // TODO: Solo pueden crear usuarios el del rol super administrador que es el rol 1
        $rules = array(
            'name' => 'required|min:2',
        );
        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del área',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para crear un área');
            }
        });

        if(!$validator->fails()) {
            $rol = Role::create([
                'name' => $request->get('name'),
                'description' => $request->get('description')
            ]);

            $rol->save();
        }

        return response()->json($validator->messages(), 200);
    }

    public function edit( Request $request )
    {
        $rules = array(
            'name' => 'required|min:2',
        );
        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del área',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para crear un área');
            }
        });

        if(!$validator->fails()) {
            $role = Role::find( $request->get('id') );
            $role->name = $request->get('name');
            $role->description = $request->get('description');
            $role->save();
        }


        return response()->json($validator->messages(), 200);
    }

    public function delete( Request $request )
    {
        $rules = array(
            'id' => 'exists:roles',
        );

        $messsages = array(
            'id.exists'=>'No existe el rol especificado',
        );

        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) use ($request) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para eliminar un rol de usuario');
            }
            // TODO: Validación si tiene Frentes de Trabajo
            $users = User::where('role_id', $request->get('id'))->first();
            if ($users) {
                $validator->errors()->add('work', 'No puede eliminar porque hay usuarios dentro de este rol.');
            }
        });

        if(!$validator->fails()) {
            $role = Role::find($request->get('id'));
            $role->delete();
        }
        
        return response()->json($validator->messages(), 200);

    }
    
    public function getRoles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }
}
