<?php

namespace App\Http\Controllers;

use App\Position;
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
        $users = User::with('role')->with('position')->get();
        $roles = Role::where('id', '<>', 1)->get();
        $positions = Position::where('id', '<>', 1)->get();
        // dd($users);
        return view('user.index')->with(compact('users', 'roles', 'positions'));
    }

    public function store( Request $request )
    {
        // TODO: Solo puede crear usuarios el de rol super administrador que es el rol 1
        $rules = array(
            'name' => 'required|min:2',
            'password'=> 'required|min:6',
            'role' => 'required',
        );

        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del área',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
            'password.required'=>'Es necesario indicar el password',
            'password.min'=>'El password debe tener por lo menos 6 caracteres',
            'role.required'=>'Es necesario ingresar el role del usuario',
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) use ($request) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para crear un usuario');
            }

            $email_user = User::where('email', $request->get('email'))->first();
            if ( $email_user )
                $validator->errors()->add('user', 'Ya existe un usuario con este email');
        });

        if(!$validator->fails()) {
            $request['confirmation_code'] = str_random(25);
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
                'role_id' => $request->get('role'),
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
            {
                $user->image = null;
            }

            if ( $request->get('position') == null OR $request->get('role')==4 )
            {
                $user->position_id = 1;
            }else{
                $user->position_id = $request->get('position');
            }

            /*Mail::send('emails.confirm', $request->all(), function ($m) use ($request) {
                $m->subject('Correo de confirmación');
                $m->to($request->get('email'));
            });*/

            $user->save();
        }

        return response()->json($validator->messages(), 200);
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
        //dd($request->get('position_select'));
        $rules = array(
            'name' => 'required|min:2',
            'role' => 'required',
        );

        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del área',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
            'role.required'=>'Es necesario ingresar el role del usuario',
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) use ($request) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para editar un usuario');
            }

            $email_user = User::where('email', $request->get('email'))->first();
            if ( $email_user )
                $validator->errors()->add('userEmail', 'Ya existe un usuario con este email');

            if ($request->get('position_select') == "" AND $request->get('role')!=4)
                $validator->errors()->add('positionUser', 'Es necesario ingresar el cargo del usuario');

            // TODO: Los administradores no pueden editar de otros administradores
            $user_edited = User::find( $request->get('id') );
            if ( Auth::user()->role_id == 2 && $user_edited->role_id <= 2 && $user_edited->id != Auth::user()->id )
                $validator->errors()->add('edited', 'No cuenta con permisos para editar a otro administrador.');

            // TODO: Los administradores no se pueden aumentar de nivel
            if ( Auth::user()->role_id > $request->get('role') )
                $validator->errors()->add('aument', 'No cuenta con permisos para aumentarse de rol.');

        });

        if(!$validator->fails()) {
            $user = User::find( $request->get('id') );
            $user->name = $request->get('name');
            $user->role_id = $request->get('role');

            if ($request->get('password') != "")
                $user->password = bcrypt($request->get('password'));

            if ( $request->get('position_select') == null OR $request->get('role_id')==4)
            {
                $user->position_id = 1;
            }else{

                $user->position_id = $request->get('position_select');
            }

            $user->save();
        }


        return response()->json($validator->messages(), 200);
    }

    public function delete( Request $request )
    {
        $rules = array(
            'id' => 'exists:areas'
        );

        $messsages = array(
            'id.exists'=>'No existe el usuario especificada',
        );

        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para eliminar un usuario');
            }
        });

        if(!$validator->fails()) {
            $user = User::find($request->get('id'));
            $user->delete();
        }


        return response()->json($validator->messages(), 200);

    }

    public function getPositions()
    {
        $positions = Position::where('id', '<>', 1)->get();
        return response()->json($positions);
    }

}
