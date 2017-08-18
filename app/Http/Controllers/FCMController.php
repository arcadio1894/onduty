<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class FCMController extends Controller
{

    public function update(Request $request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'fcm_token' => 'required'
        ];
        $this->validate($request, $rules);

        $user = User::find($request->input('user_id'));
        $user->fcm_token = $request->input('fcm_token');
        return $user->save();
    }

}
