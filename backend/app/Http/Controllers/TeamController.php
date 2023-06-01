<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends Controller
{
    function createNewTeam(Request $request)
    {
        if (count($request->playersId) === 0) {
            return response(['message' => ["Player input needed!"]], Response::HTTP_CONFLICT);
        }

        if($this->isPlayerTheSame($request->playersId)){
            return response(['message' => ["Player names can't be identical!"]], Response::HTTP_CONFLICT);
        }


        if (!$this->teamPlayersExist($request)) {
            return response(['message' => ["Player Not Exist"]], Response::HTTP_CONFLICT);
        }

        if( $this->hasATeam($request->playersId)){
            return response(['message' => ["Player already has a team."]], Response::HTTP_CONFLICT);
        }

        if (! $this->isTeamNameExist($request)) {
            $newTeamId = $this->createNewTeamAndReturnId($request);
            $this->addPlayersToTeam($request->playersId,$newTeamId);
            return response($this->getBackNewTeam($newTeamId));
        }
        return response(['message' => ["Team with this name already exist!"]], 422);
    }

    private function addPlayersToTeam($playersId,$teamId): void{
        foreach ($playersId as $playerId){
            $player =  Player::find($playerId);
            $player->team_id = $teamId;
            $player->is_free = 0;
            $player->save();
        }
    }

    public static function getTeams (){
        return $teams = Team::with('players')->get();
    }

    private function getBackNewTeam($newTeamId){
        return Team::with('players')->where('id', $newTeamId)->get();
    }

    public static function checkAllTeamCount(){
        return Team::all()->count();
    }

    function teamPlayersExist(Request $request): bool
    {
        $playerIds = [];
        foreach ($request->playersId as $id) {
            if(!$this->isPlayerIdExist($id)){
                return false;
            }
        }
        return true;
    }

    function isPlayerIdExist($id) :bool
    {
        $playerExist = Player::where('id', $id)->first();
        if($playerExist){
            return true;
        }
        return false;
    }

    private function isTeamNameExist(Request $request) :bool
    {
        $teamExist = Team::where('name', $request->name)->first();
        if($teamExist){
            return true;
        }
        return false;
    }

    private function createNewTeamAndReturnId(Request $request) :int
    {
        $team = Team::create(['name' => $request->name]);
        $team->save();
        return $team->id;
    }

    private function hasATeam($playersId):bool
    {
        foreach ($playersId as $playerId){
            $player =  Player::find($playerId);
            if(($player->team_id)) return true;
        }
        return false;
    }


    function editTeamName(Request $request, String $id){
        if(! $this->isTeamNameExist($request)) {
            $team = $this->getTeamById($id);
            if($team){
                $this->teamNameEditer($team,$request->name);
                return response(['message' => ["Team name successfully edited!"]], 200);
            }
            return response(['message' => ["Team with this id not exist!"]], 422);
        }
        return response(['message' => ["Team with this name already exist!"]], 422);
    }

    private function teamNameEditer(Team $team,String $name){
        $team->name = $name;
        $team->save();
    }

    public function getTeamById(String $id):? Team{
        return Team::where('id',$id)->first();
    }

    private function isPlayerTheSame($playersId): bool
    {
        if ($playersId[0] == $playersId[1]) {
            return true;
        }
        return false;
    }



}
