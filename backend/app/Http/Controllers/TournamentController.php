<?php

namespace App\Http\Controllers;

use App\Models\TeamTournament;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TournamentController extends Controller
{




    function createNewTournament(Request $request): Response
    {
        if (! self::getTournamentIdByName($request->name)) {
            $this->createTournament($request->name);
            return response(['message' => ["Tournament is created!"]], Response::HTTP_CREATED);
        }
        return response(['message' => ["Tournament with this name already exist!"]], 422);
    }

    function addAllTeamsToNewTournament($tournament): void
    {

        $this->createTeamsTournamentsPivots($tournament);

    }


    public static function isTournamentStarted($tournamentId){
        $points = DB::table('point_counts')
            ->where("tournament_id", $tournamentId)
            ->where('points', '>', 0)
            ->first();
        return $points;
    }


    public function getTournaments(Request $request){
        return Tournament::all();
    }

    public function getTournamentHistory(Request $request){
        return Tournament::where("is_started",true)->get();
    }

    public function getOpenTournaments(Request $request){
        return Tournament::where('is_ended',false)->where("is_started",true)->get();
    }

    public function startTournament(Request $request){
        $tournament = $this->getTournamentById($request->tournamentId);
        if(TeamController::checkAllTeamCount() <2){
            return response(['message' => ["Not Enough Team!"]], 422);
        };
        $this->addAllTeamsToNewTournament($tournament);
        (new GameController)->createGames($request->tournamentId);
        $this->tournament_is_started($request->tournamentId);

    }

    public function endTournament(Request $request){
        DB::table('tournaments')
            ->where('id', $request->tournamentId)
            ->update(['is_ended' => true]);
    }

    private function tournament_is_started($tournamentId){
        DB::table('tournaments')
            ->where('id', $tournamentId)
            ->update(['is_started' => true]);
    }

    public static function getTournamentIdByName($name) :?int
    {
        $tournament = Tournament::where('name', $name)->first();
        if($tournament){
            return $tournament->id;
        }
        return null;
    }

    private function createTournament($name) :Tournament
    {
        return Tournament::create([
            'name' => "$name"
        ]);

    }

    public static function getTournamentByID(int $tournamentId):Tournament
    {
        return Tournament::where('id',$tournamentId)->first();
    }


    private function hasGame($tournamentId):bool
    {
        $tournament = self::getTournamentByID($tournamentId);
        return $tournament->games()->exists();
    }

    public function createTeamsTournamentsPivots($newTournament):void{
        $teams = TeamController::getTeams();
        foreach ($teams as $team) {
            $teamTournament = new TeamTournament();
            $teamTournament->tournament_id = $newTournament->id;
            $teamTournament->team_id = $team->id;
            $teamTournament->save();
        }
    }

    function editTournamentName(Request $request, String $id){
        if(! $this->isTournamentNameExist($request)) {
            $tournament = $this->getTournamentById($id);
            if($tournament){
                $this->tournamentNameEditer($tournament,$request->name);
                return response(['message' => ["Tournament name successfully edited!"]], 200);
            }
            return response(['message' => ["Tournament with this id not exist!"]], 422);
        }
        return response(['message' => ["Tournament with this name already exist!"]], 422);
    }

    private function isTournamentNameExist(Request $request) :bool
    {
        $tournamentExist = Tournament::where('name', $request->name)->first();
        if($tournamentExist){
            return true;
        }
        return false;
    }

    private function tournamentNameEditer(Tournament $tournament,String $name){
        $tournament->name = $name;
        $tournament->save();
    }

}
