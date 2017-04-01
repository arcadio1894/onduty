<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function attempt()
    {
    	$auth = false;
    	$credentials = $request->only('email', 'password');

    	if (auth()->attempt($credentials, $request->has('remember'))) {
	        $auth = true;
	    }

	    $data['success'] = $auth;
	    $data['user_id'] = auth()->user() ? auth()->user()->id : 0;
		
		return $data;
    }
}
