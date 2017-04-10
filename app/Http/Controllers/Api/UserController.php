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

        if ($user->image)
            $data['image'] = asset('images/users/'.$user->id.'.'.$user->image);
        else
            $data['image'] = asset('images/users/default.jpg');

        return $data;
    }

    public function byUserLocation(Request $request)
    {
        // get the location where the user belongs
        $user = User::find($request->input('user_id'));
        $location_id = $user->location_id;

        // and the possible responsible uses for that location
        $users = User::where('id', '<>', 1)
            ->where('location_id', $location_id)
            ->get([
                'id',
                'name',
                'email',
                'position_id'
            ]);

        foreach ($users as $user) {
            $position = $user->position;
            $user->position_name = $position->name;
            $user->department_name = $position->department ? $position->department->name : '';

            unset($user->position);
            unset($user->position_id);
        }

        return $users;
    }
}
