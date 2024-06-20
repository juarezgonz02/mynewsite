<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb" style="padding-left: 30px;">
            <li class="breadcrumb-item">Inicio</li>
            <li class="breadcrumb-item active">Administración de Solicitudes</li>
        </ol>
        <div class="container-fluid px-4" style="background-color: white;">
            <!-- Ejemplo de tabla Listado -->
            <div v-if="loadTable == true" class="card" style="border: none;">
                <table-loader></table-loader>
            </div>
            <div v-else class="card" style="border: none;">
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
                            <!-- <div class="filter-selector" >
                                    
                                    <select class="custom-select" v-model="filtrandoPorCarrera" @change="bindDataByFilters(0)">
                                        <option :value="JSON.stringify({por: 'carrera', id: -1})" disabled selected>Filtrar por: </option>
                                        <optgroup label="Factultad"> 
                                            <option v-for="facultad in arrayFactultad" :value="JSON.stringify({por: 'facultad', id: facultad.idFacultad})" :key="facultad.idFacultad">{{facultad.nombre}}</option>
                                        </optgroup>
                                        <optgroup label="Carrera">
                                            <option v-for="carrera in arrayCarreras" :value="JSON.stringify({por: 'carrera', id: carrera.idCarrera})" :key="carrera.idCarrera">{{carrera.nombre}}</option>
                                        </optgroup>
                                    </select>
                                </div> -->


                            <div class="filter-selector">

                                <div class="btn-group">

                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        Filtrar <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <!-- v-model="filtrandoPorCarrera"> -->
                                        <!-- @change="bindDataByFilters(0)"> -->

                                        <li>
                                            <b> Filtrar por: </b>
                                        </li>

                                        <li @click="cambiarFiltro(JSON.stringify({ por: 'carrera', id: -1 }), 'Todas las Carreras', true)">
                                            <button class="text-button"> Todas las carreras </button>
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
                                                @click="cambiarFiltro(JSON.stringify({ por: 'facultad', id: facultad.idFacultad }), facultad.nombre)"
                                                class="text-button"> {{ facultad.nombre }} </button>
                                        </li>

                                        <li role="separator" class="divider"></li>
                                        <li>
                                            <b> Carrera: </b>
                                        </li>
                                        <li v-for="carrera in arrayCarreras">
                                            <!-- :value="JSON.stringify({ por: 'carrera', id: carrera.idCarrera })"
        :key="carrera.idCarrera"> -->
                                            <button
                                                @click="cambiarFiltro(JSON.stringify({ por: 'carrera', id: carrera.idCarrera }), carrera.nombre)"
                                                class="text-button"> {{ carrera.nombre }} </button>
                                        </li>
                                        <!-- </optgroup> -->
                                    </ul>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <!---->
                <div style="font-size: 1em; font-weight: normal">
                        <div class="d-flex flex-wrap mb-3">
                            <div class="d-flex flex-wrap mr-4" style="gap: 0.5em 1em">
                                <span> Mostrando solicitudes para: </span>
                                <span class="d-flex align-items-center badge badge-pill badge-light py-2 f-8em" style="line-height: 0; color: white; background-color: #003C71">
                                    <span>
                                         {{(filter_label).substring(0, 48)}} 
                                    </span>
                                    <span v-if="!default_filter" @click="cambiarFiltro(JSON.stringify({ por: 'carrera', id: -1 }), 'Todas las Carreras', true)" 
                                    aria-hidden="true" class="px-1" style="cursor: pointer; color: #ffffff">
                                    ×</span>
                                </span>
                            </div>
                            
                        </div>
                    </div>
                    <!--  -->
                <table v-if="arraySolicitudes.length > 0" class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 30%;">Alumno</th>
                            <th style="text-align: center;">Proyecto</th>
                            <th style="text-align: center; width: 15%;">Carrera</th>
                            <th class="disappear" style="text-align: center; width: 10%;">Cupos</th>
                            <th style="text-align: center; width: 10%;">Estudiantes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="fila" v-for="solicitud in arraySolicitudes"
                            :key="solicitud.idProyecto + '-' + solicitud.idUser">
                            <td id="pos" v-text="`${solicitud.u_nombre} ${solicitud.u_apellido}`" data-toggle="modal"
                                data-target="#userDetailModal" @click="abrirModal('info_user', solicitud)"></td>
                            <td v-text="solicitud.nombre" data-toggle="modal" data-target="#projectDetailModal"
                                @click="abrirModal('info_proyecto', solicitud)"></td>
                            <td v-text="solicitud.carrera"></td>
                            <td class="disappear" v-text="`${solicitud.cupos_act}${'/'}${solicitud.cupos}`"
                                style="text-align: center;"></td>
                            <td id="icons-pos">
                                <div class="button-container">
                                    <button type="button" @click="abrirModal('estudiantes', solicitud)"
                                        data-toggle="modal" data-target="#membersModal" class="btn btn-info btn-sm"
                                        id="membersbutton" style="margin-bottom: 8px; width: 100%;">
                                        <i class="icon-people"></i>
                                        <span class="btn-label">Miembros</span>
                                        <span id="badge" v-if="solicitud.notificaciones == 1"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="alert alert-danger" role="alert">
                    No hay solicitudes en este momento
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
                                    :src="ruta + '/img/icons/chevron_right_black_24dp.svg'" alt="chevron-right"></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->

        
        <!--Inicio de modal de estudiantes por proyecto-->
        <div class="modal fade" tabindex="-1" role="dialog" id="membersModal" aria-hidden="true">
            <div v-if="loading == 1">
                <spinner></spinner>
            </div>
            <div v-if="loading == 0" class="modal-dialog modal-primary modal-lg modal-student" role="document"
                style="max-width: 70vw;">
                <div class="modal-content modal-student" style="">
                    <div class="modal-header">
                        <h5 class="modal-title">Estudiantes</h5>
                        <button type="button" class="close" data-dismiss="modal" @click="cerrarModal()"
                            aria-label="Close">
                            <span aria-hidden="true" style="color: #ffffff">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="header-estudiante">
                            <div>
                                <label>Ingrese el carnet del estudiante que desea agregar al proyecto</label>
                            </div>
                            <div>
                                <input type="text" v-model="carnet" style="width: 38vw;"
                                    placeholder="Carnet del estudiante">
                                <button type="button" id="student-button" style="margin-left: 5px; width: 75px;"
                                    @click="buscarEstudiante()" class="btn btn-primary">Buscar</button>
                            </div>
                            <div>
                                <div v-if="flagError" class="mt-2 mb-1">
                                    <p class="text-danger" v-text="errorEstudianteMsg"></p>
                                </div>
                                <div v-else>
                                    <div v-if="nombre_completo == ''">
                                        <h2 style="visibility:hidden; margin-bottom:0">Nada</h2>
                                    </div>
                                    <div v-else>
                                        <input type="text" disabled v-model="nombre_completo"
                                            style="margin-bottom:0; width: 38vw;">
                                        <button id="student-button" class="btn btn-primary search-student" type="button"
                                            style="margin: 10px 5px 10px 5px; width: 75px;"
                                            @click="aplicarPorAdmin()">Agregar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p>Cupos disponibles: {{ modal_cupos_act }}</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>Carnet</th>
                                        <th>Año de carrera</th>
                                        <th>Carrera</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="estudiante in arrayEstudiantes" :key="estudiante.idEstudiante">
                                        <td v-text="estudiante.nombres"></td>
                                        <td v-text="estudiante.apellidos"></td>
                                        <td v-text="estudiante.correo"></td>
                                        <td v-text="estudiante.perfil"></td>
                                        <td v-text="estudiante.carrera"></td>
                                        <td>
                                            <div v-if="estudiante.estado == 0"
                                                id="estado-btns-container">
                                                <button type="button" data-toggle="modal" data-target="#confirmModal"
                                                    @click="abrirModal('confirmacion', estudiante, true)"
                                                    class="btn btn-success btn-sm">
                                                    Aceptar
                                                </button> &nbsp;
                                                <button type="button" data-toggle="modal" data-target="#confirmModal"
                                                    @click="abrirModal('confirmacion', estudiante, false)"
                                                    class="btn btn-danger btn-sm">
                                                    Rechazar
                                                </button> &nbsp;
                                            </div>
                                            <div v-else-if="estudiante.estado == 1">
                                                <span class="badge badge-success" style="border-radius: 5px;">
                                                    <p id="estadoap" style="display: inline;">ACEPTADO</p>
                                                </span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger" style="border-radius: 5px;">
                                                    <p id="estadorp" style="display: inline;">RECHAZADO</p>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            @click="cerrarModal()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin del modal-->
        <!--Inicio del modal de confirmacion para aceptar o rechazar estudiantes-->
        <div class="modal fade" :class="{ 'mostrar': modal4 }" tabindex="-1" role="dialog" id="confirmModal"
            aria-hidden="true">
            <div v-if="loading == 1">
                <spinner></spinner>
            </div>
            <div v-if="loading == 0" class="modal-dialog modal-primary" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <div v-if="flagEstudiante">
                            <h5 class="modal-title">Aceptar estudiante</h5>
                        </div>
                        <div v-else>
                            <h5 class="modal-title">Rechazar estudiante</h5>
                        </div>
                        <button id="cerrarModalARE1" type="button" class="close" data-dismiss="modal"
                            @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true" style="color: #ffffff">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 v-text="nombre_estudiante_msg"></h5>
                    </div>
                    <div class="modal-footer">

                        <button id="cerrarModalARE2" type="button" class="btn btn-secondary" data-dismiss="modal"
                            @click="cerrarModal()">Cerrar</button>
                        <button id="aceptarRechazarEst" type="button" class="btn btn-primary" data-dismiss="modal"
                            @click="aceptarRechazarEstudiante()">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin del modal-->
        <!--Inicio del modal informacion de proyecto-->
        <div class="modal fade" tabindex="-1" role="dialog" id="projectDetailModal" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-text="modal_nombre"></h5>
                        <button type="button" class="close" data-dismiss="modal" @click="cerrarModal()"
                            aria-label="Close">
                            <span aria-hidden="true" style="color: #ffffff">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- <table class="table table-bordered table-sm" style=" margin-top: 10px">
                            <tbody>
                                <tr>
                                    <th style="background-color: #dedede;">Descripción</th>
                                    <td v-text="modal_desc" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede;">Tipo</th>
                                    <td v-text="modal_tipo_horas" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede;">Cupos</th>
                                    <td v-text="`${modal_cupos_act}${'/'}${modal_cupos}`" style="padding-left: 16px;">
                                    </td>
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
                        </table> -->
                        <table class="table table-bordered table-sm appear-table" style="margin-top: 10px">
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
 
                        <table class="table table-bordered table-sm disappear" style="margin-top: 10px">
                            <tbody>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Contraparte</th>
                                    <td v-text="modal_contraparte" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Perfil del estudiante</th>
                                    <td v-text="modal_perfil_estudiante" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Tipo de horas</th>
                                    <td v-text="modal_tipo_horas" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Cupos</th>
                                    <td v-text="`${modal_cupos_act}${'/'}${modal_cupos}`" style="padding-left: 12px;">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Horario</th>
                                    <td v-text="modal_horario" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Encargado</th>
                                    <td v-text="modal_encargado" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Correo encargado</th>
                                    <td v-text="modal_correo_encargado" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Fecha inicial</th>
                                    <td v-text="modal_fecha_in" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Fecha final</th>
                                    <td v-text="modal_fecha_fin" style="padding-left: 12px;"></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer" style="border-top: none;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            @click="cerrarModal()">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

        <!--Inicio del modal informacion de proyecto-->
        <div class="modal fade" tabindex="-1" role="dialog" id="userDetailModal" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-text="modal_user_carnet"></h5>
                        <button type="button" class="close" data-dismiss="modal" @click="cerrarModal()"
                            aria-label="Close">
                            <span aria-hidden="true" style="color: #ffffff">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-sm disappear">
                            <tbody>
                                <tr>
                                    <th style="background-color: #dedede;">Correo</th>
                                    <td v-text="modal_user_carnet" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede;">Nombre</th>
                                    <td v-text="modal_user_name" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede;">Carrera</th>
                                    <td v-text="modal_user_carrera" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede;">Año</th>
                                    <td v-text="modal_user_año" style="padding-left: 16px;"></td>
                                </tr>

                            </tbody>
                        </table>
                            
                        <table class="table table-bordered table-sm appear-table">
                            <tbody>
                                <tr>
                                    <th style="background-color: #dedede;">Correo</th>
                                </tr>
                                <tr>
                                    <td v-text="modal_user_carnet" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede;">Nombre</th>
                                </tr>
                                <tr>
                                    <td v-text="modal_user_name" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #dedede;">Carrera</th>
                                </tr>
                                <tr>
                                    <td v-text="modal_user_carrera" style="padding-left: 16px;"></td>
                                </tr>
                                <tr>                                    
                                    <th style="background-color: #dedede;">Año</th>
                                </tr>
                                <tr>
                                    <td v-text="modal_user_año" style="padding-left: 16px;"></td>
                                </tr>


                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer" style="border-top: none;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            @click="cerrarModal()">Cerrar</button>
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
<script src="./index.js"></script>
<style lang="scss" src="./index.css"></style>
