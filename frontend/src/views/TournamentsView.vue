<template>
  <EditModal :data="selectedTournament" editedThing ="Tournament"  @updateData="updateTournamentName" />
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
      <li class="list-group-item d-flex justify-content-between align-items-center" v-for="(tournament, index) in tournaments" :key="index">
        {{ tournament.name }}
        <div class="buttons">
          <button v-if="!tournament.is_started" type="button" class="btn btn-success" @click="createGames(tournament)">Start Games</button>
          <button v-if="tournament.is_started" type="button" class="btn btn-success" @click="seeGame(tournament)">Games</button>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal" @click="setSelectedTournament(tournament)">Edit</button>
          <button type="button" class="btn btn-danger">Delete</button>
        </div>
        </li>
    </ul>
  </div>
</template>

<script>
import fetchUrl from "../components/Fetch"
import EditModal from "@/components/EditModal.vue";
export default {
  name: "TournamentView",
  components: {EditModal},
  data() {
    return {
      tournamentName: '',
      selectedTournament: '',
      tournaments: []
    };
  },
  async mounted() {
    this.tournaments = await fetchUrl.get("http://localhost:8000/tournaments");
  },
  methods: {
    async addChampionship() {
      try{
        this.response = await fetchUrl.post("http://localhost:8000/tournaments/new",{"name":`${this.tournamentName}`});
        this.tournaments= await fetchUrl.get("http://localhost:8000/tournaments");
      }catch (error){
        alert(error.message)
      }finally {
        this.tournamentName = '';
      }
    },
    async createGames(tournament) {
      try{
        console.log(tournament.id)
        let response = await fetchUrl.put("http://localhost:8000/tournaments/start",{"tournamentId":`${tournament.id}`});
        console.log(response);
        tournament.is_started = true;
      }catch (error){
        alert(error.message)
      }
    },
     seeGame(tournament){
       console.log(tournament)
       alert("Not Implemented!")
    },
    setSelectedTournament(tournament) {
      this.selectedTournament = tournament;
    },
    async updateTournamentName(newName) {
      if(! this.isSame(newName)){
        try {
          await fetchUrl.put(`http://localhost:8000/tournaments/${this.selectedTournament.id}/edit`, { "name": `${newName}` });
          this.selectedTournament.name = newName;
        } catch (error) {
          alert(error.message)
        }
      }else{
        alert("Same name!")
      }
    },
    isSame(newName){
      if(newName === this.selectedTournament.name) return true;
      return false;
    }
  },
}
</script>

<style scoped>

</style>