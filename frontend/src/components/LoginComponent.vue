<template>
  <div class="container">
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" v-model="email" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" v-model="password" required>
      </div>

      <button type="submit" class="btn btn-primary">Login</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: '',
      password: '',
    };
  },
  methods: {
    async login() {
      try {
        // Make API request to login endpoint
        const response = await this.$axios.post('/login', {
          email: this.email,
          password: this.password,
        });

        // Store user and token in Vuex store
        this.$store.dispatch('login', {
          user: response.data.user,
          token: response.data.token,
        });

        // Redirect to appropriate dashboard
        this.$router.push(response.data.user.role.name === 'Admin' ? '/user-dashboard' : '/user-dashboard');
      } catch (error) {
        // Handle login error
        console.error('Login failed', error);
      }
    },
  },
};
</script>
