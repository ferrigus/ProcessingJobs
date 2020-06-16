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


Route::group(['middleware' => 'api'], function($app){
	$app->group(['prefix' => 'submitters'], function($app){
		$app->get('/', 'SubmitterController@index');
		$app->get('/{id}', 'SubmitterController@show');
	});

	$app->group(['prefix' => 'processors'], function($app){
		$app->get('/', 'ProcessorController@index');
		$app->get('/{id}', 'ProcessorController@show');
	});

	$app->group(['prefix' => 'job_lists'], function($app){
		$app->post('/store', 'JobListController@store');
		$app->post('update/{id}', 'JobListController@update');
		$app->get('/avaliable_jobs/', 'JobListController@avaliable_jobs');
	});
});


/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
