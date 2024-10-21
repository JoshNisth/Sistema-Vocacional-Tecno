<template>
    <div class="container mt-5">
      <h2 class="text-center">Administración de Tipos de Pregunta</h2>
      <form @submit.prevent="createTipoPregunta" class="bg-light p-4 rounded shadow">
        <div class="mb-3">
          <label for="tipo" class="form-label">Nuevo Tipo de Pregunta</label>
          <input v-model="nuevoTipo" type="text" class="form-control" id="tipo" placeholder="Ingrese el tipo de pregunta" required />
        </div>
        <button type="submit" class="btn btn-primary w-100">Crear Tipo</button>
      </form>
      
      <div v-if="message" class="alert alert-info mt-3">{{ message }}</div>
      
      <h3 class="mt-5">Lista de Tipos de Pregunta</h3>
      <ul class="list-group">
        <li class="list-group-item" v-for="tipo in tipos" :key="tipo.tipopreguntaid">  <!-- Asegúrate de usar las propiedades correctas -->
          {{ tipo.tipo }}  <!-- Cambia 'Tipo' a 'tipo' -->
          <button class="btn btn-sm btn-danger float-end" @click="deleteTipoPregunta(tipo.tipopreguntaid)">Eliminar</button>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        nuevoTipo: '',
        tipos: [],
        message: ''
      };
    },
    created() {
      this.fetchTipos();
    },
    methods: {
      async fetchTipos() {
        const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/TipoPregunta.php');
        console.log(response.data);  // Verifica si las propiedades tienen nombres en minúsculas o mayúsculas
        this.tipos = response.data;
      },
      async createTipoPregunta() {
        try {
          const response = await axios.post('http://localhost/Sistema-Vocacional-Tecno/backend/api/TipoPregunta.php', { tipo: this.nuevoTipo });
          this.message = response.data.message;
          this.fetchTipos();
          this.nuevoTipo = '';  // Limpiar el campo de texto después de crear el tipo de pregunta
        } catch (error) {
          this.message = 'Error al crear el tipo de pregunta';
        }
      },
      async deleteTipoPregunta(id) {
        try {
          await axios.delete('http://localhost/Sistema-Vocacional-Tecno/backend/api/TipoPregunta.php', { data: { id } });
          this.message = 'Tipo de pregunta eliminado';
          this.fetchTipos();
        } catch (error) {
          this.message = 'Error al eliminar el tipo de pregunta';
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
  