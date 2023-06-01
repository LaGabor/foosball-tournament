<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="background-color: #e3f2fd;">
    <div class="container-fluid">
      <a class="navbar-brand" :href="isActive('/') ? 'javascript:void(0)' : '/'">FoosballFiesta</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" :class="{ active: isActive('/players') }" aria-current="page" :href="isActive('/players') ? 'javascript:void(0)' : '/players'">Players</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" :class="{ active: isActive('/teams') }" aria-current="page" :href="isActive('/teams') ? 'javascript:void(0)' : '/teams'">Teams</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" :class="{ active: isActive('/tournaments') }" aria-current="page" :href="isActive('/tournaments') ? 'javascript:void(0)' : '/tournaments'">Tournaments</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" :class="{ active: isActive('/games') }" aria-current="page" :href="isActive('/games') ? 'javascript:void(0)' : '/games'">Games</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Toplist</a>
          </li>
        </ul>
        <form class="d-flex">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-light" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>
</template>



<script>
export default {
  name: 'AppNavbar',
  methods: {
    isActive(route) {
      return this.$route.path === route
    }
  }

}
</script>