<?php

use Illuminate\Support\Facades\Route;

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
    return view('login');
});


Route::post('/user/login', 'CMS\LoginController@login');
Route::get('/user/list', 'CMS\UserController@index');
Route::get('/user/create', 'CMS\UserController@create');
Route::post('/user/create', 'CMS\UserController@post');
Route::get('/user/edit/{id}', 'CMS\UserController@get');
Route::post('/user/edit/{id}', 'CMS\UserController@put');
Route::get('/user/delete/{id}', 'CMS\UserController@delete');
Route::get('/logout', 'CMS\LoginController@logout');
