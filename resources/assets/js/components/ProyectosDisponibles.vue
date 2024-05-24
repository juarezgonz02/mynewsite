<template>
    <main class="main" style="background-color: white;">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Inicio</li>
            <li class="breadcrumb-item active">Proyectos Disponibles</li>
        </ol>

        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div v-if="loadTable == true" class="card" style="border: none;">
                <table-loader></table-loader>
            </div>
            <div v-else class="card" style="border: none;">
                <div v-if="ya_aplico_hoy || ya_aplico_proyecto || timeout != ''" style="margin: 20px 0px 0px 20px;">
                    <b style="color:red">
                        <p v-if="ya_aplico_hoy">No puede aplicar a otro proyecto este día. Inténtelo mañana nuevamente.
                        </p>

                    </b>
                    <b style="color:red">
                        <p v-if="ya_aplico_proyecto">No puede aplicar a un nuevo proyecto. Ya se encuentra inscrito a un
                            proyecto </p>
                    </b>
                    <b style="color:red" v-if="timeout != ''">
                        <p>Podra aplicar a un nuevo proyectos el dia</p>
                        <p v-text="timeout"></p>

                    </b>
                </div>
                <div class="card-body">
                    <!-- Barra accion superior -->
                    <div class="form-group" style=" flex-wrap: wrap; flex-direction: column-reverse;">
                        <div class="filter-group">
                            <form class="search-group" @submit.prevent="bindDataByFilters(0)">
                                <div class="search-bar">
                                    <input class="search-input" style="margin: auto; width: 100%;" type="text"
                                        v-model="filtrandoPorNombre" placeholder="Buscar por nombre del proyecto">
                                </div>
                                <div style="flex-direction: column-reverse">
                                    <button type="button" @click="bindDataByFilters(0)"
                                        class="btn btn-primary search-button"><i class="icon-magnifier"></i>
                                        Buscar</button>
                                </div>
                            </form>

                            <div class="filter-group">

                                <div class="filter-selector">

                                    <div class="btn-group">

                                        <button type="button" class="btn btn-default dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Filtrar <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <!-- v-model="filtrandoPorCarrera"> -->
                                            <!-- @change="bindDataByFilters(0)"> -->

                                            <li @click="cambiarFiltro(JSON.stringify({ por: 'carrera', id: -1 }))"
                                                disabled>
                                                <b> Filtrar por: </b>
                                            </li>

                                            <li @click="cambiarFiltro(JSON.stringify({ por: 'carrera', id: -1 }))">
                                                <button class="text-button"> Todas las carreras </button>
                                            </li>

                                            <li role="separator" class="divider"></li>
                                            <li @click="cambiarFiltro(JSON.stringify({ por: 'carrera', id: -2 }))">
                                                <button class="text-button">
                                                    Todas las carreras excepto Psicologia e Ing. Civil
                                                </button>
                                            </li>
                                            <!-- <optgroup label="Factultad"> -->
                                            <li role="separator" class="divider"></li>
                                            <li>
                                                <b> Facultad: </b>
                                            </li>
                                            <li v-for="facultad in arrayFactultad">
                                                <!-- :value="JSON.stringify({ por: 'facultad', id: facultad.idFacultad })" -->
                                                <!-- :key="facultad.idFacultad"> -->
                                                <button
                                                    @click="cambiarFiltro(JSON.stringify({ por: 'facultad', id: facultad.idFacultad }))"
                                                    class="text-button"> {{ facultad.nombre }} </button>
                                            </li>

                                            <li role="separator" class="divider"></li>
                                            <li :value="JSON.stringify({ por: 'carrera', id: -1 })" disabled>
                                                <b> Carrera: </b>
                                            </li>
                                            <li v-for="carrera in arrayCarreras">
                                                <!-- :value="JSON.stringify({ por: 'carrera', id: carrera.idCarrera })"
                                            :key="carrera.idCarrera"> -->
                                                <button
                                                    @click="cambiarFiltro(JSON.stringify({ por: 'carrera', id: carrera.idCarrera }))"
                                                    class="text-button"> {{ carrera.nombre }} </button>
                                            </li>
                                            <!-- </optgroup> -->
                                        </ul>
                                    </div>
                                </div>

                                <div class="filter-selector">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        Año <span class="caret"></span>
                                    </button>

                                    <ul class="dropdown-menu">
                                        <li role="separator" class="divider"></li>
                                        <li value="0" disabled selected> <b> Año: </b> </li>
                                        <li @click="cambiarAno(1)"> <button class="text-button"> Primer Año </button>
                                        </li>
                                        <li @click="cambiarAno(2)"> <button class="text-button"> Segundo Año </button>
                                        </li>
                                        <li @click="cambiarAno(3)"> <button class="text-button"> Tercer Año </button>
                                        </li>
                                        <li @click="cambiarAno(4)"> <button class="text-button"> Cuarto Año </button>
                                        </li>
                                        <li @click="cambiarAno(5)"> <button class="text-button"> Quinto Año </button>
                                        </li>
                                        <li @click="cambiarAno(6)"> <button class="text-button"> Egresado </button>
                                        </li>
                                    </ul>


                                </div>

                                <div class="filter-selector">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        Tipo <span class="caret"></span>
                                    </button>

                                    <ul class="dropdown-menu">
                                        <li role="separator" class="divider"></li>
                                        <li value="0" disabled selected> <b> Tipo: </b> </li>
                                        <li @click="cambiarTipo('Externas')"> <button class="text-button"> Externa
                                            </button> </li>
                                        <li @click="cambiarTipo('Internas')"> <button class="text-button"> Interna
                                            </button> </li>
                                    </ul>
                                </div>

                            </div>


                            <!--  
                                <div class="filter-selector">
                                    <select class="custom-select"  v-model="ordenandoPor" @change="bindDataByFilters(0)">
                                        <option value="recientes" disabled selected>Ordenar por: </option>
                                        <option :value="`recientes`" >Reciente</option>
                                        <option :value="`menos_cupos`"> Menos cupos libres </option>
                                        <option :value="`mas_cupos`"> Mas cupos libres </option>
                                    </select>
                                </div>
                                  -->

                        </div>
                    </div>
                    <!---->
                    <div style="font-size: 1.1em; font-weight: normal">
                        <div class="d-flex flex-wrap my-3">
                            <div class="d-flex mr-4" style="gap: 5px">
                                <span> Mostrando: </span>
                                <span class="badge badge-pill badge-light"> Ingenieria Informatica </span>
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true" style="color: #000000">×</span>
                                </button>
                            </div>
                            <div class="d-flex" style="gap: 5px">
                                <span> Orden: </span>
                                <span class="badge badge-pill badge-light"> Menos cupos </span>
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true" style="color: #000000">×</span>
                                </button>
                            </div>

                        </div>
                    </div>
                    <!--  -->
                    <table v-if="arrayProyectos.length > 0" class="table table-bordered table-hover table-sm">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 10%;">Contraparte</th>
                                <th style="text-align: center; width: 10%;">Proyecto</th>
                                <th style="text-align: center;" id="disappear">Perfil estudiante</th>
                                <th style="width: 10%; text-align: center;">Cupos</th>
                                <th style="width: 10%; text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="proyecto in arrayProyectos" :key="proyecto.idProyecto">
                                <td v-text="proyecto.contraparte" id="name_p" data-toggle="modal"
                                    data-target="#modal-info" @click="abrirModal('info', proyecto)"></td>
                                <td v-text="proyecto.nombre" id="name_p" data-toggle="modal" data-target="#modal-info"
                                    @click="abrirModal('info', proyecto)"></td>
                                <td id="disappear" v-text="proyecto.perfil_estudiante" data-toggle="modal"
                                    data-target="#modal-info" @click="abrirModal('info', proyecto)"></td>
                                <td v-text="`${proyecto.cupos_act}${'/'}${proyecto.cupos}`" data-toggle="modal"
                                    data-target="#modal-info" @click="abrirModal('info', proyecto)"
                                    style="text-align: center;"></td>
                                <td>
                                    <div class="button-container" style="margin: 8px 0px 8px 4px;">
                                        <div v-if="!ya_aplico_hoy && !ya_aplico_proyecto && !timeout"
                                            style="display: flex; margin: 0px 10px;">
                                            <button type="button" data-toggle="modal" data-target="#modal-aplicar"
                                                @click="abrirModal('aplicar', proyecto)" class="btn btn-info btn-sm"
                                                style="width: 100%; border-radius: 5px;">
                                                <i class="icon-envelope"></i>
                                                <span class="btn-label">Aplicar</span>
                                            </button> &nbsp;
                                        </div>
                                        <div v-else style="display: flex; margin: 0px 10px;">
                                            <button type="button" class="btn btn-info btn-sm" disabled
                                                style="width: 100%; border-radius: 5px;">
                                                <i class="icon-envelope"></i>
                                                <span class="btn-label">Aplicar</span>
                                            </button> &nbsp;
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-else class="alert alert-danger" role="alert">
                        Parece que no se encontró proyectos para la busqueda de carrera, año, tipo y nombre seleccionado
                    </div>
                </div>
                <nav>
                    <ul class="pagination" style="float: right;">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)"
                                style="display: flex; justify-content: center; align-items: center; width: 32px; height: 35px;"><img
                                    :src="ruta + '/img/icons/chevron_left_black_24dp.svg'" alt="chevron-left"></a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page"
                            :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)"
                                style="display: flex; justify-content: center; align-items: center; width: 32px; height: 35px;"><img
                                    :src="ruta + '/img/icons/chevron_right_black_24dp.svg'" alt="chevron-left"></a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div style="margin: 20px 0px 0px 20px;" v-if="!loadTable">
                <div class="mb-0">
                    <p>Tu perfil es: <strong>{{ user_carrera.toUpperCase() }}</strong>, en
                        <strong>{{ user_perfil.toUpperCase() }}</strong>
                    </p>
                    <p>Si tu año o carrera no coincide, recuerda que puedes cambiarlo desde la pestaña

                        <strong>Perfil</strong>.
                    </p>
                </div>
                <div id="appear">
                    <p><b> Acciones: </b></p>
                    <span class="badge badge-info" style="border-radius: 5px; margin-left: 0.2em;">
                        <p id="estadorp" style="display: inline; font-weight: 300; font-size: 1.0rem;">
                            <i class="icon-envelope"></i>
                            <span>Aplicar a proyecto</span>
                        </p>
                    </span>
                </div>
            </div>

        </div>
        <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal aplicar a proyecto-->
        <div class="modal fade" tabindex="-1" id="modal-aplicar" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div v-if="loading == 1">
                <spinner></spinner>
            </div>
            <div v-if="loading == 0" class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Aplicar a proyecto</h4>
                        <button type="button" class="close" data-dismiss="modal" @click="cerrarModal()"
                            aria-label="Close">
                            <span aria-hidden="true" style="color: #ffffff">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5><b style="color: red">AVISO DE RESPONSABILIDAD</b></h5>
                        <h6>Al aplicar a este proyecto comprende que desde el momento en que usted ha sido aceptado/a
                            por el/la encargado/a
                            del proyecto, <b>usted se compromete a completar el proyecto en su finalidad y no abandonar
                                el proyecto</b>, debido a que el incumplimiento
                            y/o abandono del proyecto se considera una falta grave acorde al Art. 35 del Reglamento de
                            Servicio Social.</h6>
                        <p><b style="color:red">IMPORTANTE: </b>Este proceso se puede realizar una vez por día. Se le
                            notificará al encargado sobre su aplicación y luego
                            se le notificará a usted por correo si ha sido aceptado o no para pasar al siguiente proceso
                            de aplicación.</p>
                    </div>
                    <div class="modal-footer">
                        <h5>¿Está seguro/a que desea aplicar a este proyecto?</h5>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal"
                            @click="aplicarProyecto()">Confirmar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

        <!--Inicio del modal informacion de proyecto-->
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-info" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="modal_nombre">Aplicar a proyecto</h4>
                        <button type="button" class="close" data-dismiss="modal" @click="cerrarModalDos()"
                            aria-label="Close">
                            <span aria-hidden="true" style="color: #ffffff">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-sm" style=" margin-top: 10px">
                            <tbody>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Contraparte</th>
                                    <td v-text="modal_contraparte" style="padding-left: 12px;"></td>
                                </tr>

                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Perfil del estudiante</th>
                                    <td v-text="modal_perfil_estudiante" style="padding-left: 12px;"></td>
                                </tr>

                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Tipo de horas</th>
                                    <td v-text="modal_tipo_horas" style="padding-left: 12px;"></td>
                                </tr>

                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Cupos</th>
                                    <td v-text="`${modal_cupos_act}${'/'}${modal_cupos}`" style="padding-left: 12px;">
                                    </td>
                                </tr>

                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Horario</th>
                                    <td v-text="modal_horario" style="padding-left: 12px;"></td>
                                </tr>

                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Encargado</th>
                                    <td v-text="modal_encargado" style="padding-left: 12px;"></td>
                                </tr>

                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Correo encargado</th>
                                    <td v-text="modal_correo_encargado" style="padding-left: 12px;"></td>
                                </tr>

                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Fecha inicial</th>
                                    <td v-text="modal_fecha_in" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Fecha final</th>
                                    <td v-text="modal_fecha_fin" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th v-if="modal_desc.length > 2" class="col-md-4"
                                        style="background-color: #dedede;">Descripcion adicional</th>
                                    <td v-text="modal_desc" style="padding-left: 12px;"></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            @click="cerrarModalDos()">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        <footer class="app-footer" id="footer"
            style="display: flex; flex-direction: column; justify-content: center; font-size: 15px; padding: 10px 0px">
            <span><a target="_blank" href="http://www.uca.edu.sv/servicio-social/">Centro de Servicio Social | UCA</a>
                &copy; 2024</span>
            <span>Desarrollado por <a href="#"></a>Grupo de Horas Sociales</span>
        </footer>
    </main>
</template>

<script>
import { API_HOST } from '../constants/endpoint.js';
import { API_HOST_ASSETS } from '../constants/endpoint.js';
export default {
    data() {
        return {
            ruta: API_HOST_ASSETS,
            loading: 0,
            loadTable: false,
            user_id: 0,
            user_email: '',
            user_perfil: '',
            user_perfil_id: 0,
            user_carrera: '',
            user_carrera_id: 0,
            search_carrera_id: 0,
            search_perfil_id: 0,
            user_info: {},
            ya_aplico_hoy: false,
            ya_aplico_proyecto: false,
            descripcion: '',
            arrayProyectos: [],
            arrayProyectosAplicados: [''],
            modal: 0,
            modal2: 0,
            id_proyecto: 0,
            modal_encargado: '',
            modal_nombre: '',
            modal_desc: '',
            modal_tipo_horas: '',
            modal_cupos_act: 0,
            modal_cupos: 0,
            modal_horario: '',
            modal_fecha_in: '',
            modal_fecha_fin: '',
            modal_estado: '',
            arrayPXE: [''],
            arrayCarreras: [],
            arrayFactultad: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0
            },
            offset: 3,
            timeout: '',
            filtrandoPorTipo: "todas",
            filtrandoPorNombre: "",
            modal_perfil_estudiante: '',
            modal_correo_encargado: '',
            modal_contraparte: '',
        }
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
            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArray = []
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },
    },
    methods: {

        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.bindDataByFilters(page);
        },
        cambiarTipo(tipo) {
            let me = this;
            me.filtrandoPorTipo = tipo;
            me.bindDataByFilters(0);

        },
        cambiarAno(ano) {
            let me = this;
            me.user_perfil_id = ano;
            me.bindDataByFilters(0);

        },
        cambiarFiltro(filtro) {
            let me = this;
            me.user_carrera_id = filtro;
            me.bindDataByFilters(0);

        },
        aplicarProyecto() {
            let me = this
            me.loading = 1;
            axios.post(`${API_HOST}/proyecto/aplicar`, {
                'idProyecto': this.id_proyecto,
                'idUser': this.user_id,
                'estado': 0,
            })
                .then(function (response) {
                    $('#modal-aplicar').modal('hide')
                    me.loading = 2;
                    me.cerrarModal();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cerrarModal() {
            this.modal = 0;
            this.id_proyecto = 0
            this.bindDataByFilters(this.pagination.current_page);
        },
        cerrarModalDos() {
            this.modal2 = 0;
        },
        abrirModal(modelo, data = []) {

            switch (modelo) {
                case "aplicar":
                    {
                        this.modal = 1;
                        this.loading = 0;
                        this.id_proyecto = data.idProyecto
                        break;
                    }
                case "info":
                    {
                        this.modal2 = 1;
                        this.id_proyecto = data.idProyecto;
                        this.modal_encargado = data.encargado;
                        this.modal_correo_encargado = data.correo_encargado;
                        this.modal_nombre = data.nombre;
                        this.modal_desc = data.descripcion;
                        this.modal_perfil_estudiante = data.perfil_estudiante;
                        this.modal_tipo_horas = data.tipo_horas;
                        this.modal_cupos_act = data.cupos_act;
                        this.modal_cupos = data.cupos;
                        this.modal_horario = data.horario;
                        this.modal_fecha_in = data.fecha_inicio;
                        this.modal_fecha_fin = data.fecha_fin;
                        this.modal_contraparte = data.contraparte;

                        break;
                    }
                default:
                    break;
            }
        },
        logout() {
            var url = `${API_HOST}/logout`;
            axios.post(url).then(() => location.href = `${API_HOST}/`)
        },
        fechaLegible(fecha) {
            if (fecha == null || fecha == "") return null;
            var date = new Date(fecha);
            // plus 1 day 
            date.setDate(date.getDate() + 1);
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            return day + "/" + month + "/" + year;
        },
        async getFacultadesCarrerasAndPerfils() {
            let me = this

            const response = await axios.get(`${API_HOST}/get_user`)
            me.user_id = response.data.idUser;
            me.user_email = response.data.correo;
            me.user_carrera = response.data.carrera;
            me.user_carrera_id = response.data.idCarrera;
            me.user_perfil = response.data.perfil;
            me.user_perfil_id = response.data.idPerfil;

            me.search_carrera_id = response.data.idCarrera;
            me.search_pefil_id = response.data.idPerfil;

            console.log(response.data.timeout);
            if (me.fechaLegible(response.data.timeout))
                me.timeout = me.fechaLegible(response.data.timeout);
            else
                me.timeout = '';

            axios.get(`${API_HOST}/carrera`).then(function (response) {
                me.arrayCarrerasSin = response.data;
                me.arrayCarreras = me.arrayCarrerasSin.slice();
                // Mark to remove
                // me.arrayCarreras.push({idCarrera : -1, idFacultad : -1, nombre : "Todas las carreras"});
                // me.arrayCarreras.push({idCarrera : -2, idFacultad : -2, nombre : "Todas las carreras menos Psicología, Civil y Arquitectura"});
                me.arrayCarrerasCon = me.arrayCarreras.slice();
                me.arrayCarrerasSelector = me.arrayCarreras.slice();
            })
                .catch(function (error) {
                    console.log(error);
                });

            axios.get(`${API_HOST}/perfil`).then(function (response) {
                me.arrayPerfiles = response.data;
            })
                .catch(function (error) {
                    console.log(error);
                });

            axios.get(`${API_HOST}/facultad`).then(function (response) {
                me.arrayFactultad = response.data;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        bindDataByFilters(page) {

            let me = this;
            me.loadTable = true;

            axios.get(`${API_HOST}/estadoAplicacion`).then(function (response) {
                console.log(response)
                me.ya_aplico_hoy = response.data.ya_aplico_hoy;
                me.ya_aplico_proyecto = response.data.activeProject;
            })

            var url = `${API_HOST}/proyectos_carrera?nombre=${this.filtrandoPorNombre}&carrera=${this.user_carrera_id}&ano=${this.user_perfil_id}&tipo=${this.filtrandoPorTipo}&page=${page}`;
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
        },
    },
    async mounted() {
        await this.getFacultadesCarrerasAndPerfils();
        this.bindDataByFilters(1);
    }
}
</script>
<style>
body {
    font-size: 1em;
}

.main {
    font-family: "Abel", sans-serif;
    display: flex;
    flex-direction: column;
    min-height: 95vh;
}

.container-fluid {
    flex: 1;
}

.btn-primary {
    color: #fff;
    background-color: #ff7f00;
    border-color: #ff7f00;
}

.btn-primary:disabled {
    color: #fff;
    background-color: #f7b16b;
    border-color: #f7b16b;
}

.btn-primary:focus {
    color: #fff;
    background-color: #f7b16b;
    border-color: #f7b16b;
}

.btn-primary:hover {
    color: #fff;
    background-color: #f7b16b;
    border-color: #f7b16b;
}

.btn {
    border-radius: 6px;
}

.sidebar {
    background-color: #003C71;

}

@media screen and (min-width: 991px) {
    #logout {
        margin-right: 30px;
    }
}

@media screen and (max-width: 991px) {
    #logout {
        margin-right: 30px;
    }
}

#footer {
    margin-left: 0px;
}

.modal-primary .modal-header {
    background-color: #003C71;
    border: none;

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

.sidebar-fixed .sidebar {
    height: 100%;
}

#appear {
    display: none;
}

.text-button {
    background: none;
    border: none;
}

.text-button:hover {
    background: #c2c2c2;
}


.dropdown-menu {
    min-width: max-content;
    max-height: 250px;
    overflow: auto;
    margin-top: 1em;
    padding: 5px;
}
@media screen and (max-width: 991px) {

    #sidebarMenu {
        margin-top: 55px;
    }

    #logout {
        margin-right: 30px;
    }
    
    .main {
        overflow: scroll;
    }
    
}

@media screen and (max-width: 500px) {
    #disappear {
        display: none;
    }

    .btn-label {
        display: none;
    }

    #appear {
        display: block;
    }


}


.filter-group {
    align-items: center;
    display: flex;
    gap: 1em;
    flex-wrap: wrap;
    justify-content: space-between;
}

.search-bar {
    height: 2.5em;
    flex: 1;
}

.search-group {
    align-items: center;
    flex: 2;
    display: flex;
    gap: 1em;
    max-width: 60%;
    height: fit-content;
    flex-wrap: nowrap;
}

.filter-selector {
    max-width: 40%;
}

.search-input {
    flex: 2;
    height: 100%;
}
@media screen and (max-width: 991px) {

    .search-group {
        max-width: 100%;
    }
}
    
</style>