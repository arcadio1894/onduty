<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function attempt(Request $request)
    {
    	$auth = false;
    	$credentials = $request->only('email', 'password');

    	if (auth()->attempt($credentials, $request->has('remember'))) {
	        $auth = true;
	    }

	    $data['success'] = $auth;
	    $user = auth()->user();
	    if ($user) {
	    	$data['user_id'] =  $user->id;
	    	$data['is_admin'] =  $user->role_id <= 2; // 1: SuperAdmin | 2: Admin
	    } else {
			$data['user_id'] = 0;
			$data['is_admin'] = false;
	    }
	    
		return $data;
    }
}
