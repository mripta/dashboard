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
    // Ex.: http://localhost:80/api/EcoModZHC/ref
    Route::get(
        'ref',
        'App\Http\Controllers\RefController@listRefs'
    )->withoutMiddleware('auth');
    // Ex.: http://localhost:80/api/EcoModZHC/ref/team/1
    Route::get(
        'ref/team/{teamId}',
        'App\Http\Controllers\RefController@listRefsInTeam'
    )->withoutMiddleware('auth');
    // Ex.: http://localhost:80/api/EcoModZHC/ref/1
    Route::get(
        'ref/{id}',
        'App\Http\Controllers\RefController@singleRef'
    )->withoutMiddleware('auth');

    // Param
    // Ex.: http://localhost:80/api/EcoModZHC/param
    Route::get(
        'param',
        'App\Http\Controllers\ParamController@listParams'
    )->withoutMiddleware('auth');
    // Ex.: http://localhost:80/api/EcoModZHC/param/ref/1
    Route::get(
        'param/ref/{refId}',
        'App\Http\Controllers\ParamController@listParamsInRef'
    )->withoutMiddleware('auth');
    // Ex.: http://localhost:80/api/EcoModZHC/param/1
    Route::get(
        'param/{id}',
        'App\Http\Controllers\ParamController@singleParam'
    )->withoutMiddleware('auth');

    // Data
    // Ex.: http://localhost:80/api/EcoModZHC/data/1
    Route::get(
        'data/{id}',
        'App\Http\Controllers\DataController@listData'
    )->withoutMiddleware('auth');

    // Ex.: http://localhost:80/api/EcoModZHC/data/1/ref/optod
    Route::get(
        'data/{id}/ref/{ref}',
        'App\Http\Controllers\DataController@listDataGivenRef'
    )->withoutMiddleware('auth');
});
