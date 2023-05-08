<template>
  <div class="container">
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
    <li class="list-group-item d-flex justify-content-center" v-for="(player, index) in players" :key="index">{{ player.name }}</li>
    <div v-if="players.length === 0">
      <p class=" d-flex justify-content-center">Players not exist!</p>
    </div>
    <table v-if="players.length > 0" class="table table-fluid" id="myTable">
        <thead>
        <tr><th>Name</th><th>Team</th><th></th></tr>
        </thead>
        <tbody>
        <tr v-for="(player, index) in players" :key="index"><td>{{ player.name }}</td><td>{{ player.team }}</td><td class="d-flex justify-content-end"><button type="button" class="btn btn-primary">Edit</button>
          <button type="button" class="btn btn-danger">Delete</button></td></tr>
        </tbody>
      </table>
  </div>
</template>

<script>
import fetchUrl from "@/components/Fetch";

export default {
  name: "PlayersView",
  data() {
    return {
      playerName: '',
      players: []
    };
  },
  async mounted() {
    this.players = await fetchUrl.get("api/players");
  },
  methods: {
    async addPlayer() {
      try {
        this.response = await fetchUrl.post("api/player/new", {"name": `${this.playerName}`});
        this.players.push({"name": `${this.playerName}`});
      } catch (error) {
        alert("Name Already Exist!")
      } finally {
        this.playerName = '';
      }
    }
  },
}
</script>

<style scoped>

</style>
