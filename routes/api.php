<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RefController;
use App\Http\Controllers\ParamController;
use App\Http\Controllers\DataController;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
    //return $request->user();
//});

Route::prefix('EcoModZHC')->group(function () {
    // Ref
    Route::get(
        'ref',
        'App\Http\Controllers\RefController@listRefs'
    )->withoutMiddleware('auth');
    Route::get(
        'ref/team/{teamId}',
        'App\Http\Controllers\RefController@listRefsInTeam'
    )->withoutMiddleware('auth');
    Route::get(
        'ref/{id}',
        'App\Http\Controllers\RefController@singleRef'
    )->withoutMiddleware('auth');

    // Param
    Route::get(
        'param',
        'App\Http\Controllers\ParamController@listParams'
    )->withoutMiddleware('auth');
    Route::get(
        'param/ref/{refId}',
        'App\Http\Controllers\ParamController@listParamsInRef'
    )->withoutMiddleware('auth');
    Route::get(
        'param/{id}',
        'App\Http\Controllers\ParamController@singleParam'
    )->withoutMiddleware('auth');

    // Data
    Route::get(
        'data/{id}',
        'App\Http\Controllers\DataController@listData'
    )->withoutMiddleware('auth');
});
