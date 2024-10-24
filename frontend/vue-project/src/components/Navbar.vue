<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Sistema Vocacional</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <router-link to="/register" class="nav-link">Registro</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/login" class="nav-link">Inicio de Sesión</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/usuarios" class="nav-link">Lista de Usuarios</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/tipos-pregunta" class="nav-link">Tipos de Pregunta</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/pruebas" class="nav-link">Pruebas</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/preguntas" class="nav-link">Preguntas</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/opciones" class="nav-link">Opciones</router-link> <!-- Enlace a la administración de opciones -->
          </li>
          <li class="nav-item">
              <router-link to="/reporte" class="nav-link">Reporte</router-link>
            </li>
            <li>
            <router-link to="/intereses" class="nav-link">Intereses</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/intereses_usuario" class="nav-link">Intereses Usuario</router-link>
          </li>
          <li>
            <router-link to="/realizarPrueba" class="nav-link">Realizar Prueba</router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navbar',
};
</script>

<style scoped>
/* Estilos específicos para la navbar si lo deseas */
/* Aquí puedes agregar estilos específicos para la navbar si lo deseas */
</style>
