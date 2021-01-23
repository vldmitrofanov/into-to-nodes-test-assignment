<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/experts', 'API\ExpertsController@getAll');
Route::post('/experts', 'API\ExpertsController@create');

Route::get('/clients', 'API\ClientsController@getAll');

Route::get('/availabilities', 'API\AvailabilitiesController@getAll');

Route::post('/bookings/auto-set/{client_id}', 'API\BookingsController@autoSet');
Route::post('/bookings/unset-all/{client_id}', 'API\BookingsController@unsetAll');
