<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RefController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\ParamController;
use App\Http\Controllers\PontoController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminAlertController;

use App\Http\Controllers\Auth\RegisterController;
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

// Default auth routes -> public routes
Auth::routes(['verify' => true]);

// request invite
Route::get('/request', [RegisterController::class, 'inviteRequest'])->name('invite.request'); // request
// post request invite
Route::post('/request', [RegisterController::class, 'inviteStore'])->name('invite.store'); //storeRequest
// shows registration form
Route::get('/register', [RegisterController::class, 'inviteRegisterForm'])->name('register')->middleware('hasInvite');


// ============================== AUTH ROUTES ==============================
// dashboard home
Route::get('/', [DashboardController::class, 'home'])->name('home');

// ============================== POINTS (teams) ==============================
// get points
Route::get('/pontos', [PontoController::class, 'index'])->name('pontos.index');
// get point
Route::get('/pontos/{pontoid}', [PontoController::class, 'info'])->name('pontos.info')->whereNumber('pontoid');
// create point
Route::get('/pontos/create', [PontoController::class, 'create'])->name('pontos.create');
Route::post('/pontos/create', [PontoController::class, 'store'])->name('pontos.store');
// edit point
Route::get('/pontos/{pontoid}/edit', [PontoController::class, 'edit'])->name('pontos.edit')->whereNumber('pontoid');
Route::patch('/pontos/{pontoid}', [PontoController::class, 'patch'])->name('pontos.patch')->whereNumber('pontoid');

// ============================== PARAMS ==============================
// manage params
Route::get('/params/{refid}', [ParamController::class, 'edit'])->name('params.edit')->whereNumber('refid');
Route::patch('/params/{refid}', [ParamController::class, 'patch'])->name('params.patch')->whereNumber('refid');

// ============================== REFS ==============================
// create ref
Route::get('/ref/create/{teamid}', [RefController::class, 'create'])->name('refs.create')->whereNumber('teamid');
Route::post('/ref/create/{teamid}', [RefController::class, 'store'])->name('refs.create')->whereNumber('teamid');
// delete ref
Route::delete('/ref/{refid}', [RefController::class, 'destroy'])->name('refs.destroy')->whereNumber('refid');
// edit ref
Route::get('/ref/{refid}', [RefController::class, 'edit'])->name('refs.edit')->whereNumber('refid');
Route::patch('/ref/{refid}', [RefController::class, 'patch'])->name('refs.patch')->whereNumber('refid');

// ============================== ALERTS ==============================
// create alert
Route::get('/alert/create/{refid}', [AlertController::class, 'create'])->name('alert.create')->whereNumber('refid');
Route::post('/alert/create/{refid}', [AlertController::class, 'store'])->name('alert.store')->whereNumber('refid');
// delete alert
Route::delete('/alert/{alertid}', [AlertController::class, 'destroy'])->name('alert.destroy');
// edit alert
Route::get('/alert/{alertid}/edit', [AlertController::class, 'edit'])->name('alert.edit')->whereNumber('alertid');
Route::patch('/alert/{alertid}', [AlertController::class, 'update'])->name('alert.patch')->whereNumber('alertid');
// toggle alert
Route::get('/alert/{alertid}/toggle', [AlertController::class, 'toggle'])->name('alert.toggle')->whereNumber('alertid');

// ============================== CHARTS ==============================

Route::get('/charts/live/{chart}/{teamid}/{refid?}/{paramid?}',[DataController::class, 'live'])->name('live')
->where(['chart' => '[a-z]+', 'teamid' => '[0-9]+', 'refid' => '[0-9]+', 'paramid' => '[0-9]+']);

Route::post('/charts/genroute',[DataController::class, 'genRoute'])->name('genroute');

Route::post('/charts/live/{teamid}/{refid?}/{paramid?}',[DataController::class, 'livepost'])->name('livepost')
->where(['teamid' => '[0-9]+', 'refid' => '[0-9]+', 'paramid' => '[0-9]+']);

// general charts
Route::get('/charts/{chart}/{teamid}',[DataController::class, 'charts'])->name('charts')
        ->where(['chart' => '[a-z]+', 'teamid' => '[0-9]+']);

// charts post to define the date picker
Route::post('/charts/{chart}/{teamid}',[DataController::class, 'charts'])
        ->where(['chart' => '[a-z]+', 'teamid' => '[0-9]+']);

// ============================== TABLES ==============================
// raw table
Route::get('/raw/{teamid}',[DataController::class, 'raw'])->whereNumber('teamid')->name('raw');
Route::post('/raw/{teamid}',[DataController::class, 'raw'])->whereNumber('teamid');
// formated table
Route::get('/table/{teamid}',[DataController::class, 'table'])->whereNumber('teamid')->name('table');
Route::post('/table/{teamid}',[DataController::class, 'table'])->whereNumber('teamid');

 // ============================== PROFILE ==============================
Route::get('definicoes', [ProfileController::class, 'index'])->name('profile.definicoes');
Route::patch('definicoes/password', [ProfileController::class, 'passwordUpdate'])->name('profile.password');
Route::patch('perfil', [ProfileController::class, 'profileUpdate'])->name('profile.update');

// ============================== ADMIN ==============================
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function(){
    // admin manage users
    Route::resource('users', UserController::class, ['except' => ['create', 'store', 'show']]);
    // admin manage teams (pontos de recolha)
    Route::resource('teams', TeamController::class, ['except' => ['create', 'store', 'show']]);
    // admin manage alerts
    Route::resource('alerts', AdminAlertController::class, ['except' => ['create', 'store', 'show']]);
    Route::get('/alert/{alertid}/toggle', [AdminAlertController::class, 'toggle'])->name('alerts.toggle')->whereNumber('alertid');

    // ============================== INVITES ==============================
    Route::get('/invite', [InviteController::class, 'index'])->name('invite.index');
    Route::post('/invite', [InviteController::class, 'create'])->name('invite.create');
    Route::post('/invite/{id}', [InviteController::class, 'notify'])->name('invite.notify');
    Route::delete('/invite/{id}', [InviteController::class, 'destroy'])->name('invite.destroy');
});