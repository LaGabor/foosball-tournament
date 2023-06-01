<template>
  <div class="container">
    <EditModal :data="selectedPlayer" editedThing="Player"  @updateData="updatePlayerName" />
    <h2>New Player</h2>
    <form @submit.prevent="addPlayer">
      <div class="row align-items-end">
        <div class="col-md-5 form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" v-model="playerName" required>
        </div>
        <div class="col-md-4 form-group">
          <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </div>
      </div>
    </form>
    <hr>
    <h2 class="mb-3">Players</h2>
    <div v-if="players.length === 0">
      <p class=" d-flex justify-content-center">Players not exist!</p>
    </div>
    <table v-if="players.length > 0" class="table table-fluid" id="myTable">
        <thead>
        <tr><th>Name</th><th>Team</th><th></th></tr>
        </thead>
        <tbody>
        <tr v-for="(player, index) in players" :key="index"><td>{{ player.name }}</td><td>{{ player.team ? player.team.name : 'No Team' }}</td>
          <td class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal" @click="setSelectedPlayer(player)">Edit</button>
          <button type="button" class="btn btn-danger">Delete</button></td></tr>
        </tbody>
      </table>
  </div>
</template>

<script>
import fetchUrl from "@/components/Fetch";
import EditModal from "@/components/EditModal.vue";

export default {
  name: "PlayersView",
  components: { EditModal },
  data() {
    return {
      playerName: '',
      players: [],
      selectedPlayer: ''
    };
  },
  async mounted() {
    this.players = await fetchUrl.get("http://localhost:8000/players");
  },
  methods: {
    setSelectedPlayer(player) {
      this.selectedPlayer = player;
    },
    async addPlayer() {
      try {
        await fetchUrl.post("http://localhost:8000/players/new", { "name": `${this.playerName}` });
        this.players.push({ "name": `${this.playerName}` });
      } catch (error) {
        alert(error.message)
      } finally {
        this.playerName = '';
      }
    },
    async updatePlayerName(newName) {
      if(! this.isSame(newName)){
        try {
          await fetchUrl.put(`http://localhost:8000/players/${this.selectedPlayer.id}/edit`, { "name": `${newName}` });
          this.selectedPlayer.name = newName;
        } catch (error) {
          alert(error.message)
        }
      }else{
        alert("Same name!")
      }
    },
    isSame(newName){
        if(newName === this.selectedPlayer.name) return true;
        return false;
    }
  },
}
</script>

<style scoped>

</style>
