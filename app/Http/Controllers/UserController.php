<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('enable', 1)->with('role')->get();
        $roles = Role::where('enable', 1)->get();
        //dd($users);
        return view('user.index')->with(compact('users', 'roles'));
    }

    public function store( Request $request )
    {
        $validator = Validator::make($request->all(), [ 'image'=>'image' ]);
        if ( $validator->fails() )
            return response()->json(['error' => true, 'message' => 'Solo se permiten imágenes']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del usuario']);

        if ($request->get('email') == null OR $request->get('email') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el email del usuario']);

        if ($request->get('password') == null OR $request->get('password') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el password del usuario']);

        if ($request->get('role') == null OR $request->get('role') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el rol del usuario']);

        if( $request->file('image') == null OR $request->file('image') == "" )
            return response()->json(['error' => true, 'message' => 'Es necesario escoger una imagen del usuario']);

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'role_id' => $request->get('role'),
            'enable' => '1',
            'image' => $request->file('image')->getClientOriginalExtension()
        ]);

        if( $request->file('image') )
        {
            $path = public_path().'/images/users';
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = $user->id . '.' . $extension;
            $request->file('image')->move($path, $fileName);
        }

        // TODO: Enviar email de confirmación

        $user->save();
        return response()->json(['error' => false, 'message' => 'Usuario registrado correctamente']);
    }

    public function delete( Request $request )
    {
        $user = User::find($request->get('id'));

        if (Auth::user()->id != 1)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para eliminar el usuario especificado.']);

        if($user == null)
            return response()->json(['error' => true, 'message' => 'No existe el usuario especificado.']);

        // TODO: Validación si tiene plantas

        $user->enable = 0;
        $user->save();

        return response()->json(['error' => false, 'message' => 'Usuario eliminado correctamente.']);

    }

}
