<template>
  <footer class="footer" :class="{'sticky-bottom': contentLargerThanViewport}">
    <div class="container">
      <ul class="nav justify-content-center border-bottom pb-2 mb-1">
        <li class="nav-item">
          <a href="/" class="nav-link px-2 text-muted">Home</a>
        </li>
        <li class="nav-item">
          <a href="/players" class="nav-link px-2 text-muted">Players</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link px-2 text-muted">Teams</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link px-2 text-muted">Tournaments</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link px-2 text-muted">Games</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link px-2 text-muted">Top Lists</a>
        </li>
      </ul>
      <p class="text-center text-muted">2023 FoosballFiesta</p>
    </div>
  </footer>
</template>

<script>
export default {
  name: "AppFooter.vue",
  computed: {
    contentLargerThanViewport() {
      return (
          document.body.clientHeight > window.innerHeight ||
          document.documentElement.clientHeight > window.innerHeight
      );
    },
  },
};
</script>

<style scoped>
.footer {
  position: relative;
  bottom: 0;
  width: 100%;
}

.sticky-bottom {
  position: sticky;
  bottom: 0;
  width: 100%;
}
</style>