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

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('clear-compiled');
   // Artisan::call('modelCache:clear');
    //flash_success('操作成功');

    //return redirect()->back();
    return ['ok'];
});

Route::get('/', 'App\Http\Controllers\StaticPagesController@home')->name('home');
Route::get('/faq', 'App\Http\Controllers\StaticPagesController@help')->name('help');
Route::get('/about', 'App\Http\Controllers\StaticPagesController@about')->name('about');

Route::get('/signup', 'App\Http\Controllers\UsersController@create')->name('signup');

Route::resource('users', 'App\Http\Controllers\UsersController');

Route::get('login', 'App\Http\Controllers\SessionsController@create')->name('login');
Route::post('login', 'App\Http\Controllers\SessionsController@store')->name('login');
Route::delete('logout', 'App\Http\Controllers\SessionsController@destroy')->name('logout');

Route::get('/signup/confirm/{token}', 'App\Http\Controllers\UsersController@confirmEmail')->name('confirm_email');
