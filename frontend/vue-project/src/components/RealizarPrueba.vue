<template>
  <div class="container mt-5">
    <h2 class="text-center">Test de orientación vocacional</h2>

    <div v-for="(pregunta, index) in preguntas" :key="index" class="mb-4">
      <h5>{{ pregunta.contenido }}</h5>
      <ul class="list-group">
        <li v-for="(opcion, idx) in filtrarOpcionesPorPregunta(pregunta.preguntaid)" :key="idx" class="list-group-item">
          <button 
            :class="['btn', 'w-100', opcionSeleccionada(pregunta.preguntaid, opcion.opcionid) ? 'btn-primary' : 'btn-outline-primary']" 
            @click="seleccionarOpcion(pregunta.preguntaid, opcion.opcionid)">
            {{ opcion.descripcion }}
          </button>
        </li>
      </ul>
    </div>

    <!-- Mensaje de confirmación -->
    <div v-if="mensaje" class="alert alert-success mt-4" role="alert">
      {{ mensaje }}
    </div>

    <!-- Botón al final de las preguntas -->
    <div class="text-center mt-4" id="divBtn">
      <button class="btn btn-success" @click="procesarTest">
        Enviar Respuestas
      </button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      preguntas: [],  // Preguntas con sus opciones asociadas
      opciones: [],   // Opciones de todas las preguntas
      respuestasSeleccionadas: {},  // Almacena las respuestas seleccionadas por pregunta
      respuestasConFecha: [],  // Almacena las respuestas con fecha y opcionId
      mensaje: '',  // Mensaje para retroalimentación
      usuarioId: 38  // Asigna el ID del usuario (esto puede venir de la autenticación)
    };
  },

  created() {
    // Llamar a la API para obtener preguntas y opciones cuando el componente se monte
    this.fetchPreguntasYOpciones();
  },

  methods: {
    async fetchPreguntasYOpciones() {
      try {
        // Obtener preguntas
        console.log("Llamado a la API para obtener preguntas");
        const responsePreguntas = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/Pregunta.php');
        console.log("Preguntas obtenidas: ", responsePreguntas.data);
        this.preguntas = responsePreguntas.data;

        // Obtener opciones
        console.log("Llamado a la API para obtener opciones");
        const responseOpciones = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/Opcion.php');
        console.log("Opciones obtenidas: ", responseOpciones.data);
        this.opciones = responseOpciones.data;

      } catch (error) {
        console.error("Error al cargar las preguntas o opciones:", error);
      }
    },

    // Filtrar las opciones por el ID de la pregunta
    filtrarOpcionesPorPregunta(preguntaId) {
      return this.opciones.filter(opcion => opcion.preguntaid === preguntaId);
    },

    seleccionarOpcion(preguntaId, opcionId) {
      // Almacenar la opción seleccionada
      this.respuestasSeleccionadas[preguntaId] = opcionId;

      // Obtener la fecha actual
      const fechaRespuesta = new Date().toISOString();

      // Agregar la opción seleccionada con la fecha al array
      this.respuestasConFecha.push({
        fechaRespuesta: fechaRespuesta,
        opcionId: opcionId
      });

      console.log("Respuestas con fecha:", this.respuestasConFecha);

      // Mostrar mensaje de confirmación
      this.mensaje = 'Has seleccionado una opción para la pregunta ' + preguntaId;
      setTimeout(() => {
        this.mensaje = '';
      }, 3000);
    },

    opcionSeleccionada(preguntaId, opcionId) {
      return this.respuestasSeleccionadas[preguntaId] === opcionId;
    },

    // Función para procesar el test al hacer clic en el botón
    async procesarTest() {
      try {
        const payload = {
          respuestas: this.respuestasConFecha,
          UsuarioID: this.usuarioId,
        };

        // Enviar los datos al backend
        const response = await axios.post('http://localhost/Sistema-Vocacional-Tecno/backend/api/registrar_respuesta.php', payload);
        console.log("Respuesta del servidor:", response.data);
        this.mensaje = 'Respuestas enviadas correctamente';
      } catch (error) {
        console.error("Error al enviar respuestas:", error);
        this.mensaje = 'Error al enviar las respuestas';
      }
    },
  }
};
</script>

<style scoped>
.container {
  max-width: 600px;
}

.list-group-item {
  margin-bottom: 10px;
}

.btn-outline-primary {
  border-radius: 0;
  font-size: 1.2rem;
}

.btn-primary {
  border-radius: 0;
  font-size: 1.2rem;
  background-color: #007bff;
  color: white;
}

.btn-success {
  font-size: 1.2rem;
  padding: 10px 20px;
}

#divBtn {
  padding-bottom: 20%;
}
</style>
