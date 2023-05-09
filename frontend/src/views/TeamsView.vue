<template>
  <div class="container">
    <h2>New Team</h2>
    <form @submit.prevent="addTeam">
      <div class="row align-items-end">
        <div class="col-md-5 form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" v-model="teamName" required>
        </div>
        <div class="col-md-3 form-group">
          <label for="player1">Player 1</label>
          <select class="form-control" id="player1" v-model="player1">
            <option value="">-- Select a player --</option>
            <option v-for="player in freePlayers" :key="player.id" :value="player.id">{{ player.name }}</option>
          </select>
        </div>
        <div class="col-md-3 form-group">
          <label for="player2">Player 2</label>
          <select class="form-control" id="player2" v-model="player2" required>
            <option value="">-- Select a player --</option>
            <option v-for="player in freePlayers" :key="player.id" :value="player.id">{{ player.name }}</option>
          </select>
        </div>
        <div class="col-md-1 form-group">
          <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </div>
      </div>
    </form>
    <hr>
    <h2 >Teams</h2>
      <li class="list-group-item d-flex justify-content-center" v-for="(team, index) in teams" :key="index">{{ team.name }} : {{team.players[0].name}} and {{team.players[1].name}}</li>
    <div v-if="teams.length === 0">
      <p class=" d-flex justify-content-center">Teams not exist!</p>
    </div>
    <table v-if="teams.length > 0" class="table table-fluid" id="myTable">
      <thead>
      <tr><th>Team Name</th><th>Players</th><th></th></tr>
      </thead>
      <tbody>
      <tr v-for="(team, index) in teams" :key="index"><td>{{ team.name }}</td><td>{{ team.players }}</td><td class="d-flex justify-content-end"><button type="button" class="btn btn-primary">Edit</button>
        <button type="button" class="btn btn-danger">Delete</button></td></tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import fetchUrl from "../components/Fetch"
export default {
  name: "TeamsView",
  data() {
    return {
      teamName: '',
      player2Name: '',
      player1Name: '',
      player1: '',
      player2: '',
      teams: [],
      freePlayers: [],
    };
  },
  async mounted() {

    this.teams = await fetchUrl.get("api/teams");
    this.freePlayers = await fetchUrl.get("api/players/free");
  },
  methods: {
    async addTeam() {
      if (this.checkNames() && this.checkTeamName()) {
        let response = await fetchUrl.post("api/team/new", {
          "name": `${this.teamName}`,
          "playersId": [this.player1, this.player2]
        });
        try {
          this.teams.push({
            "name": `${this.teamName}`,
            "players": [{"name": response[0].players[0].name}, {"name": response[0].players[1].name}]
          });
        } catch (error) {
          alert(error)
        }
        this.freePlayers = await fetchUrl.get("api/players/free");
      }
      this.teamName = '';
      this.player1 = '';
      this.player2 = '';
    },
    checkNames() {
      if (this.player1 === this.player2) {
        alert("Names cant be identical!")
        return false;
      }
      return true;
    },
    checkTeamName() {
      let teamNameDontExist = true;
      this.teams.forEach(element => {
        if (this.teamName === element.name) {
          alert("Team name already existing!")
          teamNameDontExist = false;
        }
      })
      console.log(teamNameDontExist)
      return teamNameDontExist;
    },

  }
}
</script>

<style scoped>

</style>