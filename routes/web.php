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
    // ユーザ登録
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

// ログイン認証
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', 'TasksController@index');

Route::group(['middleware' => 'auth'], function () {

Route::resource('categories', 'CategoriesController',['only' => ['create','store']]);
Route::get('categories/edit', 'CategoriesController@edit')->name('categories.edit');
Route::delete('categories/destroy', 'CategoriesController@destroy')->name('categories.destroy');

Route::resource('tasks', 'TasksController',['only' => ['index','show','update','create','store', 'destroy']]);
Route::put('tasks/{id}/edit', 'TasksController@edit')->name('tasks.edit');
Route::put('tasks/{id}/finish', 'TasksController@finish')->name('tasks.finish');

Route::resource('starts', 'StartsController',['only' => ['update']]);
Route::resource('stops', 'StopsController',['only' => ['update']]);
Route::get('tasks/{id}/start', 'StartsController@create')->name('starts.create');
Route::get('tasks/{id}/stop', 'StopsController@create')->name('stops.create');
});