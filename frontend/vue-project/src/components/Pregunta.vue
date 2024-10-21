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
            <option v-for="tipo in tipos" :key="tipo.tipopreguntaid" :value="tipo.tipopreguntaid">{{ tipo.tipo }}</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="prueba" class="form-label">Prueba</label>
          <select v-model="pruebaID" class="form-select">
            <option v-for="prueba in pruebas" :key="prueba.pruebaid" :value="prueba.pruebaid">{{ prueba.instrucciones }}</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">Crear Pregunta</button>
      </form>
      
      <div v-if="message" class="alert alert-info mt-3">{{ message }}</div>
      
      <h3 class="mt-5">Lista de Preguntas</h3>
      <!-- Verificación si hay datos para mostrar -->
      <ul class="list-group" v-if="preguntas.length && tipos.length && pruebas.length">
        <li class="list-group-item" v-for="pregunta in preguntas" :key="pregunta.preguntaid">
          <div>
            <strong>Pregunta:</strong> {{ pregunta.contenido }}
          </div>
          <div>
            <strong>Tipo:</strong> {{ getTipoNombre(pregunta.tipopreguntaid) }}
          </div>
          <div>
            <strong>Prueba:</strong> {{ getPruebaInstrucciones(pregunta.pruebaid) }}
          </div>
          <button class="btn btn-sm btn-danger float-end" @click="deletePregunta(pregunta.preguntaid)">Eliminar</button>
        </li>
      </ul>
      <div v-else>
        <p>No se encontraron preguntas o los datos de tipos o pruebas no están disponibles.</p>
      </div>
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
        pruebas: [],
        message: ''
      };
    },
    created() {
      this.fetchPreguntas();
      this.fetchTipos();
      this.fetchPruebas();
    },
    methods: {
      // Obtener preguntas desde la API
      async fetchPreguntas() {
        try {
          const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/pregunta.php');
          console.log("Preguntas obtenidas:", response.data);  // Verificar los datos en la consola
          this.preguntas = response.data;
        } catch (error) {
          console.error("Error al cargar las preguntas:", error);
        }
      },
      // Obtener tipos de pregunta desde la API
      async fetchTipos() {
        try {
          const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/tipopregunta.php');
          console.log("Tipos obtenidos:", response.data);  // Verificar los datos en la consola
          this.tipos = response.data;
        } catch (error) {
          console.error("Error al cargar los tipos de pregunta:", error);
        }
      },
      // Obtener pruebas desde la API
      async fetchPruebas() {
        try {
          const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/prueba.php');
          console.log("Pruebas obtenidas:", response.data);  // Verificar los datos en la consola
          this.pruebas = response.data;
        } catch (error) {
          console.error("Error al cargar las pruebas:", error);
        }
      },
      // Crear una nueva pregunta
      async createPregunta() {
        try {
          const response = await axios.post('http://localhost/Sistema-Vocacional-Tecno/backend/api/pregunta.php', { 
            contenido: this.nuevoContenido, 
            tipoPreguntaID: this.tipoPreguntaID, 
            pruebaID: this.pruebaID 
          });
          this.message = response.data.message;
          this.fetchPreguntas();  // Refrescar la lista de preguntas después de crear una nueva
          this.nuevoContenido = '';  // Limpiar campos después de crear
        } catch (error) {
          this.message = 'Error al crear la pregunta';
        }
      },
      // Eliminar una pregunta por ID
      async deletePregunta(id) {
        try {
          await axios.delete('http://localhost/Sistema-Vocacional-Tecno/backend/api/pregunta.php', { data: { id } });
          this.message = 'Pregunta eliminada';
          this.fetchPreguntas();  // Refrescar la lista después de eliminar una pregunta
        } catch (error) {
          this.message = 'Error al eliminar la pregunta';
        }
      },
      // Obtener el nombre del tipo de pregunta
      getTipoNombre(tipopreguntaid) {
        if (!this.tipos.length) {
          console.warn('No hay tipos cargados');  // Verifica si los tipos están vacíos
        }
        const tipo = this.tipos.find(tipo => tipo.tipopreguntaid === tipopreguntaid);
        return tipo ? tipo.tipo : 'Tipo no encontrado';
      },
      // Obtener las instrucciones de la prueba
      getPruebaInstrucciones(pruebaid) {
        if (!this.pruebas.length) {
          console.warn('No hay pruebas cargadas');  // Verifica si las pruebas están vacías
        }
        const prueba = this.pruebas.find(prueba => prueba.pruebaid === pruebaid);
        return prueba ? prueba.instrucciones : 'Prueba no encontrada';
      }
    }
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 600px;
  }
  </style>
  