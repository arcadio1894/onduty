<?php

namespace App\Http\Controllers;

use App\Department;
use App\Location;
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
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::withTrashed()->orderBy('name')->get();
        $roles = Role::where('id', '<>', 1)->get();
        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('user.index')->with(compact('users', 'roles', 'departments', 'locations'));
    }

    public function store( Request $request )
    {
        //dd($request->all());
        $rules = [
            'name' => 'required|min:2',
            'password'=> 'required|min:6',
            'role' => 'required',
            'location-id' => 'required',
            'email' => 'required'
        ];
        $messages = [
            'name.required' => 'Es necesario ingresar el nombre del área',
            'name.min' => 'El nombre debe tener por lo menos 2 caracteres',
            'password.required' => 'Es necesario indicar el password',
            'password.min' => 'El password debe tener por lo menos 6 caracteres',
            'role.required' => 'Es necesario ingresar el rol del usuario',
            'location-id.required' => 'Es necesario escoger una localización para el usuario',
            'email.required' => 'Es necesario ingresar un email para el usuario'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para crear un usuario');
            }

            $email_user = User::where('email', $request->get('email'))->first();
            if ($email_user)
                $validator->errors()->add('user', 'Ya existe un usuario con este email');
        });

        if (! $validator->fails()) {
            $request['confirmation_code'] = str_random(25);
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
                'role_id' => $request->get('role'),
                'location_id'=> $request->get('location-id'),
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

            Mail::send('emails.confirm', $request->all(), function ($m) use ($request) {
                $m->subject('Correo de confirmación');
                $m->to($request->get('email'));
            });

            $user->save();
        }

        return response()->json($validator->messages(), 200);
    }

    public function edit( Request $request )
    {
        $rules = [
            'name' => 'required|min:2',
            'role' => 'required',
            'location_select'=> 'required'
        ];

        $messsages = array(
            'name.required'=>'Es necesario ingresar el nombre del área',
            'name.min'=>'El nombre debe tener por lo menos 2 caracteres',
            'role.required'=>'Es necesario ingresar el role del usuario',
            'location_select.required'=>'Es necesario ingresar una localización para el usuario',
        );
        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) use ($request) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para editar un usuario');
            }

            $email_user = User::where('email', $request->get('email'))->first();
            if ($email_user)
                $validator->errors()->add('userEmail', 'Ya existe un usuario con este email');

            if ($request->get('position_select') == "" AND $request->get('role')!=4)
                $validator->errors()->add('positionUser', 'Es necesario ingresar el cargo del usuario');

            // Los administradores no pueden editar a otros administradores
            $user_edited = User::find( $request->get('id') );
            if ( Auth::user()->role_id == 2 && $user_edited->role_id <= 2 && $user_edited->id != Auth::user()->id )
                $validator->errors()->add('edited', 'No cuenta con permisos para editar a otro administrador.');

            // Los administradores no se pueden aumentar de nivel
            if ( Auth::user()->role_id > $request->get('role') )
                $validator->errors()->add('aument', 'No cuenta con permisos para aumentarse de rol.');

        });

        if (! $validator->fails()) {
            $user = User::find( $request->get('id') );
            $user->name = $request->get('name');
            $user->role_id = $request->get('role');
            $user->location_id = $request->get('location_select');

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
        $rules = [
            'id' => 'exists:users'
        ];

        $messages = [
            'id.exists' => 'No existe el usuario indicado',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para activar/desactivar usuarios');
            }
        });

        if (! $validator->fails()) {
            $user = User::withTrashed()->find($request->get('id'));
            if ($user->trashed())
                $user->restore();
            else
                $user->delete();
        }

        return response()->json($validator->messages(), 200);
    }

    public function getPositions()
    {
        $positions = Position::where('id', '<>', 1)->get();
        return response()->json($positions);
    }

    public function getLocations()
    {
        $locations = Location::orderBy('name')->get(); // ascendant order by default
        return response()->json($locations);
    }
    
    public function getPositionsDepartment($id_department){
        $positions = Position::where('id', '<>', 1)
            ->where('department_id', $id_department)
            ->orderBy('name') // asc
            ->get();
        return response()->json($positions);
    }

    public function getDepartments()
    {
        $departments = Department::orderBy('name')->get(); // asc order by default (A->Z)
        return response()->json($departments);
    }

}
