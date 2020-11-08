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

/**
 * 登录
 */
Route::get('login', 'App\Http\Controllers\SessionsController@create')->name('login');
Route::post('login', 'App\Http\Controllers\SessionsController@store')->name('login');
Route::delete('logout', 'App\Http\Controllers\SessionsController@destroy')->name('logout');

/**
 * 登录验证
 */
Route::get('/signup/confirm/{token}', 'App\Http\Controllers\UsersController@confirmEmail')->name('confirm_email');

/**
 * 重置密码
 */
Route::get('password/email', 'App\Http\Controllers\PasswordController@emailForm')->name('password.email_form');
Route::post('password/email', 'App\Http\Controllers\PasswordController@email')->name('password.email');
Route::get('password/reset/{token}', 'App\Http\Controllers\PasswordController@resetForm')->name('password.reset_form');
Route::post('password/reset', 'App\Http\Controllers\PasswordController@reset')->name('password.reset');

/**
 * 微博
 */
Route::resource('statuses', 'App\Http\Controllers\StatusesController', ['only' => ['store', 'destroy']]);

/**
 * 粉丝页面
 */
Route::get('/users/{user}/followings', 'App\Http\Controllers\UsersController@followings')->name('users.followings');
Route::get('/users/{user}/followers', 'App\Http\Controllers\UsersController@followers')->name('users.followers');

