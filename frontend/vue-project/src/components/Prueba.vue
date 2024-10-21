<template>
    <div class="container mt-5">
        <h2 class="text-center">Administración de Pruebas</h2>
        <form @submit.prevent="createPrueba" class="bg-light p-4 rounded shadow">
            <div class="mb-3">
                <label for="instrucciones" class="form-label">Instrucciones</label>
                <input v-model="nuevasInstrucciones" type="text" class="form-control" id="instrucciones"
                    placeholder="Ingrese las instrucciones" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Crear Prueba</button>
        </form>

        <div v-if="message" class="alert alert-info mt-3">{{ message }}</div>

        <h3 class="mt-5">Lista de Pruebas</h3>
        <ul class="list-group">
            <li class="list-group-item" v-for="prueba in pruebas" :key="prueba.pruebaid">
                {{ prueba.instrucciones }} <!-- Cambia 'Instrucciones' a 'instrucciones' -->
                <button class="btn btn-sm btn-danger float-end" @click="deletePrueba(prueba.pruebaid)">Eliminar</button>
            </li>
        </ul>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            nuevasInstrucciones: '',
            pruebas: [],
            message: ''
        };
    },
    created() {
        this.fetchPruebas();  // Cargar las pruebas al crear el componente
    },
    methods: {
        async fetchPruebas() {
            try {
                const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/Prueba.php');
                console.log(response.data);  // Verifica en la consola qué datos están llegando
                this.pruebas = response.data;  // Asigna los datos a la variable 'pruebas'
            } catch (error) {
                console.error("Error al cargar las pruebas:", error);
            }
        },
        async createPrueba() {
            try {
                const fechaReg = new Date().toISOString().slice(0, 19).replace('T', ' ');  // Generar fecha actual
                const response = await axios.post('http://localhost/Sistema-Vocacional-Tecno/backend/api/Prueba.php', {
                    instrucciones: this.nuevasInstrucciones,
                    fechaReg: fechaReg
                });
                this.message = response.data.message;
                this.fetchPruebas();  // Refrescar la lista de pruebas después de crear una nueva
                this.nuevasInstrucciones = '';  // Limpiar el campo de texto
            } catch (error) {
                this.message = 'Error al crear la prueba';
            }
        },
        async deletePrueba(id) {
            try {
                await axios.delete('http://localhost/Sistema-Vocacional-Tecno/backend/api/Prueba.php', { data: { id } });
                this.message = 'Prueba eliminada';
                this.fetchPruebas();  // Refrescar la lista después de eliminar una prueba
            } catch (error) {
                this.message = 'Error al eliminar la prueba';
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
