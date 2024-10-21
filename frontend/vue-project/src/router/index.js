import { createRouter, createWebHistory } from 'vue-router';
import Register from '../components/Register.vue'; // Importar desde components
import Login from '../components/Login.vue'; // Importar desde components
import Intereses from '../components/interes.vue'; // Importar el nuevo componente
import InteresesUsuario from '../components/intereses_usuario.vue'; // Importar el nuevo componente


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
    path: '/intereses', 
    name: 'Intereses',
    component: Intereses,
  },
  {
    path: '/intereses_usuario', 
    name: 'InteresesUsuario', 
    component: InteresesUsuario, 
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;