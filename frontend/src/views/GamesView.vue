<template>
  <div class="container">
    <h2>Games</h2>
    <div class="form-group">
      <label for="tournament">Select tournament:</label>
      <select class="form-control" id="tournament" v-model="selectedTournamentId" @change="loadGames">
        <option v-for="tournament in tournaments" :key="tournament.id" :value="tournament.id">{{ tournament.name }}</option>
      </select>
    </div>
    <hr>
    <ul class="list-group mx-auto">
      <li class="list-group-item d-flex justify-content-between align-items-center" v-for="(game) in games" :key="game.id" :data-team-ids="`${game.teams[0].id}-${game.teams[1].id}`">
        <div>{{ game.teams[0].name }} - {{ game.teams[1].name }}</div>
        <div>
          <label for="lefTeamGoals">Home team goals:</label>
          <select class="form-control" id="lefTeamGoals" v-model="game.lefTeamGoals">
            <option v-for="i in 11" :key="i" :value="i-1">{{ i-1 }}</option>
          </select>
        </div>
        <div>
          <label for="rightTeamGoals">Away team goals:</label>
          <select class="form-control" id="rightTeamGoals" v-model="game.rightTeamGoals">
            <option v-for="i in 11" :key="i" :value="i-1">{{ i-1 }}</option>
          </select>
        </div>
        <button type="button" class="btn btn-primary" @click="submitResult(game)">Submit</button>
      </li>
    </ul>
  </div>
</template>
<script>
import fetchUrl from "../components/Fetch"

export default {
  name: 'GamesView.vue',
  data() {
    return {
      tournaments: [],
      selectedTournamentId: null,
      games: []
    };
  },
  async mounted() {
    await this.loadTournaments();
  },
  methods: {
    async loadTournaments() {
      this.tournaments = await fetchUrl.get("http://localhost:8000/tournaments/open");
      if (this.tournaments.length > 0) {
        this.selectedTournamentId = this.tournaments[0].id;
        await this.loadGames();
      }
    },
    async loadGames() {
      if (this.selectedTournamentId) {
        this.tournamentId = this.selectedTournamentId;
        this.games = await fetchUrl.get(`http://localhost:8000/tournaments/${this.selectedTournamentId}/games`);
      }
    },
    async submitResult(game) {
      let teamIds = event.target.closest('li').dataset.teamIds.split('-');
      let redTeamId = teamIds[0];
      let blueTeamId = teamIds[1];
      let result = {
        red_team_goals: game.lefTeamGoals,
        blue_team_goals: game.rightTeamGoals
      };
      if(this.checkInput(result)){
        let outcome =`${result.red_team_goals}:${result.blue_team_goals}`;
        let winnerId = this.checkWinner(redTeamId,blueTeamId,result);
        await fetchUrl.put(`http://localhost:8000/tournament/game/play`, {"gameId":`${game.id}`,"outcome":`${outcome}`,"winnerId":`${winnerId}`});
        this.checkIfLast()
        this.games = await fetchUrl.get(`http://localhost:8000/tournaments/${this.selectedTournamentId}/games`);
      }
    },
    checkInput(result){
      if((parseInt(result.red_team_goals) + parseInt(result.blue_team_goals)) < 20){
        if(parseInt(result.red_team_goals) === 10 || parseInt(result.blue_team_goals) === 10){
          return true
        }else{
          alert("Not a Valid Score!")
          return false;
        }
      }
      alert("Can't Be Draw!")
      return false
    },
    checkWinner(redTeamId,blueTeamId,result){
      if(result.red_team_goals>result.blue_team_goals){
        return redTeamId;
      }
      return blueTeamId;
    },
    async checkIfLast(){
      if(this.games.length === 1){
        await fetchUrl.put(`http://localhost:8000/tournament/last-game`, {"tournamentId":`${this.selectedTournamentId}`});
      }

    },
  },
  watch: {
    selectedTournamentId() {
      this.loadGames();
    }
  }
}
</script>
<style scoped>
.list-group {
  max-width: 800px;
}
</style>