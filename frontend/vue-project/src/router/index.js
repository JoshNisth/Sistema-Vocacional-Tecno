// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Register from '../components/Register.vue';
import Login from '../components/Login.vue';

const routes = [
  {
    path: '/register',
    name: 'Register',
    component: Register,
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/',
    redirect: '/login', // Redirige a la página de inicio de sesión
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
