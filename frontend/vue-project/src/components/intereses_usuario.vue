<template>
    <div class="container mt-5">
      <h2 class="text-center">Relaciones de Usuarios e Intereses</h2>
  
      <form @submit.prevent="addUsuarioInteres" class="bg-light p-4 rounded shadow mb-4">
        <div class="mb-3">
          <label for="usuario" class="form-label">ID de Usuario</label>
          <input
            v-model="newUsuarioID"
            type="number"
            class="form-control"
            id="usuario"
            placeholder="Ingresa el ID del usuario"
            required
          />
        </div>
  
        <div class="mb-3">
          <label for="interes" class="form-label">ID del Interés</label>
          <input
            v-model="newInteresID"
            type="number"
            class="form-control"
            id="interes"
            placeholder="Ingresa el ID del interés"
            required
          />
        </div>
  
        <button type="submit" class="btn btn-primary w-100">Agregar Relación</button>
      </form>
  
      <!-- Lista de relaciones entre usuarios e intereses -->
      <ul class="list-group">
        <li v-for="relacion in relaciones" :key="relacion.usuarioid + '-' + relacion.interesid" class="list-group-item">
          Usuario: {{ relacion.usuarioid }} - Interés: {{ relacion.interesid }} - Fecha: {{ relacion.fecharegistro }}
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        relaciones: [], // Lista de relaciones entre usuarios e intereses
        newUsuarioID: '', // ID del usuario
        newInteresID: '', // ID del interés
      };
    },
    
    methods: {
      // Método para obtener todas las relaciones entre usuarios e intereses
      async fetchRelaciones() {
        try {
          const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/intereses_usuario.php');
          this.relaciones = response.data;
        } catch (error) {
          console.error('Error al obtener relaciones:', error);
        }
      },
      // Método para agregar una nueva relación de usuario e interés
      async addUsuarioInteres() {
        if (this.newUsuarioID.trim() === '' || this.newInteresID.trim() === '') return;
  
        try {
          const data = {
            UsuarioID: this.newUsuarioID,
            InteresID: this.newInteresID,
          };
          await axios.post('http://localhost/Sistema-Vocacional-Tecno/backend/api/intereses_usuario.php', data, {
            headers: {
              'Content-Type': 'application/json',
            },
          });
          this.newUsuarioID = '';
          this.newInteresID = '';
          this.fetchRelaciones(); // Refresca la lista de relaciones
        } catch (error) {
          console.error('Error al agregar relación:', error);
        }
      },
    },
    mounted() {
      // Cargar las relaciones cuando el componente se monta
      this.fetchRelaciones();
    },
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 600px;
  }
  </style>
  