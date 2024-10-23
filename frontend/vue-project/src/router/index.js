import { createRouter, createWebHistory } from 'vue-router';
import Register from '../components/Register.vue';
import Login from '../components/Login.vue';
import TipoPregunta from '../components/TipoPregunta.vue'; // Componente para Tipos de Pregunta
import Prueba from '../components/Prueba.vue'; // Componente para Pruebas
import Pregunta from '../components/Pregunta.vue'; // Componente para Preguntas
import ListarUsuarios from '../components/ListarUsuarios.vue'; // Componente para Listar Usuarios
import Opcion from '../components/Opcion.vue'; // Nuevo componente para Opciones

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
    path: '/usuarios',  // Ruta para listar usuarios
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
    path: '/opciones',
    name: 'Opcion',
    component: Opcion, // Nueva ruta para la administración de Opciones
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
