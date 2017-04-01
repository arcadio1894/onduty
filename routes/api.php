<?php

use Illuminate\Http\Request;

Route::get('/login', 'Api\LoginController@attempt');
