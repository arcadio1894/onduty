<?php

Route::post('/login', 'Api\LoginController@attempt');
Route::get('/profile', 'Api\UserController@getProfile');

// FCM token
Route::post('/fcm/token', 'Api\FCMController@update');

// Informs and reports
Route::get('/informs', 'Api\InformController@byUserLocation');
Route::get('/reports', 'Api\ReportController@byInform');
Route::get('/reports/{id}', 'Api\ReportController@byId');

// Spinner options
Route::get('/work-fronts', 'Api\WorkFrontController@byUserLocation');
Route::get('/areas', 'Api\AreaController@all');
Route::get('/responsible-users', 'Api\UserController@byUserLocation');
Route::get('/critical-risks', 'Api\CriticalRiskController@all');

// New report
Route::post('/reports', 'Api\ReportController@store');
// Edit report
Route::post('/reports/{id}', 'Api\ReportController@update');

// New inform
Route::post('/informs', 'Api\InformController@store');