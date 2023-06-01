<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TournamentController;
use App\Models\Tournament;
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
Route::get('/players/free',[PlayerController::class,'getFreePlayers']);
Route::get('/teams',[TeamController::class,'getTeams']);
Route::get('/tournaments',[TournamentController::class,'getTournaments']);
Route::get('/tournaments/open',[TournamentController::class,'getOpenTournaments']);
Route::get('/tournaments/{id}/games',[GameController::class,'getGamesByTournament'])->where('id','[0-9]+');;


Route::post('/players/new',[PlayerController::class,'createNewPlayer']);
Route::post('/teams/new',[TeamController::class,'createNewTeam']);
Route::post('/tournaments/new',[TournamentController::class,'createNewTournament']);


Route::put('/players/{id}/edit',[PlayerController::class,'editPlayerName'])->where('id','[0-9]+');
Route::put('/teams/{id}/edit',[TeamController::class,'editTeamName'])->where('id','[0-9]+');
Route::put('/tournaments/start',[TournamentController::class,'startTournament']);
Route::put('/tournaments/{id}/edit',[TournamentController::class,'editTournamentName'])->where('id','[0-9]+');;
Route::put('/tournament/game/play',[GameController::class,'playGame']);













