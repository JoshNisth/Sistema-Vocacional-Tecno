import { createRouter, createWebHistory } from 'vue-router';
import Register from '../components/Register.vue';
import Login from '../components/Login.vue';
import TipoPregunta from '../components/TipoPregunta.vue'; // Nuevo componente para Tipos de Pregunta
import Prueba from '../components/Prueba.vue'; // Nuevo componente para Pruebas
import Pregunta from '../components/Pregunta.vue'; // Nuevo componente para Preguntas
import ListarUsuarios from '../components/ListarUsuarios.vue'
import RealizarPrueba from '@/components/RealizarPrueba.vue';
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
    path: '/usuarios',  // Nueva ruta para listar usuarios
    name: 'ListarUsuarios',
    component: ListarUsuarios,
  },
  {
    path: '/tipos-pregunta',
    name: 'TipoPregunta',
    component: TipoPregunta, // Ruta para la administración de Tipos de Pregunta
  },
  {
    path: '/pruebas',
    name: 'Prueba',
    component: Prueba, // Ruta para la administración de Pruebas
  },
  {
    path: '/preguntas',
    name: 'Pregunta',
    component: Pregunta, // Ruta para la administración de Preguntas
  },
  {
    path: '/realizarPrueba',
    name: 'RPrueba',
    component: RealizarPrueba, // Ruta para la administración de Preguntas
  },
  
  {
    path: '/',
    redirect: '/login', // Redirige a login por defecto
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
