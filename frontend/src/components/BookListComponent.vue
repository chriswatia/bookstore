<template>
  <div>
    <h2>Book List</h2>
    <ul>
      <li v-for="book in books" :key="book.id">
        {{ book.name }} - {{ book.publisher }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  computed: {
    books() {
      return this.$store.state.books;
    },
  },
  mounted() {
    // Fetch books when the component is mounted
    this.$store.dispatch('fetchBooks');
  },
};
</script>

<style scoped>
/* Add component styles here */
</style>
