<?php

Route::post('/login', 'Api\LoginController@attempt');
Route::get('/profile', 'Api\UserController@getProfile');

Route::get('/informs', 'Api\UserController@get');
