import { createRouter, createWebHistory } from "vue-router";
import LoginComponent from "../components/LoginComponent.vue";
import UserDashboard from "../components/UserDashboard.vue";
import AdminDashboard from "../components/AdminDashboard.vue";
import BookList from "../components/BookListComponent.vue";

import store from "../store";

const routes = [
  {
    path: "/",
    component: UserDashboard,
    meta: { requiresAuth: true },
  },
  { path: "/login", component: LoginComponent },
  {
    path: "/user-dashboard",
    component: UserDashboard, BookList,
    meta: { requiresAuth: true },
  },
  {
    path: "/admin-dashboard",
    component: AdminDashboard, BookList,
    meta: { requiresAuth: true, requiresAdmin: true },
  },
  {
    path: "/books",
    component: BookList,
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = store.getters.isAuthenticated;
  const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
  const requiresAdmin = to.matched.some((record) => record.meta.requiresAdmin);

  if (requiresAuth && !isAuthenticated) {
    next("/login");
  } else if (requiresAdmin && !store.getters.isAdmin) {
    next("/login");
  } else {
    next();
  }
});

export default router;
