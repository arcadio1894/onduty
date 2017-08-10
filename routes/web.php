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

Route::get('/', 'GuestController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::post('/user/image', 'DataController@postProfileImage');

Route::get('/locations', 'LocationController@index');
Route::post('/location/register', 'LocationController@store');
Route::post('/location/editar', 'LocationController@edit');
Route::post('/location/delete', 'LocationController@delete');
Route::get('/locations/users', 'UserController@getLocations');
Route::get('/position/department/{id_department}', 'UserController@getPositionsDepartment');
Route::get('/department/user', 'UserController@getDepartments');

Route::get('/location/{id}/work-fronts', 'WorkFrontController@index');
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

Route::get('/critical-risks', 'CriticalRiskController@index');
Route::post('/critical-risk/register', 'CriticalRiskController@store');
Route::post('/critical-risk/editar', 'CriticalRiskController@edit');
Route::post('/critical-risk/delete', 'CriticalRiskController@delete');

Route::get('/positions', 'PositionController@index');
Route::post('/position/register', 'PositionController@store');
Route::post('/position/editar', 'PositionController@edit');
Route::post('/position/delete', 'PositionController@delete');
Route::get('/positions/users', 'UserController@getPositions');

Route::get('/informes', 'InformeController@index');
Route::get('/informes/general', 'InformeController@general');
Route::post('/informe/register', 'InformeController@store');
Route::post('/informe/edit', 'InformeController@edit');
Route::post('/informe/delete', 'InformeController@delete');
Route::get('/graphics/informe/{id}', 'InformeController@graphics');

Route::get('/work-fronts/graph/{informe_id}', 'InformeController@getWorkFrontsGraph');
Route::get('/critical-risks/graph/{informe_id}', 'InformeController@getCriticalRisksGraph');
Route::get('/areas/graph/{informe_id}', 'InformeController@getAreasGraph');
Route::get('/responsible/graph/{informe_id}', 'InformeController@getResponsibleGraph');
Route::get('/work-fronts-opens/graph/{informe_id}', 'InformeController@getWorkFrontOpensGraph');
Route::get('/responsible-opens/graph/{informe_id}', 'InformeController@getOpenResponsibleGraph');

Route::get('/reports/informe/{id}', 'ReportController@index');
Route::get('/register/report/{id}', 'ReportController@create');
Route::get('/informes/users', 'ReportController@getUsers');
Route::get('/informes/locations', 'ReportController@getLocations');
Route::post('/report/register', 'ReportController@store');
Route::post('/report/edit', 'ReportController@edit');
Route::post('/report/delete', 'ReportController@delete');
Route::get('/edit/informe/report/{informe_id}/{report_id}', 'ReportController@showEdit');

Route::get('/observations/informe/{id}', 'ObservationController@index');
Route::post('/observation/register', 'ObservationController@store');
Route::post('/observation/edit', 'ObservationController@edit');
Route::post('/observation/delete', 'ObservationController@delete');

Route::get('/departments', 'DepartmentController@index');
Route::post('/department/register', 'DepartmentController@store');
Route::post('/department/editar', 'DepartmentController@edit');
Route::post('/department/delete', 'DepartmentController@delete');
Route::get('/department/user/{idPosition}', 'DepartmentController@getDepartment');

// Confirmation email
Route::get('/register/verify/{code}', 'GuestController@verify');

// Reports in excel
Route::get('/excel/informe/{id_informe}', 'ExcelController@getReportsExcel');