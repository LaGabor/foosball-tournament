<template>
  <div class="container">
    <h2>New Tournament</h2>
    <form @submit.prevent="addChampionship">
      <div class="row align-items-end">
        <div class="col-md-5 form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" v-model="tournamentName" required>
        </div>
        <div class="col-md-4 form-group">
          <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </div>
      </div>
    </form>
    <hr>
    <h2>Tournaments</h2>
    <div v-if="tournaments.length === 0">
    <p class=" d-flex justify-content-center">Tournaments not exist!</p>
  </div>
    <ul class="list-group mx-auto">
      <li class="list-group-item d-flex justify-content-between align-items-center" v-for="(tournament, index) in championships" :key="index">
        {{ tournament.name }}
        <button v-if="!tournament.is_started" type="button" class="btn btn-primary" @click="createGames(tournament)">Start Games</button>
        <button v-if="tournament.is_started" type="button" class="btn btn-success" @click="seeGame(tournament)">Games</button>
      </li>
    </ul>
  </div>
</template>

<script>
import fetchUrl from "../components/Fetch"
export default {
  name: "TournamentView",
  data() {
    return {
      tournamentName: '',
      tournaments: []
    };
  },
  async mounted() {
    this.tournaments = await fetchUrl.get("api/tournaments");
  },
  methods: {
    async addChampionship() {
      try{
        this.response = await fetchUrl.post("api/tournament/new",{"name":`${this.tournamentName}`});
        this.tournaments= await fetchUrl.get("api/tournaments");
      }catch (error){
        alert("Tournament name Already Exist!")
      }finally {
        this.championshipName = '';
      }
    },
    async createGames(tournament) {
      try{
        console.log(tournament.id)
        let response = await fetchUrl.put("api/tournament/start",{"tournamentId":`${tournament.id}`});
        console.log(response);
        tournament.is_started = true;
      }catch (error){
        alert("Not Enough Team!")
      }
    }
  },
  seeGame(tournament){
    console.log(tournament);
  },
}
</script>

<style scoped>

</style>