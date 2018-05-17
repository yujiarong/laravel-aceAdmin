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
     return redirect('/home');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/calOrder','DataController@calOrder')->name('calOrder');
$this->get('logout', 'Auth\LoginController@logout')->name('logout');



Route::group([
    'middleware' => ['permission:admin','auth']
], function () {
	Route::get('/user/index', 'UserController@index')->name('user.index');
	Route::any('/user/tableGet', 'UserController@tableGet')->name('user.tableGet');
	Route::any('/user/delete', 'UserController@delete')->name('user.delete');
});  




Route::group(['middleware' => ['permission:admin;saler','auth']], function () {
    includeRouteFiles(__DIR__.'/Backend/');
});