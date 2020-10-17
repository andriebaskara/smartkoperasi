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
/*
Route::get('/', function () {
    return view('welcome');
});
*/


Route::get('/', 'Auth\LoginController@showLoginForm');
Auth::routes(); /*standar untuk login, logout, lupa password, register */

Route::get('/home', 'HomeController@index')->name('home'); /*halaman dashboard */



Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_loaddatatable', ['uses' => 'Admin\RolesController@loaddatatable', 'as' => 'roles.loaddatatable']);
    Route::post('roles_hapusdata', ['uses' => 'Admin\RolesController@hapusdata', 'as' => 'roles.hapusdata']);
    Route::post('roles_hapusdipilih', ['uses' => 'Admin\RolesController@hapusdipilih', 'as' => 'roles.hapusdipilih']);



    Route::resource('users', 'Admin\UsersController');
    Route::post('users_loaddatatable', ['uses' => 'Admin\UsersController@loaddatatable', 'as' => 'users.loaddatatable']);
    Route::post('users_hapusdata', ['uses' => 'Admin\UsersController@hapusdata', 'as' => 'users.hapusdata']);
    Route::post('users_hapusdipilih', ['uses' => 'Admin\UsersController@hapusdipilih', 'as' => 'users.hapusdipilih']);

    Route::get('user/roles/{user}', ['uses' => 'Admin\UsersController@rolesuser', 'as' => 'users.roles']);
    Route::post('user/simpanroles', ['uses' => 'Admin\UsersController@simpanrolesuser', 'as' => 'users.simpanroles']);
    Route::get('user/permissions/{user}', ['uses' => 'Admin\UsersController@permissionsuser', 'as' => 'users.permissions']);
    Route::post('user/simpanpermissions', ['uses' => 'Admin\UsersController@simpanpermissionsuser', 'as' => 'users.simpanpermissions']);

    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {

        //lokasi
        Route::resource('lokasi', 'Admin\UsersController');
        //tipe
        Route::resource('tipe_iuran', 'Admin\UsersController');

	});



});




