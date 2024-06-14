<template>
  <main class="main" style="background-color: white;">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Inicio</li>
          <li class="breadcrumb-item active">Recordatorio</li>
      </ol>

      <div class="container-fluid px-4">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Horas Sociales
        </div>
        <!-- <img :src="imgsrc" /> -->
        <div class="card-body">
          <b>
          </b>
          <ul>
            <li>
              <span>
                <b> 600 Horas sociales </b>  para Ingenierias, Arquitectura y Licenciaturas 
              </span>
            </li>

            <li>
              <span> 
               <b> 300 Horas sociales </b> para Profesorados de Inglés 
              </span>
            </li>

            <li>
              <span> 
                <b> 150 Horas sociales </b> para otros Profesorados y Técnicos 
              </span>
            </li>
        </ul>
        <div>
          <p>Todas las carreas al menos el 50% en contacto con la realidad </p>
        </div>
        </div>
      </div>
    </div>

    <footer class="app-footer" id="footer"
      style="display: flex; flex-direction: column; justify-content: center; font-size: 15px; padding: 10px 0px">
      <span><a target="_blank" href="http://www.uca.edu.sv/servicio-social/">Centro de Servicio Social | UCA</a> &copy;
        2024</span>
      <span>Desarrollado por <a href="#"></a>Grupo de Horas Sociales</span>
    </footer>
  </main>
</template>
<script>
export default {
  data() {
    return {
      imgsrc: "./img/recordatorio.ae2960e4.png"
    }
  }
}
</script>
<style scoped>
.recordatorio-container {
  padding-bottom: 37.88%;
  padding-top: 60px;
  position: relative;
}

.recordatorio-container img {
  bottom: 0;
  height: 100%;
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
  width: 100%;
}

/* @media screen and (max-width: 991px) {
  .container-fluid {
    margin-top: 55px;
  }
} */
</style>