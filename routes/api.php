<?php

use Illuminate\Http\Request;

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

Route::get('login', 'API\UserController@login');
Route::group(['middleware' => 'auth:api'], function(){
	
	// Dibawah ini adalah route yang di lindungi oleh middleware auth:api
	// Jadi ketika akan mengakses route di bawah ini harus memiliki header
	
	// Accept : application/json
	// Authorization : Bearer <api_token>
	
	// ex : 
	// Authorization : Bearer 0398c0335fff849bba6ba9bc0e71b1b557f26d4f9ab0412abbd5a56b6c2d5db6
	
	Route::post('details', 'API\UserController@details');
	Route::get('/getDashboard3','RestController@getDashboard2' );
	Route::get('/getDashboard4','RestController@getDashboard2' );
	Route::get('/getDashboard5','RestController@getDashboard2' );

});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:api')->get('/getDashboard2','RestController@getDashboard2' );

// Route::get('job',function(){
// 	return App\Job::all();
// });

