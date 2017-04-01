<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = User::find($request->input('user_id'));

        $data = [];
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['rol'] = $user->role->name;
        $position = $user->position;
        $data['department'] = $position->department->name;
        $data['position'] = $position->name;
        $data['location'] = $user->location->name;

        return $data;
    }
}
