<template>
    <div class="container mt-5">
      <h2 class="text-center">Administración de Preguntas</h2>
      <form @submit.prevent="createPregunta" class="bg-light p-4 rounded shadow">
        <div class="mb-3">
          <label for="contenido" class="form-label">Pregunta</label>
          <input v-model="nuevoContenido" type="text" class="form-control" id="contenido" placeholder="Ingrese la pregunta" required />
        </div>
        <div class="mb-3">
          <label for="tipoPregunta" class="form-label">Tipo de Pregunta</label>
          <select v-model="tipoPreguntaID" class="form-select">
            <option v-for="tipo in tipos" :key="tipo.TipoPreguntaID" :value="tipo.TipoPreguntaID">{{ tipo.Tipo }}</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="prueba" class="form-label">Prueba</label>
          <select v-model="pruebaID" class="form-select">
            <option v-for="prueba in pruebas" :key="prueba.PruebaID" :value="prueba.PruebaID">{{ prueba.Instrucciones }}</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">Crear Pregunta</button>
      </form>
      
      <div v-if="message" class="alert alert-info mt-3">{{ message }}</div>
      
      <h3 class="mt-5">Lista de Preguntas</h3>
      <ul class="list-group">
        <li class="list-group-item" v-for="pregunta in preguntas" :key="pregunta.PreguntaID">
          {{ pregunta.Contenido }}
          <button class="btn btn-sm btn-danger float-end" @click="deletePregunta(pregunta.PreguntaID)">Eliminar</button>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        nuevoContenido: '',
        tipoPreguntaID: '',
        pruebaID: '',
        preguntas: [],
        tipos: [],
        pruebas: [],  // Aquí almacenamos las pruebas
        message: ''
      };
    },
    created() {
      this.fetchPreguntas();
      this.fetchTipos();
      this.fetchPruebas();  // Llamada para cargar las pruebas
    },
    methods: {
      async fetchPreguntas() {
        const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/pregunta.php');
        this.preguntas = response.data;
      },
      async fetchTipos() {
        const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/tipopregunta.php');
        this.tipos = response.data;
      },
      async fetchPruebas() {
        try {
          const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/prueba.php');  // Asegúrate de que esta URL es correcta
          this.pruebas = response.data;
        } catch (error) {
          console.error("Error al cargar las pruebas:", error);
        }
      },
      async createPregunta() {
        try {
          const response = await axios.post('http://localhost/Sistema-Vocacional-Tecno/backend/api/pregunta.php', { 
            contenido: this.nuevoContenido, 
            tipoPreguntaID: this.tipoPreguntaID, 
            pruebaID: this.pruebaID 
          });
          this.message = response.data.message;
          this.fetchPreguntas();
          this.nuevoContenido = '';  // Limpiar campos después de crear
        } catch (error) {
          this.message = 'Error al crear la pregunta';
        }
      },
      async deletePregunta(id) {
        try {
          await axios.delete('http://localhost/Sistema-Vocacional-Tecno/backend/api/pregunta.php', { data: { id } });
          this.message = 'Pregunta eliminada';
          this.fetchPreguntas();
        } catch (error) {
          this.message = 'Error al eliminar la pregunta';
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 600px;
  }
  </style>
  