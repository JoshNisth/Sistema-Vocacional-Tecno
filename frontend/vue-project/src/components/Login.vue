<!-- src/components/Login.vue -->
<template>
  <div class="container mt-5">
    <h2 class="text-center">Inicio de Sesión</h2>
    <form @submit.prevent="loginUser" class="bg-light p-4 rounded shadow">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input v-model="email" type="email" class="form-control" id="email" placeholder="Email" required />
      </div>
      <div class="mb-3">
        <label for="contrasena" class="form-label">Contraseña</label>
        <input v-model="contrasena" type="password" class="form-control" id="contrasena" placeholder="Contraseña" required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
      <p v-if="message" class="mt-3 text-center">{{ message }}</p>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      email: '',
      contrasena: '',
      message: '',
    };
  },
  methods: {
    async loginUser() {
      try {
        const data = {
          Email: this.email,
          Contrasena: this.contrasena,
        };
        const response = await axios.post('http://localhost/Sistema-Vocacional-Tecno/backend/api/usuarios.php?login', data, {
          headers: {
            'Content-Type': 'application/json',
          },
        });
        this.message = response.data.message; // Maneja la respuesta de inicio de sesión
      } catch (error) {
        this.message = 'Error al iniciar sesión: ' + (error.response?.data?.message || error.message);
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
