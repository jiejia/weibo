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
    return response(['code' => 200]);
});


Route::get('/', 'App\Http\Controllers\StaticPagesController@home');
Route::get('/help', 'App\Http\Controllers\StaticPagesController@help');
Route::get('/about', 'App\Http\Controllers\StaticPagesController@about');
