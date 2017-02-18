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

Route::get('/plants/location/{id}', 'PlantController@index');
Route::post('/plant/register', 'PlantController@store');
Route::post('/plant/editar', 'PlantController@edit');
Route::post('/plant/delete', 'PlantController@delete');

Route::get('/workFronts/plant/{id}', 'WorkFrontController@index');
Route::post('/workFront/register', 'WorkFrontController@store');
Route::post('/workFront/editar', 'WorkFrontController@edit');
Route::post('/workFront/delete', 'WorkFrontController@delete');

Route::get('/areas', 'AreaController@index');
Route::post('/area/register', 'AreaController@store');
Route::post('/area/editar', 'AreaController@edit');
Route::post('/area/delete', 'AreaController@delete');

