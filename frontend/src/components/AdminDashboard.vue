<template>
    <div>
      <h2>Admin Dashboard</h2>
      <p>Welcome, Admin {{ $store.state.user.name }}!</p>
      <!-- Add other admin dashboard content here -->
    </div>
  </template>
  
  <script>
  export default {
    created() {
      // Fetch and display admin-specific data here if needed
    },
  };
  </script>
  