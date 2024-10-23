<template>
    <div class="container mt-5">
        <h2 class="text-center">Administración de Opciones</h2>
        <form @submit.prevent="createOpcion" class="bg-light p-4 rounded shadow">
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción de la Opción</label>
                <input v-model="nuevaDescripcion" type="text" class="form-control" id="descripcion"
                    placeholder="Ingrese la descripción" required />
            </div>
            <div class="mb-3">
                <label for="inciso" class="form-label">Inciso</label>
                <input v-model="nuevoInciso" type="text" class="form-control" id="inciso" placeholder="Ingrese el inciso"
                    required />
            </div>
            <div class="mb-3">
                <label for="pregunta" class="form-label">Pregunta Asociada</label>
                <select v-model="preguntaId" class="form-select">
                    <option v-for="pregunta in preguntas" :key="pregunta.preguntaid" :value="pregunta.preguntaid">{{
                        pregunta.contenido }}</option>
                </select>



            </div>
            <button type="submit" class="btn btn-primary w-100">Crear Opción</button>
        </form>

        <div v-if="message" class="alert alert-info mt-3">{{ message }}</div>

        <h3 class="mt-5">Lista de Opciones</h3>
        <ul class="list-group">
            <li class="list-group-item" v-for="opcion in opciones" :key="opcion.OpcionID">
                <strong>{{ opcion.inciso }}:</strong> {{ opcion.descripcion }}
                <button class="btn btn-sm btn-danger float-end" @click="deleteOpcion(opcion.OpcionID)">Eliminar</button>
            </li>
        </ul>
    </div>
</template>
  
<script>
import axios from 'axios';

export default {
    data() {
        return {
            nuevaDescripcion: '',
            nuevoInciso: '',
            preguntaId: '',
            opciones: [],
            preguntas: [],
            message: ''
        };
    },
    created() {
        this.fetchOpciones();
        this.fetchPreguntas();
    },
    methods: {
        async fetchOpciones() {
            try {
                const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/Opcion.php');
                this.opciones = response.data;
            } catch (error) {
                console.error("Error al cargar las opciones:", error);
            }
        },
        async fetchPreguntas() {
            try {
                const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/Pregunta.php');
                console.log(response.data);  // Verifica cómo llegan los datos desde la API
                this.preguntas = response.data;
            } catch (error) {
                console.error("Error al cargar las preguntas:", error);
            }
        }
        ,
        async createOpcion() {
    try {
        console.log({
            descripcion: this.nuevaDescripcion,
            inciso: this.nuevoInciso,
            preguntaId: this.preguntaId
        }); // Imprimir los datos para verificar
        const response = await axios.post('http://localhost/Sistema-Vocacional-Tecno/backend/api/Opcion.php', {
            descripcion: this.nuevaDescripcion,
            inciso: this.nuevoInciso,
            preguntaId: this.preguntaId
        });
        this.message = response.data.message;
        this.fetchOpciones();
        // Limpiar los campos
        this.nuevaDescripcion = '';
        this.nuevoInciso = '';
        this.preguntaId = '';
    } catch (error) {
        this.message = 'Error al crear la opción';
        console.error(error);
    }
}


        ,
        async deleteOpcion(id) {
            try {
                await axios.delete('http://localhost/Sistema-Vocacional-Tecno/backend/api/Opcion.php', { data: { id } });
                this.message = 'Opción eliminada';
                this.fetchOpciones();
            } catch (error) {
                this.message = 'Error al eliminar la opción';
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
  