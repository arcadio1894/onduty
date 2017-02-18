<?php

namespace App\Http\Controllers;

use App\Area;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('enable', 1)->get();
        //dd($speakers);
        return view('role.index')->with(compact('roles'));
    }

    public function store( Request $request )
    {
        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del rol']);
        $rol = Role::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'enable' => 1
        ]);

        $rol->save();
        return response()->json(['error' => false, 'message' => 'Rol registrado correctamente']);
    }

    public function edit( Request $request )
    {
        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del área']);
        
        // TODO: Validación si el usuario tiene permisos para editar
        
        $role = Role::find( $request->get('id') );
        $role->name = $request->get('name');
        $role->description = $request->get('description');
        $role->save();

        return response()->json(['error' => false, 'message' => 'Rol modificado correctamente']);
    }

    public function delete( Request $request )
    {
        $role = Role::find($request->get('id'));

        if($role == null)
            return response()->json(['error' => true, 'message' => 'No existe el rol especificado.']);

        // TODO: Validación si tiene usuario
        // TODO: Validación si el usuario que quiere borrar este rol tiene permisos

        $role->enable = 0;
        $role->save();

        return response()->json(['error' => false, 'message' => 'Rol eliminado correctamente.']);

    }
}
