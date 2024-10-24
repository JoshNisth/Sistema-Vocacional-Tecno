<template>
  <div class="container mt-5">
    <h2 class="text-center">Relación de Usuarios e Intereses</h2>

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
        Usuario: {{ usuarios[relacion.usuarioid] || 'Desconocido' }} (ID: {{ relacion.usuarioid }}) - 
        Interés: {{ intereses[relacion.interesid] || 'Desconocido' }} (ID: {{ relacion.interesid }}) - 
        Fecha: {{ relacion.fecharegistro }}
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      relaciones: [], 
      newUsuarioID: '', 
      newInteresID: '', 
      usuarios: {}, 
      intereses: {},
    };
  },
  
  methods: {
    async fetchRelaciones() {
      try {
        const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/intereses_usuario.php');
        console.log('Relaciones:', response.data); 
        this.relaciones = response.data;
      } catch (error) {
        console.error('Error al obtener relaciones:', error);
      }
    },

    async fetchUsuarios() {
      try {
        const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/usuarios.php');
        console.log('Usuarios:', response.data); 
        response.data.forEach(usuario => {
          this.usuarios[usuario.usuarioid] = usuario.nombres; 
        });
      } catch (error) {
        console.error('Error al obtener usuarios:', error);
      }
    },

    async fetchIntereses() {
      try {
        const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/intereses.php');
        console.log('Intereses:', response.data); 
        response.data.forEach(interes => {
          this.intereses[interes.interesid] = interes.descripcioninteres; 
        });
      } catch (error) {
        console.error('Error al obtener intereses:', error);
      }
    },

    async addUsuarioInteres() {
      if (!this.newUsuarioID || !this.newInteresID) return;

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
        this.fetchRelaciones(); 
      } catch (error) {
        console.error('Error al agregar relación:', error);
      }
    },
  },
  mounted() {
    this.fetchRelaciones(); 
    this.fetchUsuarios(); 
    this.fetchIntereses(); 
  },
};
</script>

<style scoped>
.container {
  max-width: 700px;
}
</style>