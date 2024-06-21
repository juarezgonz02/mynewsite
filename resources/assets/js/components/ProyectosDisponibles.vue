<template>
    <main class="main" style="background-color: white;">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Inicio</li>
            <li class="breadcrumb-item active">Proyectos Disponibles</li>
        </ol>

        <div class="container-fluid px-4">
            <!-- Ejemplo de tabla Listado -->
            <div v-if="loadTable == true" class="card" style="border: none;">
                <table-loader></table-loader>
            </div>
            <div v-else class="card" style="border: none;">
                <div v-if="ya_aplico_hoy || ya_aplico_proyecto || timeout != ''">
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
                <div class="card-body px-0 py-0">
                    <!-- Barra accion superior -->
                    <div class="form-group mb-0" style=" flex-wrap: wrap; flex-direction: column-reverse;">
                        <div class="filter-group justify-content-between" style="gap: 1em;">
                            <form class="search-group " @submit.prevent="bindDataByFilters(0)">
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

                                <div class="d-flex align-items-center">
                                    <div style="width: 70px">
                                        <span>
                                            Filtrar por:
                                        </span>
                                    </div>
                                </div>

                                <div class="filter-group">

                                    <div class="filter-selector">

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle font-lg py-1"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Carrera <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">

                                                <li role="separator" class="divider"></li>
                                                <li>
                                                    <b> Todos los proyectos: </b>
                                                </li>
                                                <li>
                                                    <!-- :value="JSON.stringify({ por: 'carrera', id: carrera.idCarrera })"
                                            :key="carrera.idCarrera"> -->
                                                    <button @click="cambiarFiltro(-1, 'Todos los proyectos')"
                                                        class="text-button"> Todos los proyectos </button>
                                                </li>
                                                <li role="separator" class="divider"></li>
                                                <li>
                                                    <b> Carrera: </b>
                                                </li>
                                                <li v-for="carrera in arrayCarreras">
                                                    <!-- :value="JSON.stringify({ por: 'carrera', id: carrera.idCarrera })"
                                            :key="carrera.idCarrera"> -->
                                                    <button @click="cambiarFiltro(carrera.idCarrera, carrera.nombre)"
                                                        class="text-button"> {{ carrera.nombre }} </button>
                                                </li>
                                                <!-- </optgroup> -->
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="filter-selector">
                                        <button type="button" class="btn btn-default dropdown-toggle font-lg py-1"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Año <span class="caret"></span>
                                        </button>

                                        <ul class="dropdown-menu">
                                            <li role="separator" class="divider"></li>
                                            <li value="0" disabled selected> <b> Año: </b> </li>
                                            <li @click="cambiarAno(1, 'Primer Año')"> <button class="text-button">
                                                    Primer
                                                    Año </button>
                                            </li>
                                            <li @click="cambiarAno(2, 'Segundo Año')"> <button class="text-button">
                                                    Segundo
                                                    Año </button>
                                            </li>
                                            <li @click="cambiarAno(3, 'Tercer Año')"> <button class="text-button">
                                                    Tercer
                                                    Año </button>
                                            </li>
                                            <li @click="cambiarAno(4, 'Cuarto Año')"> <button class="text-button">
                                                    Cuarto
                                                    Año </button>
                                            </li>
                                            <li @click="cambiarAno(5, 'Quinto Año')"> <button class="text-button">
                                                    Quinto
                                                    Año </button>
                                            </li>
                                            <li @click="cambiarAno(6, 'Egresado')"> <button class="text-button">
                                                    Egresado
                                                </button>
                                            </li>
                                        </ul>


                                    </div>

                                    <div class="filter-selector">
                                        <button type="button" class="btn btn-default dropdown-toggle font-lg py-1"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Tipo <span class="caret"></span>
                                        </button>

                                        <ul class="dropdown-menu">
                                            <li role="separator" class="divider"></li>
                                            <li value="0" disabled selected> <b> Tipo: </b> </li>
                                            <li @click="cambiarTipo('todas', 'Todas')"> <button class="text-button">
                                                    Todas
                                                </button> </li>
                                            <li @click="cambiarTipo('Externas', 'Externas')"> <button
                                                    class="text-button">
                                                    Externa
                                                </button> </li>
                                            <li @click="cambiarTipo('Internas', 'Internas')"> <button
                                                    class="text-button">
                                                    Interna
                                                </button> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!---->
                <div style="font-size: 1em; font-weight: normal">
                    <div class="d-flex flex-wrap my-3" style="gap: 0.5em 1em">
                        <div class="d-flex align-items-center">
                            <div style="width: 70px">
                                <span> Mostrando: </span>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap " style="gap: 0.5em 1em">
                            <div class="d-flex align-items-center" style="gap: 5px">
                                <span class="d-flex align-items-center badge badge-pill badge-light py-2 f-8em"
                                    style="line-height: 0; color: white; background-color: #003C71">
                                    <span>
                                        {{ (search_carrera).substring(0, 50) }}
                                    </span>
                                    <span v-if="!default_filter" class="px-1"
                                        @click="cambiarFiltro(user_carrera_id, user_carrera, true)" aria-hidden="true"
                                        style="cursor: pointer; color: #ffffff">×</span>
                                </span>
                            </div>
                            <div class="d-flex align-items-center" style="gap: 5px">
                                <span class="d-flex align-items-center badge badge-pill badge-light py-2 f-8em"
                                    style="line-height: 0; color: white; background-color: #003C71">
                                    <span>
                                        {{ search_perfil }}
                                    </span>
                                    <span v-if="!default_year" class="px-1"
                                        @click="cambiarAno(user_perfil_id, user_perfil, true)" aria-hidden="true"
                                        style="cursor: pointer; color: #ffffff">×</span>
                                </span>
                            </div>
                            <div v-if="!default_type" class="d-flex align-items-center" style="gap: 5px">
                                <span class="d-flex align-items-center badge badge-pill badge-light py-2 f-8em"
                                    style="line-height: 0; color: white; background-color: #003C71">
                                    <span>
                                        {{ type_label }}
                                    </span>
                                    <span class="px-1" @click="cambiarTipo('todas', 'Todas', true)" aria-hidden="true"
                                        style="cursor: pointer; color: #ffffff">×</span>
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
                <!--  -->
                <table v-if="arrayProyectos.length > 0" class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 25%;">Contraparte</th>
                            <th style="text-align: center; width: 25%;">Proyecto</th>
                            <th style="text-align: center; width: 20%;">Perfil estudiante</th>
                            <th style="text-align: center; width: 10%;" class="disappear">Tipo</th>
                            <th style="width: 10%; text-align: center;" class="disappear">Cupos</th>
                            <th style="width: 7%; text-align: center;" class="disappear">Aplicar</th>
                        </tr>
                    </thead>
                    <tbody style="width: 100%;">
                        <tr v-for="proyecto in arrayProyectos" :key="proyecto.idProyecto">
                            <td id="name_p" data-toggle="modal" data-target="#modal-info"
                                @click="abrirModal('info', proyecto)">
                                <div class="cell-minimizer">
                                    {{ proyecto.contraparte }}
                                </div>
                            </td>
                            <td v-text="proyecto.nombre" id="name_p" data-toggle="modal" data-target="#modal-info"
                                @click="abrirModal('info', proyecto)"></td>
                            <td v-text="proyecto.perfil_estudiante" data-toggle="modal" data-target="#modal-info"
                                @click="abrirModal('info', proyecto)"></td>
                            <td v-text="proyecto.tipo_horas" data-toggle="modal" data-target="#modal-info"
                                @click="abrirModal('info', proyecto)" class="disappear" style="text-align: center;"></td>
                            <td class="disappear" v-text="`${proyecto.cupos_act}${'/'}${proyecto.cupos}`"
                                data-toggle="modal" data-target="#modal-info" @click="abrirModal('info', proyecto)"
                                style="text-align: center;"></td>
                            <td class="disappear">
                                <div class="d-flex justify-content-center">
                                    <div v-if="!ya_aplico_hoy && !ya_aplico_proyecto && !timeout">
                                        <button type="button" data-toggle="modal" data-target="#modal-aplicar"
                                            @click="abrirModal('aplicar', proyecto)" class="mx-2 btn btn-primary"
                                            style="border-radius: 5px;">
                                            <i class="icon-envelope"></i>
                                            <span class="btn-label">Aplicar</span>
                                        </button>
                                    </div>
                                    <div v-else>
                                        <button type="button" class="mx-2 btn btn-primary" disabled
                                            style="border-radius: 5px;">
                                            <i class="icon-envelope"></i>
                                            <span class="btn-label">Aplicar</span>
                                        </button>
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
                        <p>Al aplicar a este proyecto comprende que desde el momento en que usted ha sido aceptado/a
                            por el/la encargado/a
                            del proyecto, <b>usted se compromete a completar el proyecto en su finalidad y no abandonar
                                el proyecto</b>, debido a que el incumplimiento
                            y/o abandono del proyecto se considera una falta grave acorde al Art. 35 del Reglamento de
                            Servicio Social.</p>
                        <p><b style="color:red">IMPORTANTE: </b>Este proceso se puede realizar una vez por día. Se le
                            notificará al encargado sobre su aplicación y luego
                            se le notificará a usted por correo si ha sido aceptado o no para pasar al siguiente proceso
                            de aplicación.</p>
                    </div>
                    <div class="modal-footer" style="display: flex;justify-content: space-between;">
                        <div>
                            <span style="font-size: 1.2em;">
                                ¿Está seguro/a que desea aplicar a este proyecto?<br />
                            </span>
                            <span style="margin-left: 10px;">
                                <b>{{ apply_name }}</b>
                            </span>
                        </div>

                        <div>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"
                                @click="aplicarProyecto()">Confirmar</button>
                        </div>
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
                        <h5 class="modal-title" v-text="modal_nombre"></h5>
                        <button type="button" class="close" data-dismiss="modal" @click="cerrarModalInfo()"
                            aria-label="Close">
                            <span aria-hidden="true" style="color: #ffffff">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="appear" v-if="!ya_aplico_hoy && !ya_aplico_proyecto && !timeout">
                            <button data-dismiss="modal" aria-label="Close" type="button" data-toggle="modal"
                                data-target="#modal-aplicar" @click="abrirModal('aplicar')"
                                class="btn btn-primary btn-sm" style="border-radius: 5px;">
                                <i class="icon-envelope"></i>
                                <span class="btn-label">Aplicar</span>
                            </button> &nbsp;
                        </div>
                        <div class="appear" v-else>
                            <button type="button" class="btn btn-primary btn-sm" disabled style="border-radius: 5px;">
                                <i class="icon-envelope"></i>
                                <span class="btn-label">Aplicar</span>
                            </button> &nbsp;
                        </div>
                        <table class="table table-bordered table-sm mt-3 appear-table">
                            <tbody>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Contraparte</th>
                                </tr>
                                <tr>
                                    <td v-text="modal_contraparte" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Perfil del estudiante</th>
                                </tr>
                                <tr>
                                    <td v-text="modal_perfil_estudiante" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Tipo de horas</th>
                                </tr>
                                <tr>
                                    <td v-text="modal_tipo_horas" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Cupos</th>
                                </tr>
                                <tr>
                                    <td v-text="`${modal_cupos_act}${'/'}${modal_cupos}`" style="padding-left: 12px;">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Horario</th>
                                </tr>
                                <tr>
                                    <td v-text="modal_horario" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Encargado</th>
                                </tr>
                                <tr>
                                    <td v-text="modal_encargado" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Correo encargado</th>
                                </tr>
                                <tr>
                                    <td v-text="modal_correo_encargado" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Fecha inicial</th>
                                </tr>
                                <tr>
                                    <td v-text="modal_fecha_in" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede;">Fecha final</th>
                                </tr>
                                <tr>
                                    <td v-text="modal_fecha_fin" style="padding-left: 12px;"></td>
                                </tr>

                            </tbody>
                        </table>

                        <table class="table table-bordered table-sm disappear">
                            <tbody s>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Contraparte</th>
                                    <td v-text="modal_contraparte" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Perfil del
                                        estudiante</th>
                                    <td v-text="modal_perfil_estudiante" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Tipo de horas
                                    </th>
                                    <td v-text="modal_tipo_horas" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Cupos</th>
                                    <td v-text="`${modal_cupos_act}${'/'}${modal_cupos}`" style="padding-left: 12px;">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Horario</th>
                                    <td v-text="modal_horario" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Encargado</th>
                                    <td v-text="modal_encargado" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Correo encargado
                                    </th>
                                    <td v-text="modal_correo_encargado" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Fecha inicial
                                    </th>
                                    <td v-text="modal_fecha_in" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Fecha final</th>
                                    <td v-text="modal_fecha_fin" style="padding-left: 12px;"></td>
                                </tr>

                            </tbody>
                        </table>

                        <!-- <table class="table table-bordered table-sm" style=" margin-top: 10px">
                            <tbody>
                                <tr>
                                    <th style="background-color: #dedede; width: 25%;">Contraparte</th>
                                    <td v-text="modal_contraparte" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede; width: 25%;">Perfil Estudiante</th>
                                    <td v-text="modal_perfil_estudiante" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede; width: 25%;">Tipo</th>
                                    <td v-text="modal_tipo_horas" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede; width: 25%;">Cupos</th>
                                    <td v-text="`${modal_cupos_act}${'/'}${modal_cupos}`" style="padding-left: 16px;">
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede; width: 25%;">Horario</th>
                                    <td v-text="modal_horario" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede; width: 25%;">Inicio</th>
                                    <td v-text="modal_fecha_in" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede; width: 25%;">Fin</th>
                                    <td v-text="modal_fecha_fin" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede; width: 25%;">Encargado</th>
                                    <td v-text="modal_encargado" style="padding-left: 16px;"></td>
                                </tr>
                            </tbody>
                        </table> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            @click="cerrarModalInfo()">Cerrar</button>
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
export default {
    data() {
        return {
            ruta: API_HOST,
            loading: 0,
            loadTable: true,
            user_id: 0,
            user_email: '',
            user_perfil: '',
            user_perfil_id: 0,
            user_carrera: '',
            user_carrera_id: 0,
            search_carrera_id: 0,
            search_perfil_id: 0,
            search_carrera: '',
            search_perfil: '',
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
            type_label: "Todas",
            year_label: '',
            default_filter: true,
            default_year: true,
            default_type: true,
            apply_id: '',
            apply_name: ""
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
            const me = this;
            me.pagination.current_page = page;
            me.bindDataByFilters(page);
        },
        cambiarTipo(tipo, label, defaultFilter = false) {
            const me = this;
            me.filtrandoPorTipo = tipo;
            me.type_label = label;
            me.default_type = defaultFilter
            me.bindDataByFilters(0);

        },
        cambiarAno(ano, label, defaultFilter = false) {
            const me = this;
            me.search_perfil_id = ano;
            me.search_perfil = label;
            me.default_year = defaultFilter
            me.bindDataByFilters(0);

        },
        cambiarFiltro(filtro, label, defaultFilter = false) {
            const me = this;
            me.search_carrera = label;
            me.search_carrera_id = filtro;
            me.default_filter = defaultFilter
            me.bindDataByFilters(0);

        },
        aplicarProyecto() {
            const me = this
            me.loading = 1;
            axios.post(`${API_HOST}/proyecto/aplicar`, {
                'idProyecto': this.apply_id,
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
        cerrarModalInfo() {
            this.modal2 = 0;
        },
        abrirModal(modelo, data = []) {

            switch (modelo) {
                case "aplicar":
                    {
                        this.modal = 1;
                        this.loading = 0;
                        this.cerrarModalInfo()
                        this.modal2 = 0;
                        this.apply_name = data.nombre || this.modal_nombre;
                        this.apply_id = data.idProyecto || this.id_proyecto;
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
            const me = this

            const response = await axios.get(`${API_HOST}/get_user`)
            me.user_id = response.data.idUser;
            me.user_email = response.data.correo;
            me.user_carrera = response.data.carrera;
            me.user_carrera_id = response.data.idCarrera;
            me.user_perfil = response.data.perfil;
            me.user_perfil_id = response.data.idPerfil;

            me.search_carrera_id = response.data.idCarrera;
            me.search_perfil_id = response.data.idPerfil;

            //console.log(response.data.timeout);
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

            const me = this;
            me.loadTable = true;

            axios.get(`${API_HOST}/estudiante/aplica/estado`).then(function (response) {
                //console.log(response)
                me.ya_aplico_hoy = response.data.ya_aplico_hoy;
                me.ya_aplico_proyecto = response.data.activeProject;
            })

            var url = `${API_HOST}/proyecto?nombre=${this.filtrandoPorNombre}&carrera=${this.search_carrera_id}&ano=${this.search_perfil_id}&tipo=${this.filtrandoPorTipo}&page=${page}`;
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
        this.search_carrera = this.user_carrera
        this.search_carrera_id = this.user_carrera_id
        this.search_perfil = this.user_perfil
        this.search_perfil_id = this.user_perfil_id
        this.bindDataByFilters(1);
    }
}
</script>
<style>
body {
    font-size: 1em;
}

.cell-minimizer {
    display: flex;
    align-items: center;
    min-height: 115px;
    max-height: 400px;
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

.badge-pill {
    border-radius: 6px;
    padding: 6px;
    height: 2em;
}

.btn-primary {
    color: #fff;
    background-color: #e66c20;
    border-color: #e66c20;
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

.appear {
    display: none;
}

.appear-table {
    display: none;
}

.text-button {
    background: none;
    border: none;
    width: 100%;
    text-align: left;
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
    .disappear {
        display: none;
    }

    .btn-label {
        display: inline;
    }

    .appear {
        display: block;
    }

    .appear-table {
        display: table;
    }


}


.filter-group {
    align-items: center;
    display: flex;
    gap: 0.5em 1em;
    flex-wrap: wrap;
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

.f-8em {
    font-size: 14px;
    font-weight: normal;

}

@media screen and (max-width: 991px) {

    .search-group {
        max-width: 100%;
        min-width: 50vw;
    }
}
</style>