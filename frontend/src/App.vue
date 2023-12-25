<template>
  <div>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <router-link class="navbar-brand" to="/">BookStore</router-link>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link class="nav-link" to="/books">Books</router-link>
            </li>
          </ul>
          <ul class="navbar-nav d-flex flex-row-reverse">
            <li class="nav-item">
              <router-link v-if="!$store.getters.isAuthenticated" class="nav-link" to="/login">Login</router-link>
              <button v-if="$store.getters.isAuthenticated" class="btn btn-link nav-link" @click="logout">Logout</button>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container mt-4">
      <router-view></router-view>
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-light text-center py-3 mt-4">
      <div class="container">
        <p>&copy; 2023 BookStore</p>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'App',
  methods: {
    logout() {
      this.$store.dispatch('logout');
      this.$router.push('/login');
    },
  },
};

</script>

<style>
.footer {
  position: absolute;
  bottom: 0;
  left: 0;
  z-index: 10;
}
</style>

<!-- Include Bootstrap and Popper.js scripts -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
