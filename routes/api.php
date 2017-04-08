<?php

Route::post('/login', 'Api\LoginController@attempt');
Route::get('/profile', 'Api\UserController@getProfile');

// Informs and reports
Route::get('/informs', 'Api\InformController@byUserLocation');
Route::get('/reports', 'Api\ReportController@byInform');

// Spinner options
Route::get('/work-fronts', 'Api\WorkFrontController@byUserLocation');
Route::get('/areas', 'Api\AreaController@all');
Route::get('/responsible-users', 'Api\UserController@byUserLocation');
Route::get('/critical-risks', 'Api\CriticalRiskController@all');

// New report
Route::post('/reports', 'Api\ReportController@store');
