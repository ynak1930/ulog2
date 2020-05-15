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

Route::resource('tasks', 'TasksController',['only' => ['index','show','create','store', 'destroy']]);
Route::put('tasks/{id}/edit', 'TasksController@edit')->name('tasks.edit');


Route::resource('starts', 'StartsController',['only' => ['update']]);
Route::resource('stops', 'StopsController',['only' => ['update']]);
Route::get('tasks/{id}/start', 'StartsController@create')->name('starts.create');
Route::get('tasks/{id}/stop', 'StopsController@create')->name('stops.create');

Route::resource('activities', 'ActivitiesController');

Route::put('tasks/{id}/finish', 'FinishesControllerController@create')->name('tasks.finish');
Route::put('tasks/{id}/finish_', 'FinishesControllerController@store')->name('finish.store');


Route::get('tasks/{id}/pause', 'PausesController@store')->name('pauses.store');

//Route::put('tasks/{id}/finish', 'TasksController@finish')->name('tasks.finish');ｺﾝﾄﾛｰﾗ整理
//Route::put('finishes.edit', 'FinishesController@edit')->name('finishes.edit');
//Route::resource('finishes', 'FinishesController',['only' => ['index']]);
});