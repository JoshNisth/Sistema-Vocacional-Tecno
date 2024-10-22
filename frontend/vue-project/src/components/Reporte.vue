<template>
    <div class="container mt-5">
      <h2 class="text-center">Reporte de Resultados</h2>
      <div v-if="errorMessage" class="alert alert-danger" role="alert">
        {{ errorMessage }}
      </div>
      
      <div v-if="resultados.length">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Resumen de intereses</th>
              <th>Razonamiento Verbal</th>
              <th>Razonamiento Numérico</th>
              <th>Espacial</th>
              <th>Clerical</th>
              <th>Sugerencia Vocacional</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(resultado, index) in resultados" :key="resultado.resultadoid">
              <td>{{ index + 1 }}</td>
              <td>{{ resultado.resumenintereses }}</td>
              <td>{{ resultado.razonamientoverbal }}</td>
              <td>{{ resultado.razonamientonumerico }}</td>
              <td>{{ resultado.espacial }}</td>
              <td>{{ resultado.clerical }}</td>
              <td>{{ resultado.sugerenciavocacional }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else>
        <p>No se encontraron resultados para este usuario.</p>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        usuarioId: 38, // Temporalmente ingresado manualmente
        resultados: [],
        errorMessage: ''
      };
    },
    created() {
      this.fetchResultados();
    },
    methods: {
      // Obtener los resultados de la API
      async fetchResultados() {
        try {
          const response = await axios.get(`http://localhost/Sistema-Vocacional-Tecno/backend/api/reporte.php`, {
            params: { usuarioid: this.usuarioId }
          });
          console.log("Resultados obtenidos:", response.data);
          
          if (response.data.status === 'success') {
            this.resultados = response.data.data;
          } else {
            this.errorMessage = response.data.message || 'No se encontraron resultados.';
          }
        } catch (error) {
          console.error("Error al obtener el reporte:", error);
          this.errorMessage = 'Error al obtener el reporte.';
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 800px;
  }
  </style>
  