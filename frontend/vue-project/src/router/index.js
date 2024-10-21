import { createRouter, createWebHistory } from 'vue-router';
import Register from '../components/Register.vue'; // Importar desde components
import Login from '../components/Login.vue'; // Importar desde components
import Reporte from '@/components/Reporte.vue';

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
    path: '/reporte',
    name: 'Reporte',
    component: Reporte,
  },
  // Otras rutas...
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
