<template>
  <main class="main">
    <!-- Breadcrumb -->

    <ol class="breadcrumb" style="padding-left: 30px;">
      <li class="breadcrumb-item">Inicio</li>
      <li class="breadcrumb-item active">Historial de Proyectos</li>
    </ol>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div v-if="loadTable == true" class="card" style="border: none;">
        <table-loader></table-loader>
      </div>
      <div v-else class="card" style="border: none;">
        <div class="card-body">
          <table class="table table-bordered table-hover table-sm" style="font-size: 1.25em;">
            <thead>
              <tr>
                <!--<th>Opciones</th> -->
                <!--<th>Numero</th>-->
                <th style="text-align: center; width: 10%;">Contraparte</th>
                <th style="text-align: center; width: 20%;">Proyecto</th>
                <th id="disappear" style="text-align: center;">Perfil Estudiante</th>
                <th id="resized" style="width: 30px; text-align: center;">Estado del proyecto</th>
                <th id="resized" style="width: 15px; text-align: center;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="proyecto in arrayProyectos" :key="proyecto.idProyecto">
                <!--<td>{{ index + 1 }}</td>-->
                <td v-text="proyecto.contraparte" @click="abrirModal('info', proyecto)" data-toggle="modal"
                  data-target="#modal-info"></td>
                <td v-text="proyecto.nombre" @click="abrirModal('info', proyecto)" data-toggle="modal"
                  data-target="#modal-info"></td>
                <td id="disappear" v-text="proyecto.perfil_estudiante" @click="abrirModal('info', proyecto)"
                  data-toggle="modal" data-target="#modal-info"></td>
                <td id="disappear" v-text="proyecto.estado_proyecto" @click="abrirModal('info', proyecto)"
                  data-toggle="modal" data-target="#modal-info"></td>
                <td @click="abrirModal('info', proyecto)" style="text-align: center;">
                  <div class="button-container">
                    <button type="button" @click="abrirModal('estado', proyecto)" data-toggle="modal"
                      data-target="#statusModal" class="btn btn-danger btn-sm" style="margin: 8px 0; width: 100%;">
                      <i class="icon-lock"></i>
                      <span class="btn-label">Cambiar estado</span>
                    </button>
                  </div>
                  <div class="button-container">
                    <button type="button" @click="abrirModal('estudiantes', proyecto)" data-toggle="modal"
                      data-target="#membersModal" class="btn btn-info btn-sm" id="membersbutton"
                      style="margin-bottom: 8px; width: 100%;">
                      <i class="icon-people"></i>
                      <span class="btn-label">Miembros</span>
                      <span id="badge" v-if="proyecto.notificaciones == 1"></span>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination" style="float: right;">
              <li class="page-item" v-if="pagination.current_page > 1">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)"
                  style="display: flex; justify-content: center; align-items: center; width: 32px; height: 35px;"><img
                    :src="ruta + '/img/icons/chevron_left_black_24dp.svg'" alt="chevron-left"></a>
              </li>
              <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
              </li>
              <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)"
                  style="display: flex; justify-content: center; align-items: center; width: 32px; height: 35px;"><img
                    :src="ruta + '/img/icons/chevron_right_black_24dp.svg'" alt="chevron-right"></a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>

    <!--Inicio del modal informacion de proyecto-->
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-info">
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="modal_nombre">
              Aplicar a proyecto
            </h4>
            <button type="button" class="close" data-dismiss="modal" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered table-sm" style="font-size: 1.35em; margin-top: 10px">
              <tbody>
                <tr>
                  <th style="background-color: #dedede;">Contraparte</th>
                  <td v-text="modal_contraparte" style="padding-left: 16px;"></td>
                </tr>
                <tr>
                  <th style="background-color: #dedede;">Perfil Estudiante</th>
                  <td v-text="modal_perfil_estudiante" style="padding-left: 16px;"></td>
                </tr>
                <tr>
                  <th style="background-color: #dedede;">Tipo</th>
                  <td v-text="modal_tipo_horas" style="padding-left: 16px;"></td>
                </tr>
                <tr>
                  <th style="background-color: #dedede;">Cupos</th>
                  <td v-text="`${modal_cupos_act}${'/'}${modal_cupos}`" style="padding-left: 16px;"></td>
                </tr>
                <tr>
                  <th style="background-color: #dedede;">Horario</th>
                  <td v-text="modal_horario" style="padding-left: 16px;"></td>
                </tr>
                <tr>
                  <th style="background-color: #dedede;">Encargado</th>
                  <td v-text="modal_encargado" style="padding-left: 16px;"></td>
                </tr>
                <tr>
                  <th style="background-color: #dedede;">Fecha inicial</th>
                  <td v-text="modal_fecha_in" style="padding-left: 16px;"></td>
                </tr>
                <tr>
                  <th style="background-color: #dedede;">Fecha final</th>
                  <td v-text="modal_fecha_fin" style="padding-left: 16px;"></td>
                </tr>

              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="cerrarModal()">Cerrar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="statusModal" aria-hidden="true">
      <div v-if="loading == 1">
        <spinner></spinner>
      </div>
      <div v-if="loading == 0" class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Cambiar estado al proyecto {{ modal_nombre }}</h4>
            <button type="button" data-dismiss="modal" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row div-form">
              <label class="col-md-3 form-control-label" for="text-input">Estado del proyecto</label>
              <div class="col-md-9">
                <select class="form-control" v-model="estado_proyecto">
                  <option value="En curso">En curso</option>
                  <option value="Finalizado">Finalizado</option>
                  <option value="Cancelado">Cancelado</option>
                </select>
              </div>
            </div>
            <h5>Por favor escriba <b>{{ modal_nombre }}</b> para confirmar el cambio de estado de este proyecto</h5>
            <div class="col-md-9 -alt">
              <input type="text" v-model="modal_confirmar" class="form-control">
              <p :class="{ show: errorEstado == 1, hide: errorEstado != 1 }" class="error">El texto ingresado no coincide
                con el solicitado</p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button type="button" class="btn btn-primary" v-bind:data-dismiss="flagErrorEstado ? '' : 'modal'"
              @click="estadoProyecto()">Confirmar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <!--Fin del modal-->
    <footer class="app-footer" id="footer"
      style="display: flex; flex-direction: column; justify-content: center; font-size: 15px; padding: 10px 0px">
      <span><a target="_blank" href="http://www.uca.edu.sv/servicio-social/">Centro de Servicio Social | UCA</a> &copy;
        2021</span>
      <span>Desarrollado por <a href="#"></a>Grupo de Horas Sociales</span>
    </footer>
  </main>
</template>

<script>
import { API_HOST } from "../constants/endpoint.js";
import { API_HOST_ASSETS } from '../constants/endpoint.js';

export default {
  data() {
    return {
      ruta: API_HOST_ASSETS,
      loadTable: false,
      loading: 0,
      user_email: '',
      nombre: "",
      descripcion: "",
      arrayProyectos: [""],
      modal: 0,
      id_proyecto: 0,
      modal_encargado: "",
      modal_nombre: "",
      modal_desc: "",
      modal_perfil_estudiante: "",
      modal_contraparte: "",
      modal_tipo_horas: "",
      modal_cupos_act: 0,
      modal_cupos: 0,
      modal_horario: "",
      modal_fecha_in: "",
      modal_fecha_fin: "",
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      estado_proyecto: "",
      modal_confirmar: "",
      errorEstado: 0,
      flagErrorEstado: false,
      offset: 3,
    };
  },
  computed: {
    isActived: function () {
      return this.pagination.current_page;
    },
    pagesNumber: function () {
      if (!this.pagination.to) {
        return [];
      }
      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }
      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
  },
  methods: {
    bindData(page) {
      let me = this;
      me.loadTable = true;
      //var url2 = '/public/proyecto?page=' + page;
      var url = `${API_HOST}/historial_proyectos?page=${page}`;
      axios.get(url).then(function (response) {
        var respuesta = response.data;
        var proyectos = respuesta.proyectos.data;
        me.arrayProyectos = proyectos;
        me.pagination = respuesta.pagination;
        me.loadTable = false;
      })
        .catch(function (error) {
          console.log(error);
        });
      axios.get(`${API_HOST}/get_user`).then(function (response) {
        me.user_email = response.data.correo;
      })
        .catch(function (error) {
          console.log(error);
        });
    },
    cambiarPagina(page) {
      let me = this;
      me.pagination.current_page = page;
      me.bindData(page);
    },
    cerrarModal() {
      this.modal = 0;
      this.moda2 = 0;
    },
    estadoProyecto() {
      let me = this;
      if (me.modal_confirmar != me.modal_nombre) {
        me.flagErrorEstado = true
        me.errorEstado = 1
      }
      else {
        me.loading = 1
        var estado = (this.estado_proyecto == "En curso" ? 1 : 0);

        axios.put(`${API_HOST}/proyecto/estado`, {
          'idProyecto': this.id_proyecto,
          'estado': estado,
          'estado_proyecto': this.estado_proyecto
        }).then(function (response) {
          $('#statusModal').modal('hide');
          me.loading = 2;
          me.bindData(1);
          me.cerrarModal();
        }).catch(function (error) {
          console.log(error);
        });
      }
    },
    abrirModal(modelo, data = []) {
      switch (modelo) {
        case "info": {
          this.modal = 1;
          this.id_proyecto = data.idProyecto;
          this.modal_encargado = data.encargado;
          this.modal_nombre = data.nombre;
          this.modal_desc = data.descripcion;
          this.modal_tipo_horas = data.tipo_horas;
          this.modal_cupos_act = data.cupos_act;
          this.modal_cupos = data.cupos;
          this.modal_horario = data.horario;
          this.modal_fecha_in = data.fecha_inicio;
          this.modal_fecha_fin = data.fecha_fin;
          this.modal_perfil_estudiante = data.perfil_estudiante;
          this.modal_contraparte = data.contraparte;
          break;
        }
        case "estado":
          {
            this.modal2 = 1;
            this.id_proyecto = data.idProyecto;
            this.modal_nombre = data.nombre;
            this.estado_proyecto = data.estado_proyecto;
            this.errorEstado = 0;
            this.modal_confirmar = '';
            this.flagErrorEstado = false;
            break;
          }
        default:
          break;
      }
    },
    logout() {
      var url = `${API_HOST}/logout`;
      axios.post(url).then(() => location.href = `${API_HOST}/`)
    }
  },
  mounted() {
    this.bindData();
  },
};
</script>
<style>
@font-face {
  font-family: 'Abel';
  src: url(/css-proyecto/public/fonts/Abel-Regular.ttf);
}

.main {
  font-family: 'Abel';
}

.modal-content {
  width: 100% !important;
  position: absolute !important;
}

.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  background-color: #3c29297a !important;
}

@media screen and (max-width: 500px) {
  #disappear {
    display: none;
  }

  #resized {
    width: 1% !important;
  }
}
</style>
