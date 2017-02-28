<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::post('/user/image', 'DataController@postProfileImage');

Route::get('/locations', 'LocationController@index');
Route::post('/location/register', 'LocationController@store');
Route::post('/location/editar', 'LocationController@edit');
Route::post('/location/delete', 'LocationController@delete');

Route::get('/workFronts/location/{id}', 'WorkFrontController@index');
Route::post('/workFront/register', 'WorkFrontController@store');
Route::post('/workFront/editar', 'WorkFrontController@edit');
Route::post('/workFront/delete', 'WorkFrontController@delete');

Route::get('/areas', 'AreaController@index');
Route::post('/area/register', 'AreaController@store');
Route::post('/area/editar', 'AreaController@edit');
Route::post('/area/delete', 'AreaController@delete');

Route::get('/roles', 'RoleController@index');
Route::get('/roles/users', 'RoleController@getRoles');
Route::post('/role/register', 'RoleController@store');
Route::post('/role/editar', 'RoleController@edit');
Route::post('/role/delete', 'RoleController@delete');

Route::get('/users', 'UserController@index');
Route::post('/user/register', 'UserController@store');
Route::post('/user/editar', 'UserController@edit');
Route::post('/user/delete', 'UserController@delete');

Route::get('/critical_risks', 'CriticalRiskController@index');
Route::post('/critical_risk/register', 'CriticalRiskController@store');
Route::post('/critical_risk/editar', 'CriticalRiskController@edit');
Route::post('/critical_risk/delete', 'CriticalRiskController@delete');

Route::get('/positions', 'PositionController@index');
Route::post('/position/register', 'PositionController@store');
Route::post('/position/editar', 'PositionController@edit');
Route::post('/position/delete', 'PositionController@delete');
Route::get('/positions/users', 'UserController@getPositions');

Route::get('/informes', 'InformeController@index');
Route::post('/informe/register', 'InformeController@store');
Route::post('/informe/edit', 'InformeController@edit');
Route::post('/informe/delete', 'InformeController@delete');

Route::get('/reports/informe/{id}', 'ReportController@index');
Route::get('/informes/users', 'ReportController@getUsers');
Route::get('/informes/locations', 'ReportController@getLocations');


// Confirmation email
Route::get('/register/verify/{code}', 'UserController@verify');