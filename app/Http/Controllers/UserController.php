<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::where('id', '<>', 1)->get();
        // dd($users);
        return view('user.index')->with(compact('users', 'roles'));
    }

    public function store( Request $request )
    {
        // TODO: Solo puede crear usuarios el de rol super administrador que es el rol 1

        if ( Auth::user()->role_id > 2 )
            return response()->json(['error' => true, 'message' => 'No cuenta con permisos para crear un usuario.']);
        
        $validator = Validator::make($request->all(), [ 'image'=>'image' ]);
        if ( $validator->fails() )
            return response()->json(['error' => true, 'message' => 'Solo se permiten imágenes']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del usuario']);

        if ($request->get('email') == null OR $request->get('email') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el email del usuario']);

        if ($request->get('password') == null OR $request->get('password') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el password del usuario']);

        if ( strlen($request->get('password')) < 6 )
            return response()->json(['error' => true, 'message' => 'Ingrese una constraseña de 6 dígitos o más.']);

        if ($request->get('role') == null OR $request->get('role') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el rol del usuario']);

        /*if( $request->file('image') == null OR $request->file('image') == "" )
            return response()->json(['error' => true, 'message' => 'Es necesario escoger una imagen del usuario']);*/

        $request['confirmation_code'] = str_random(25);
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'role_id' => $request->get('role'),
            'enable' => '1',
            'confirmation_code' => $request->get('confirmation_code')
        ]);

        if ($request->file('image'))
        {
            $path = public_path().'/images/users';
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = $user->id . '.' . $extension;
            $request->file('image')->move($path, $fileName);
            $user->image = $request->file('image')->getClientOriginalExtension();
        }
        else
            $user->image = null;

        Mail::send('emails.confirm', $request->all(), function ($m) use ($request) {
            $m->subject('Correo de confirmación');
            $m->to($request->get('email'));
        });

        $user->save();
        return response()->json(['error' => false, 'message' => 'Usuario registrado correctamente']);
    }

    public function verify($code)
    {
        $user = User::where('confirmation_code', $code)->first();
        if (! $user)
            return redirect('/');

        $user->confirmed = true;
        $user->confirmation_code = null;
        $user->save();

        return redirect('/login');
    }

    public function edit( Request $request )
    {
        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del usuario']);

        // TODO: Validación que solo pueda bajar o subir de rol el super administrador
        if ( Auth::user()->role_id > 2 )
            return response()->json(['error' => true, 'message' => 'No cuenta con permisos para editar un usuario.']);

        // TODO: Los administradores no pueden editar de otros administradores
        $user_edited = User::find( $request->get('id') );
        if ( Auth::user()->role_id == 2 && $user_edited->role_id <= 2 && $user_edited->id != Auth::user()->id )
            return response()->json(['error' => true, 'message' => 'No cuenta con permisos para editar a otro administrador.']);

        // TODO: Los administradores no se pueden aumentar de nivel
        if ( Auth::user()->role_id > $request->get('role') )
            return response()->json(['error' => true, 'message' => 'No cuenta con permisos para aumentarse de rol.']);


        if ($request->get('role') == null OR $request->get('role') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el rol del usuario']);

        $user = User::find( $request->get('id') );
        $user->name = $request->get('name');
        $user->role_id = $request->get('role');

        if ($request->get('password') != "")
            $user->password = bcrypt($request->get('password'));

        $user->save();

        return response()->json(['error' => false, 'message' => 'Usuario modificado correctamente']);
    }

    public function delete( Request $request )
    {
        $user = User::find($request->get('id'));

        // TODO: Solo el que puede eliminar es el super administrador
        if (Auth::user()->role_id > 1)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para eliminar el usuario especificado.']);

        if($user == null)
            return response()->json(['error' => true, 'message' => 'No existe el usuario especificado.']);

        // TODO: Validación si tiene plantas

        $user->delete();

        return response()->json(['error' => false, 'message' => 'Usuario eliminado correctamente.']);

    }

}
