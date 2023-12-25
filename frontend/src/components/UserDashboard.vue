<template>
  <div>
    <h3>All Books</h3>
    <div class="row">
      <div v-if="books.length > 0">
        <div v-for="book in books" :key="book.id" class="col-md-4 mb-3">
          <div class="card">
            <img :src="book.image" class="card-img-top" alt="...">
            <div class="card-body">
              <h5 class="card-title">{{ book.name }}</h5>
              <p class="card-text">{{ book.description }}</p>
              <p class="card-text"><strong>Publisher:</strong> {{ book.publisher }}</p>
              <p class="card-text"><strong>Category:</strong> {{ book.category }}</p>
              <p class="card-text"><strong>Status:</strong> {{ book.status }}</p>
              <a href="#" class="btn btn-primary">Borrow</a>
            </div>
          </div>
        </div>
      </div>
      <div v-else>
        <p>No books available.</p>
      </div>
    </div>
  </div>
</template>

<script>
import BookService from '@/services/BookService';

export default {
  data() {
    return {
      books: [],
    };
  },
  created() {
    this.fetchUserBooks();
  },
  methods: {
    fetchUserBooks() {
      BookService.getAllBooks()
        .then(response => {
          this.books = response.data;
          console.log(this.books)
        })
        .catch(error => {
          console.error('Error fetching user books:', error);
        });
    },
  },
};
</script>
