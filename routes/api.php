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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();

});

*/

Route::group(['middleware' => ['api', 'public'], 'prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {
	Route::post('register', ['uses' => 'RegisterController@register', 'as' => 'register']);
	
	Route::group(['middleware' => ['anggota']], function () {

		Route::post('anggota', ['uses' => 'AnggotaController@anggota', 'as' => 'anggota']);

		/*
		Route::post('joinagenda', ['uses' => 'RegisterController@joinagenda', 'as' => 'joinagenda']);
		Route::post('upload_user_image', ['uses' => 'RegisterController@upload_user_image', 'as' => 'upload_user_image']);
		Route::post('download_user_image', ['uses' => 'RegisterController@download_user_image', 'as' => 'download_user_image']);
		*/


	});


});
