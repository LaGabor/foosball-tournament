<?php

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/players',[PlayerController::class,'getPlayers']);
Route::get('/teams',[TeamController::class,'getTeams']);
Route::get('/players/free',[PlayerController::class,'getFreePlayers']);



Route::post('/players/new',[PlayerController::class,'createNewPlayer']);
Route::post('/teams/new',[TeamController::class,'createNewTeam']);











