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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'API\UserController@login');
Route::get('fire', function (Request $request) {
    return 'awesome';
});

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('user', 'API\UserController@index')->middleware('scope:user.list');
    Route::get('user/{id}', 'API\UserController@get')->middleware('scope:user.list');
    Route::post('user', 'API\UserController@post')->middleware('scope:user.create');
    Route::put('user/{id}', 'API\UserController@put')->middleware('scope:user.edit');
    Route::delete('user/{id}', 'API\UserController@delete')->middleware('scope:user.delete');

    Route::get('permission/list', 'API\PermissionController@index');
    Route::get('permission/list/{id}', 'API\PermissionController@get');
    Route::get('logout', 'API\UserController@logout');
});
