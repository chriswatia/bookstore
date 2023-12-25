// Vuex store
import { createStore } from 'vuex';
import BookService from '@/services/BookService';

const TOKEN_KEY = 'authToken'; // Key for localStorage

export default createStore({
  state: {
    user: JSON.parse(localStorage.getItem('user')) || null,
    token: localStorage.getItem(TOKEN_KEY) || null,
    books: [],
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
      localStorage.setItem('user', JSON.stringify(user)); // Save user to localStorage
    },
    setToken(state, token) {
      state.token = token;
      localStorage.setItem(TOKEN_KEY, token); // Save token to localStorage
    },
    logout(state) {
      state.user = null;
      state.token = null;
      localStorage.removeItem('user'); // Remove user from localStorage on logout
      localStorage.removeItem(TOKEN_KEY); // Remove token from localStorage on logout
    },
    setBooks(state, books) {
      state.books = books;
    },
    addBook(state, newBook) {
      state.books.push(newBook);
    },
  },
  actions: {
    login({ commit }, { user, token }) {
      commit('setUser', user);
      commit('setToken', token);
    },
    logout({ commit }) {
      commit('logout');
    },
    async fetchBooks({ commit }) {
      try {
        const response = await BookService.getAllBooks();
        commit('setBooks', response.data);
      } catch (error) {
        console.error('Error fetching books:', error);
      }
    },
    async addBook({ commit }, newBook) {
      try {
        const response = await BookService.addBook(newBook);
        commit('addBook', response.data);
      } catch (error) {
        console.error('Error adding a book:', error);
      }
    },
  },
  getters: {
    isAuthenticated(state) {
      return !!state.user;
    },
    isAdmin(state) {
      return state.user && state.user.role.name === 'Admin';
    },
  },
});
