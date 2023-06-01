<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PlayerController extends Controller
{
    function createNewPlayer(Request $request)
    {
        if(! $this->isPlayerNameExist($request->name)){
            $this->makeNewPlayer($request);
            return response(['message' => ["New player successfully created"]], Response::HTTP_CREATED);
        }
        return response(['message' => ["Player with this name already exist!"]], 422);
    }

    public function getPlayer(String $id):? Player{
        return Player::where('id',$id)->first();
    }

    public function getPlayers(){
        $allPlayer = Player::with('team')->get();
        return response($allPlayer, Response::HTTP_CREATED);
    }

    public function  getFreePlayers()
    {
        return Player::all()->where('is_free',1);
    }

    private function isPlayerNameExist($name): bool
    {
        return Player::where('name', $name)->exists();
    }

    private function makeNewPlayer(Request $request) :void
    {
        Player::create([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    function editPlayerName(Request $request, String $id){
        if(! $this->isPlayerNameExist($request->name)) {
            $player = $this->getPlayer($id);
            if($player){
                $this->playerNameEditer($player,$request->name);
                return response(['message' => ["Player name successfully edited!"]], 200);
            }
            return response(['message' => ["Player with this id not exist!"]], 422);
        }
        return response(['message' => ["Player with this name already exist!"]], 422);
    }

    private function playerNameEditer(Player $player,String $name){
        $player->name = $name;
        $player->save();
    }


}
