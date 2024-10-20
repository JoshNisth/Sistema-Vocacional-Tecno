<template>
    
    <div class="container mt-5">
        <h2 class="text-center">Registro de Usuario</h2>
        <form @submit.prevent="registerUser" class="bg-light p-4 rounded shadow">
            <div class="mb-3">
                <label for="nombres" class="form-label">Nombres</label>
                <input v-model="nombres" type="text" class="form-control" id="nombres" placeholder="Nombres" required />
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input v-model="apellidos" type="text" class="form-control" id="apellidos" placeholder="Apellidos"
                    required />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input v-model="email" type="email" class="form-control" id="email" placeholder="Email" required />
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input v-model="contrasena" type="password" class="form-control" id="contrasena" placeholder="Contraseña"
                    required />
            </div>
            <div class="mb-3">
                <label for="rolid" class="form-label">Rol</label>
                <select v-model="rolid" class="form-select" id="rolid" required>
                    <option value="" disabled selected>Seleccionar Rol</option>
                    <option value="1">Usuario</option>
                    <option value="2">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Registrar</button>
        </form>
        <p v-if="message" class="mt-3 text-center">{{ message }}</p>
    </div>
</template>
  
<script>
import axios from 'axios';
import { useRouter } from 'vue-router'; // Importar useRouter

export default {
    data() {
        return {
            nombres: '',
            apellidos: '',
            email: '',
            contrasena: '',
            rolid: '',
            message: '',
        };
    },
    setup() {
        const router = useRouter(); // Usar useRouter para redireccionar

        return { router };
    },
    methods: {
        async registerUser() {
            try {
                const data = {
                    Nombres: this.nombres,
                    Apellidos: this.apellidos,
                    Email: this.email,
                    Contrasena: this.contrasena,
                    RolID: this.rolid,
                };
                const response = await axios.post('http://localhost/Sistema-Vocacional-Tecno/backend/api/usuarios.php', data, {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });
                this.message = response.data.message;

                // Redirigir a la página de login
                this.$router.push({ name: 'Login' });
            } catch (error) {
                this.message = 'Error al registrar: ' + (error.response?.data?.message || error.message);
            }
        },
    },

};
</script>
  
<style>
.container {
    max-width: 400px;
}
</style>
  