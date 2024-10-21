<template>
  <div class="container mt-5">
    <h2 class="text-center">Lista de Usuarios</h2>

    <!-- Alerta para mensajes -->
    <div v-if="message" class="alert alert-success mt-3" role="alert">
      {{ message }}
    </div>

    <!-- Mostrar lista de usuarios -->
    <ul class="list-group" v-if="usuarios.length">
      <li class="list-group-item" v-for="usuario in usuarios" :key="usuario.usuarioid">
        <div>
          <strong>Nombre:</strong> {{ usuario.nombres }} {{ usuario.apellidos }}
        </div>
        <div>
          <strong>Email:</strong> {{ usuario.email }}
        </div>
        <div>
          <strong>Rol:</strong> {{ getRol(usuario.rolid) }}
        </div>
        <!-- Botón de eliminar -->
        <button class="btn btn-sm btn-danger float-end"
          @click="deleteUsuario(usuario.usuarioid, usuario.nombres)">Eliminar</button>
      </li>
    </ul>

    <!-- Mostrar mensaje cuando no haya usuarios -->
    <div v-else>
      <p>No se encontraron usuarios.</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      usuarios: [], // Lista de usuarios obtenidos de la API
      message: '',  // Mensaje de éxito o error
    };
  },
  created() {
    this.fetchUsuarios();  // Cargar la lista de usuarios al iniciar el componente
  },
  methods: {
    // Obtener la lista de usuarios desde la API
    async fetchUsuarios() {
      try {
        console.log("Llamando a fetchUsuarios");
        const response = await axios.get('http://localhost/Sistema-Vocacional-Tecno/backend/api/usuarios.php');
        console.log("Usuarios obtenidos:", response.data);  // Verificar en la consola si los usuarios están llegando correctamente
        this.usuarios = response.data;
      } catch (error) {
        console.error("Error al cargar los usuarios:", error);
      }
    },

    // Eliminar un usuario
    async deleteUsuario(usuarioid, nombres) {
      try {
        console.log(`Eliminando usuario con ID: ${usuarioid} y Nombres: ${nombres}`);
        const response = await axios.delete('http://localhost/Sistema-Vocacional-Tecno/backend/api/borrar_usuario.php', {
          params: { usuarioid, nombres }
        });

        console.log("Respuesta del servidor al eliminar:", response.data);
        
        // Si la eliminación fue exitosa, actualizar el mensaje
        if (response.data.status === 'success') {
          this.message = 'Usuario eliminado con éxito.';
          console.log("Mensaje establecido:", this.message);

          this.fetchUsuarios();  // Refrescar la lista de usuarios
          // Ocultar el mensaje después de 3 segundos
          setTimeout(() => {
            this.message = '';
          }, 3000);
        } else {
          console.log("Error del servidor:", response.data.message);
        }
      } catch (error) {
        console.error("Error al eliminar el usuario:", error);
        this.message = 'Error al eliminar el usuario.';
      }
    },

    // Obtener el rol del usuario
    getRol(rolid) {
      switch (rolid) {
        case 1:
          return 'Usuario';
        case 2:
          return 'Admin';
        default:
          return 'Desconocido';
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
