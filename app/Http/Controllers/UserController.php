<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('enable', 1)->with('role')->get();
        //dd($users);
        return view('user.index')->with(compact('users'));
    }
}
