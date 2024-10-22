<template>
    <div class="container mt-5">
      <h2 class="text-center">Lista de Intereses</h2>
  
      <form @submit.prevent="addInteres" class="bg-light p-4 rounded shadow mb-4">
        <div class="mb-3">
          <label for="descripcion" class="form-label">Nuevo Interés</label>
          <input
            v-model="newInteres"
            type="text"
            class="form-control"
            id="descripcion"
            placeholder="Describe un nuevo interés"
            required
          />
        </div>
        <button type="submit" class="btn btn-primary w-100">Agregar Interés</button>
      </form>
  
      <ul class="list-group">
        <li v-for="interes in intereses" :key="interes.interesid" class="list-group-item">
          ID: {{ interes.interesid }} - Descripción: {{ interes.descripcioninteres }}
        </li>
      </ul>


    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        intereses: [],
        newInteres: '',
      };
    },
    
    methods: {
      async fetchIntereses() {
        try {
          const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/intereses.php?interes'); 
          console.log('Datos obtenidos de la API:', response.data); // Añadir esta línea
          this.intereses = response.data;
        } catch (error) {
          console.error('Error al obtener intereses:', error);
        }
      },
      async addInteres() {
        if (this.newInteres.trim() === '') return;
  
        try {
          const data = {
            DescripcionInteres: this.newInteres,
          };
          await axios.post('http://localhost/Sistema-Vocacional-Tecno/backend/api/intereses.php', data, {
            headers: {
              'Content-Type': 'application/json',
            },
          });
          this.newInteres = '';
          this.fetchIntereses(); // Refresca la lista de intereses
        } catch (error) {
          console.error('Error al agregar interés:', error);
        }
      },
    },
    mounted() {
      this.fetchIntereses();
    },
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 600px;
  }
  </style>