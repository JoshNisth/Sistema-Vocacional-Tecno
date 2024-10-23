<template>
    <div class="container mt-5">
      <h2 class="text-center">Reporte de Resultados</h2>
      <div v-if="errorMessage" class="alert alert-danger" role="alert">
        {{ errorMessage }}
      </div>
      
      <!-- Tabla de resultados -->
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
        
        <!-- Gráfico de Resultados -->
        <div class="mt-5">
          <canvas id="resultadosChart"></canvas>
        </div>
      </div>
      
      <div v-else>
        <p>No se encontraron resultados para este usuario.</p>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { Chart, registerables } from 'chart.js';
  import { nextTick } from 'vue';
  
  // Registrar todos los componentes de Chart.js
  Chart.register(...registerables);
  
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
            await nextTick(); // Esperar a que el DOM esté completamente actualizado
            this.renderChart(); // Renderizar el gráfico cuando el DOM esté listo
          } else {
            this.errorMessage = response.data.message || 'No se encontraron resultados.';
          }
        } catch (error) {
          console.error("Error al obtener el reporte:", error);
          this.errorMessage = 'Error al obtener el reporte.';
        }
      },
      
      // Renderizar el gráfico de resultados
      renderChart() {
        const ctx = document.getElementById('resultadosChart').getContext('2d');
        const data = this.resultados.map(resultado => ({
          verbal: resultado.razonamientoverbal,
          numerico: resultado.razonamientonumerico,
          espacial: resultado.espacial,
          clerical: resultado.clerical
        }));
  
        const labels = this.resultados.map((_, index) => `Resultado ${index + 1}`);
  
        new Chart(ctx, {
          type: 'bar', // Tipo de gráfico
          data: {
            labels: labels,
            datasets: [
              {
                label: 'Razonamiento Verbal',
                backgroundColor: '#42A5F5',
                data: data.map(d => d.verbal)
              },
              {
                label: 'Razonamiento Numérico',
                backgroundColor: '#66BB6A',
                data: data.map(d => d.numerico)
              },
              {
                label: 'Espacial',
                backgroundColor: '#FFA726',
                data: data.map(d => d.espacial)
              },
              {
                label: 'Clerical',
                backgroundColor: '#FF7043',
                data: data.map(d => d.clerical)
              }
            ]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      }
    }
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 800px;
  }
  </style>
  