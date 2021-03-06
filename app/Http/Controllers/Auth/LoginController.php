<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    */

    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    protected function validateLogin(Request $request)
    {
        $messages = [
            $this->username().'.required' => 'Olvidó ingresar su e-mail.',
            'password.required' => 'Olvidó ingresar su contraseña.'
        ];

        $this->validate($request, [
            $this->username() => 'required',
            'password' => 'required'
        ], $messages);
    }

    // override this function to add additional condition
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $credentials['confirmed'] = 1; // add additional verification to the login
        return $credentials;
    }
    
}
