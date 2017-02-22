<?php

namespace App\Http\Controllers;

use App\Area;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if ( Auth::user()->role_id > 2 )
            return response()->json(['error' => true, 'message' => 'No cuenta con permisos para crear un rol.']);

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
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del rol']);
        
        // TODO: Validación si el usuario tiene permisos para editar
        if ( Auth::user()->role_id > 2 )
            return response()->json(['error' => true, 'message' => 'No cuenta con permisos para editar un rol.']);

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
        $user_roles = User::where('role_id', $request->get('id'))->first();
        if ( $user_roles )
            return response()->json(['error' => true, 'message' => 'No se puede eliminar el rol especificado porque hay usuarios con este rol.']);

        // // TODO: Solo el que puede eliminar es el super administrador
        if ( Auth::user()->role_id > 1 )
            return response()->json(['error' => true, 'message' => 'No cuenta con permisos para eliminar un rol.']);

        $role->delete();

        return response()->json(['error' => false, 'message' => 'Rol eliminado correctamente.']);

    }
    
    public function getRoles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }
}
