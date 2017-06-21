<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function verify($code)
    {
        $user = User::where('confirmation_code', $code)->first();
        if (! $user)
            return redirect('/');

        $user->confirmed = true;
        $user->confirmation_code = null;
        $user->save();

        auth()->login($user); // authenticate a user instance
        return redirect('/login');
    }
}
