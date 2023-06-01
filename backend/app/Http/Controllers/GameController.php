<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameTeam;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{

    public function createGames($tournamentId): Response
    {

        $teams = $this->getTournamentTeams($tournamentId);
        if($this->isMatchesAlreadyCreated($tournamentId)) return response(['message' => ["Matches already created for this Tournament!"]], Response::HTTP_CONFLICT);

        $this->createMatches($teams, $tournamentId);
        return response(['message' => ["Matches Created!"]], Response::HTTP_CREATED);
    }

    private function createMatches($teams, int $tournamentId): void
    {
        $teamCount = count($teams);
        for ($i = 0; $i < $teamCount; $i++) {
            $leftTeam = $teams[$i];
            for ($z = $i + 1; $z < $teamCount; $z++) {
                $rightTeam = $teams[$z];
                $newGame = $this->createAndReturnNewGame($tournamentId);
                $this->createGamesTeamsPivots($newGame, array($leftTeam, $rightTeam));
            }
        }
    }

    function playedGamesByTournamentName(Request $request){
        $tournamentId = TournamentController::getTournamentIdByName($request->tournamentName);
        if(!$tournamentId) return response(['message' => ["Tournament does not exist!"]], Response::HTTP_CONFLICT);
        $playedTournamentGames = $this->getPlayedGamesByTournament($tournamentId);
        if(!$playedTournamentGames) return response(['message' => ["Tournament is not started yet!"]], Response::HTTP_CONFLICT);
        return response(['message' => ["Tournament Games!"], 'games' => $this->getPlayedGamesByTournament($tournamentId)], Response::HTTP_OK);
    }

    private function getGameById($gameId)
    {
        return $this->gameExistByID($gameId);
    }


    function playGame(Request $request)
    {

        $game = $this->getGameById($request->gameId);
        if (!$game) {
            return response(['message' => ["Game does not exist by this ID!"]], Response::HTTP_CONFLICT);
        }
        if ($game->game_date) {
            return response(['message' => ["Game already played!"]], Response::HTTP_CONFLICT);
        }


        if (!$this->isOutcomeValid($request->outcome)) {
            return response(['message' => ["Outcome is not valid!"]], Response::HTTP_CONFLICT);
        }

        if (!$this->isWinnerIdValid($request->winnerId,$game)) {
            return response(['message' => ["Team with this id not playing"]], Response::HTTP_CONFLICT);
        }

        $this->updateGameStatistic($game, $request);
        return response(['message' => ["Game Played/Updated!"]], Response::HTTP_OK);

    }


    private function updateGameStatistic($game, $request):Game
    {
        $outcomeList = $this->sliceAndSortOutcome($request->outcome);
        return $this->gameStatisticUpdater($game, $request,$outcomeList,$this->getLooser($request->winnerId,$game));

    }

    private function isOutcomeValid($outcome): bool
    {
        $outcomeList = explode(":", $outcome);
        $WinCount=0;
        if (count($outcomeList) != 2) return false;
        foreach ($outcomeList as $value) {
            if (is_numeric($value)) {
                if(floor($value)< 0 || floor($value) > 10 ){
                    return false;
                }elseif (floor($value) == 10){
                    $WinCount++;
                }
            }
        }
        if($WinCount ==1) return true;
        return false;
    }

    private function isMatchesAlreadyCreated($tournamentId):bool{
        return $this->hasGame($tournamentId);

    }
    private function isWinnerIdValid($winnerId,$game)
    {
        $teams = $game->teams;
        foreach ($teams as $team){
            if($team->id == $winnerId) return true;
        }
        return false;
    }

    private function getLooser($winnerId,$game)
    {
        $teams = $game->teams;
        foreach ($teams as $team) {
            if (!($team->id == $winnerId)) return $team->id;
        }
    }

    private function sliceAndSortOutcome($outcome):array
    {
        $outcomeList = explode(":", $outcome);
        sort($outcomeList, SORT_NUMERIC);
        return $outcomeList;
    }


/*
    public function topListByTournamentName(Request $request){
        if(!(ChampionshipController::isTournamentStarted($request->tournamentId))){
            return response(['message' => ["Tournament not started yet!"]], Response::HTTP_CONFLICT);
        }
        return GameRepository::getTopList($request->tournamentId);
    }
*/

    public function getGamesByTournament($tournamentId){
        return Game::where('tournament_id', $tournamentId)->whereNull('game_date')->with('teams')->get();
    }


    public function getTournamentTeams(int $tournamentId){
        $tournament = TournamentController::getTournamentByID($tournamentId);
        return $tournament->teams;
    }

    private function hasGame($tournamentId):bool
    {
        $tournament = TournamentController::getTournamentByID($tournamentId);
        return $tournament->games()->exists();
    }


    //-------------------------------------- REP

    public static function createAndReturnNewGame($tournamentId) :Game
    {
        $game = new Game();
        $game->tournament_id = $tournamentId;
        $game->save();
        return $game;
    }


    public function gameExistById($gameId)
    {
        return Game::where('id', $gameId)->first();

    }


    public function gameStatisticUpdater($game, $request,$outcomeList,$loserId):Game{
        $game->game_date = now();
        $game->winning_score = $outcomeList[1];
        $game->losing_score = $outcomeList[0];
        $game->winner_id = $request->winnerId;
        $game->loser_id = $loserId;
        $game->save();
        return $game;
    }

    public function getGameTeams($game)
    {
        return $game->teams;
    }

    public function getPlayedGamesByTournament($tournamentId)
    {
        return Game::where("tournament_id", $tournamentId)
            ->whereNotNull('game_date')
            ->orderBy('id')
            ->get();
    }


    private function createGamesTeamsPivots(Game $newGame,$teams):void{

        foreach ($teams as $team) {
            $gameTeam = new GameTeam();
            $gameTeam->game_id = $newGame->id;
            $gameTeam->team_id = $team->id;
            $gameTeam->save();
        }
    }

}
