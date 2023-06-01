<template>
  <EditModal :data="selectedTeam" editedThing ="Team" @updateData="updateTeamName"/>
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
          <select class="form-control" id="player1" v-model="player1Id">
            <option value="">-- Select a player --</option>
            <option v-for="player in freePlayers" :key="player.id" :value="player.id">{{ player.name }}</option>
          </select>
        </div>
        <div class="col-md-3 form-group">
          <label for="player2">Player 2</label>
          <select class="form-control" id="player2" v-model="player2Id" required>
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
    <div v-if="teams.length === 0">
      <p class=" d-flex justify-content-center">Teams not exist!</p>
    </div>
    <table v-if="teams.length > 0" class="table table-fluid" id="myTable">
      <thead>
      <tr><th>Team Name</th><th>Players</th><th></th></tr>
      </thead>
      <tbody>
      <tr v-for="(team, index) in teams" :key="index"><td>{{ team.name }}</td><td>{{team.players[0].name}} and {{team.players[1].name}} </td>
        <td class="d-flex justify-content-end"><button type="button" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#editModal" @click="setSelectedTeam(team)">Edit</button>
        <button type="button" class="btn btn-danger">Delete</button></td></tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import fetchUrl from "../components/Fetch"
import EditModal from "@/components/EditModal.vue";
export default {
  name: "TeamsView",
  components: {EditModal},
  data() {
    return {
      teamName: '',
      player2Name: '',
      player1Name: '',
      player1Id: '',
      player2Id: '',
      teams: [],
      freePlayers: [],
      selectedTeam: ''
    };
  },
  async mounted() {

    this.teams = await fetchUrl.get("http://localhost:8000/teams");
    this.freePlayers = await fetchUrl.get("http://localhost:8000/players/free");
  },
  methods: {
    async addTeam() {
      if (this.checkNames() && this.checkTeamName()) {
        let response = await fetchUrl.post("http://localhost:8000/teams/new", {
          "name": `${this.teamName}`,
          "playersId": [this.player1Id, this.player2Id]
        });
        try {
          this.teams.push({
            "name": `${this.teamName}`,
            "players": [{"name": response[0].players[0].name}, {"name": response[0].players[1].name}]
          });
        } catch (error) {
          alert(error.message)
        }
        this.freePlayers = await fetchUrl.get("http://localhost:8000/players/free");
      }
      this.teamName = '';
      this.player1Id = '';
      this.player2Id = '';
    },
    checkNames() {
      if (this.player1Id === this.player2Id) {
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
    setSelectedTeam(team) {
      this.selectedTeam = team;
    },
    async updateTeamName(newName) {
      if(! this.isSame(newName)){
        try {
          await fetchUrl.put(`http://localhost:8000/teams/${this.selectedTeam.id}/edit`, { "name": `${newName}` });
          this.selectedTeam.name = newName;
        } catch (error) {
          alert(error.message)
        }
      }else{
        alert("Same name!")
      }
    },
    isSame(newName){
      if(newName === this.selectedTeam.name) return true;
      return false;
    },
  }
}
</script>

<style scoped>

</style>