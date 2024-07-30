<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb" style="padding-left: 30px;">
            <li class="breadcrumb-item">Inicio</li>
            <li class="breadcrumb-item active">Administración de Proyectos</li>
        </ol>
        <div class="container-fluid px-4" style="background-color: white;">
            <!-- Ejemplo de tabla Listado -->
            <div v-if="loadTable == true" class="card" style="border: none;">
                <table-loader></table-loader>
            </div>
            <div v-else class="card" style="border: none;">
                <div class="card-body px-0">

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
                                        Buscar
                                    </button>
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
                                            <li>
                                                <b> Filtrar por: </b>
                                            </li>

                                            <li
                                                @click="cambiarFiltro(JSON.stringify({ por: 'carrera', id: -1 }), ' Todas las carreras', true)">
                                                <button class="text-button"> Todas las carreras </button>
                                            </li>


                                            <li role="separator" class="divider"></li>
                                            <li>
                                                <b> Facultad: </b>
                                            </li>
                                            <li v-for="facultad in arrayFactultad">
                                                <button
                                                    @click="cambiarFiltro(JSON.stringify({ por: 'facultad', id: facultad.idFacultad }), facultad.nombre)"
                                                    class="text-button"> {{ facultad.nombre }} </button>
                                            </li>

                                            <li role="separator" class="divider"></li>
                                            <li>
                                                <b> Carrera: </b>
                                            </li>
                                            <li v-for="carrera in arrayCarreras">
                                                <button
                                                    @click="cambiarFiltro(JSON.stringify({ por: 'carrera', id: carrera.idCarrera }), carrera.nombre)"
                                                    class="text-button"> {{ carrera.nombre }} </button>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                                <div class="filter-selector">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        Ordenar <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <!-- v-model="filtrandoPorCarrera"> -->
                                        <!-- <select class="custom-select" v-model="ordenandoPor" @change="bindDataByFilters(0)"> -->
                                        <li value="" disabled selected> <b> Ordenar por: </b> </li>
                                        <li value="recientes"> <button
                                                @click="cambiarOrden('recientes', 'Recientes', true)"
                                                class="text-button"> Reciente </button> </li>
                                        <li value="antiguos"> <button @click="cambiarOrden('antiguos', 'Antiguos')"
                                                class="text-button"> Antiguos </button> </li>
                                        <li value="menos_cupos"> <button
                                                @click="cambiarOrden('menos_cupos', 'Menos cupos libres')"
                                                class="text-button"> Menos cupos libres </button> </li>
                                        <li value="mas_cupos"> <button @click="cambiarOrden('mas_cupos', 'Más cupos')"
                                                class="text-button"> Más cupos libres </button> </li>
                                        <li value="n_solicitudes"> <button
                                                @click="cambiarOrden('n_solicitudes', 'Más solicitudes')"
                                                class="text-button"> Número solictudes </button> </li>
                                    </ul>
                                </div>
                                <div style="flex-direction: column-reverse">
                                    <button type="button" @click="abrirModal('insertar', null)" data-toggle="modal"
                                        data-target="#editModal" class="btn btn-primary"><i class="icon-plus"></i>
                                        Nuevo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!---->
                    <div style="font-size: 1em; font-weight: normal">
                        <div class="d-flex flex-wrap my-3" style="gap: 1em">
                            <div class="d-flex flex-wrap" style="gap: 0.5em 1em">
                                <div style="width: 75px">
                                    <span> Mostrando: </span>
                                </div>

                                <span class="d-flex align-items-center badge badge-pill badge-light py-2 f-8em"
                                    style="line-height: 0; color: white; background-color: #003C71">
                                    <span>
                                        {{ (filter_label).substring(0, 48) }}
                                    </span>
                                    <span v-if="!default_filter" class="px-1"
                                        @click="cambiarFiltro(JSON.stringify({ por: 'carrera', id: -1 }), ' Todas las carreras', true)"
                                        aria-hidden="true" style="cursor: pointer; color: #ffffff">
                                        ×
                                    </span>
                                </span>
                            </div>
                            <div class="d-flex flex-wrap" style="gap: 0.5em 1em">
                                <div style="width: 75px">
                                    <span> Ordenar por: </span>
                                </div>
                                <span class="d-flex align-items-center badge badge-pill badge-light py-2 f-8em"
                                    style="line-height: 0; color: white; background-color: #003C71">
                                    <span>
                                        {{ order_label }}
                                    </span>
                                    <span v-if="!default_order" class="px-1"
                                        @click="cambiarOrden('recientes', 'Reciente', true)" aria-hidden="true"
                                        style="cursor: pointer; color: #ffffff">×</span>

                                </span>
                            </div>

                        </div>
                    </div>
                    <!--  -->
                    <table v-if="arrayProyectos.length > 0" class="table table-bordered table-hover table-sm">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 15%;">Contraparte</th>
                                <th style="text-align: center; width: 25%;">Proyecto</th>
                                <th style="text-align: center; width: 20%;" class="disappear">Perfil estudiante</th>
                                <th style="text-align: center; width: 10%;">Estado del proyecto</th>
                                <th style="width: 10%; text-align: center;" class="disappear">Cupos</th>
                                <th style="width: 10%; text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- #f09418 -->
                            <tr id="fila" v-for="proyecto in arrayProyectos" :key="proyecto.idProyecto">
                                <td id="pos" v-text="proyecto.contraparte" data-toggle="modal"
                                    data-target="#projectDetailModal" @click="abrirModal('info', proyecto)"></td>
                                <td id="pos" v-text="proyecto.nombre" data-toggle="modal"
                                    data-target="#projectDetailModal" @click="abrirModal('info', proyecto)"></td>
                                <td class="disappear" v-text="proyecto.perfil_estudiante" data-toggle="modal"
                                    data-target="#projectDetailModal" @click="abrirModal('info', proyecto)"></td>
                                <td v-text="proyecto.estado_proyecto" data-toggle="modal"
                                    data-target="#projectDetailModal" @click="abrirModal('info', proyecto)"
                                    style="text-align: center;"></td>
                                <td class="disappear" v-text="`${proyecto.cupos_act}${'/'}${proyecto.cupos}`"
                                    data-toggle="modal" data-target="#projectDetailModal"
                                    @click="abrirModal('info', proyecto)" style="text-align: center;"></td>
                                <td id="icons-pos">
                                    <div class="button-container">
                                        <button type="button" @click="abrirModal('editar', proyecto)"
                                            data-toggle="modal" data-target="#editModal" class="btn btn-warning btn-sm"
                                            style="width: 100%;">
                                            <i class="icon-pencil"></i>
                                            <span class="btn-label">Editar</span>
                                        </button>
                                    </div>
                                    <div class="button-container">
                                        <button type="button" @click="abrirModal('estado', proyecto)"
                                            data-toggle="modal" data-target="#statusModal" class="btn btn-danger btn-sm"
                                            style="margin: 8px 0; width: 100%;">
                                            <i class="icon-lock"></i>
                                            <span class="btn-label">Cambiar estado</span>
                                        </button>
                                    </div>
                                    <div class="button-container">
                                        <button type="button" @click="abrirModal('estudiantes', proyecto)"
                                            data-toggle="modal" data-target="#membersModal" class="btn btn-info btn-sm"
                                            id="membersbutton" style="margin-bottom: 8px; width: 100%;">
                                            <i class="icon-people"></i>
                                            <span class="btn-label">Miembros</span>
                                            <span id="badge" v-if="proyecto.notificaciones == 1"></span>
                                        </button>
                                    </div>
                                    <div class="button-container">
                                        <button type="button" @click="abrirModal('reunion', proyecto)"
                                            data-toggle="modal" data-target="#meetingModal" class="btn btn-info btn-sm"
                                            id="meetingbutton" style="width: 100%;">
                                            <i class="icon-calendar"></i>
                                            <span class="btn-label">Reunión</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <div v-else>
                        <div v-if="filtrandoPorNombre == '' && filtrandoPorCarrera == JSON.stringify({ 'por': 'carrera', 'id': -1 })"
                            class="alert alert-danger" role="alert">
                            No hay proyectos actualmente
                        </div>
                        <div v-else class="alert alert-danger" role="alert">
                            No hay resultado para tu busqueda
                        </div>
                    </div>

                    <nav>
                        <ul class="pagination" style="float: right;">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#"
                                    @click.prevent="cambiarPagina(pagination.current_page - 1)"
                                    style="display: flex; justify-content: center; align-items: center; width: 32px; height: 35px;"><img
                                        :src="ruta + '/img/icons/chevron_left_black_24dp.svg'" alt="chevron-left"></a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page"
                                :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#"
                                    @click.prevent="cambiarPagina(pagination.current_page + 1)"
                                    style="display: flex; justify-content: center; align-items: center; width: 32px; height: 35px;"><img
                                        :src="ruta + '/img/icons/chevron_right_black_24dp.svg'" alt="chevron-right"></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal editar proyecto-->
        <div class="modal fade" tabindex="-1" role="dialog" id="editModal" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div v-if="add_edit_flag == 1">
                            <h5 class="modal-title">Insertar nuevo proyecto</h5>
                        </div>
                        <div v-else>
                            <h5 class="modal-title">Editar proyecto</h5>
                        </div>

                        <button type="button" class="close" data-dismiss="modal" @click="cerrarModal()"
                            aria-label="Close">
                            <span aria-hidden="true" style="color: #ffffff">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Contraparte</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="modal_contraparte" class="form-control"
                                        placeholder="Contraparte">
                                    <p :class="{ show: errorProyecto[8] == 1, hide: errorProyecto[8] != 1 }"
                                        class="error">La contraparte no puede ir vacía</p>
                                </div>
                            </div>
                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre del
                                    Proyecto/Actividad</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="modal_nombre" class="form-control inputs"
                                        placeholder="Nombre del proyecto">
                                    <p :class="{ show: errorProyecto[0] == 1, hide: errorProyecto[0] != 1 }"
                                        class="error">El nombre no puede ir vacío</p>
                                </div>
                            </div>
                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Encargado</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="modal_encargado" class="form-control"
                                        placeholder="Nombre del encargado">
                                    <p :class="{ show: errorProyecto[1] == 1, hide: errorProyecto[1] != 1 }"
                                        class="error">El nombre del encargado no puede ir vacío</p>
                                </div>
                            </div>
                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Correo del encargado</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="modal_correo" class="form-control"
                                        placeholder="example@example.com">
                                    <div v-if="errorProyecto[6] != 2">
                                        <p :class="{ show: errorProyecto[6] == 1, hide: errorProyecto[6] != 1 }"
                                            class="error">El correo del encargado no puede ir vacío</p>
                                    </div>
                                    <div v-else>
                                        <p class="error">El correo no es válido</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Perfil del
                                    estudiante</label>
                                <div class="col-md-9">
                                    <textarea type="text" v-model="modal_perfil_estudiante" class="form-control"
                                        placeholder="Perfil del estudiante"></textarea>
                                    <span>{{ modal_perfil_estudiante.length }}/2000</span>
                                    <div v-if="errorProyecto[5] != 2">
                                        <p :class="{ show: errorProyecto[5] == 1, hide: errorProyecto[5] != 1 }"
                                            class="error">El perfil del estudiante que puede aplicar al proyecto no
                                            puede ir vacío</p>
                                    </div>
                                    <div v-else>
                                        <p class="error">El perfil del estudiante no puede superar el número máximo de
                                            caracteres</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Cupos</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="modal_cupos" class="form-control" placeholder="Cupos">
                                    <div v-if="errorProyecto[2] != 2">
                                        <p :class="{ show: errorProyecto[2] == 1, hide: errorProyecto[2] != 1 }"
                                            class="error">Los cupos no pueden ir vacios</p>
                                    </div>
                                    <div v-else>
                                        <p class="error">El cupo no es valido</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo de horas</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="modal_tipo_horas">
                                        <option value="Internas">Internas</option>
                                        <option value="Externas">Externas</option>
                                    </select>
                                    <p :class="{ show: errorProyecto[3] == 1, hide: errorProyecto[3] != 1 }"
                                        class="error">Debe seleccionar un tipo de horas</p>
                                </div>
                            </div>
                            <div class="form-group row div-form mb-3">
                                <label class="col-md-3 form-control-label" for="text-input">Descripción
                                    Adicional</label>
                                <div class="col-md-9">
                                    <textarea type="text" v-model="modal_desc" class="form-control"
                                        placeholder="Descripción"></textarea>
                                    <span>{{ modal_desc.length }}/2000</span>
                                </div>
                            </div>


                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Horario</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="modal_horario" class="form-control"
                                        placeholder="Horario">
                                    <p :class="{ show: errorProyecto[7] == 1, hide: errorProyecto[7] != 1 }"
                                        class="error">El horario no puede ir vacío</p>
                                </div>
                            </div>

                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Fecha de inicio</label>
                                <div class="col-md-9">
                                    <input type="date" value="2017-06-01" v-model="modal_fecha_in">
                                    <div v-if="errorProyecto[9] == 1 || errorProyecto[9] == 2">
                                        <p class="show error">{{ errorDateMsg }}</p>
                                    </div>
                                    <div v-else>
                                        <p class="hide error">.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Fecha de
                                    finalización</label>
                                <div class="col-md-9">
                                    <input type="date" value="2021-01-01" v-model="modal_fecha_fin">
                                    <div v-if="errorProyecto[10] == 1 || errorProyecto[10] == 2">
                                        <p class="show error">{{ errorDateMsg }}</p>
                                    </div>
                                    <div v-else>
                                        <p class="hide error">.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Carreras</label>

                                <div class="form-check">
                                    <input class="form-check-input " type="radio" id="checkboxTodasCarreras2"
                                        v-model="flagTodasLasCarreras" value="2">
                                    <label class="form-check-label font-lg" for="checkboxTodasCarreras2">Seleccionar
                                        carreras</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input " type="radio" id="checkboxTodasCarreras1"
                                        name="checkboxTodasCarreras" v-model="flagTodasLasCarreras" value="1">
                                    <label class="form-check-label font-lg" for="checkboxTodasCarreras1">Todas las
                                        carreras excepto Ing. Civil y Psicologia</label>
                                </div>

                            </div>


                            <div class="form-group row div-form">

                                <label class="col-md-3 form-control-label" for="text-input"></label>
                                <div class="col-md-9" v-show='flagTodasLasCarreras == "1"'>
                                    <th style="padding: 0.3rem"> Rango de año para todas las carreras</th>
                                    <table class="table-sm table-borderless">
                                        <thead>
                                            <tr>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select v-model="create_lim_inf" class="form-control custom-select">
                                                        <option v-for="perfil in arrayPerfiles" :value="perfil.idPerfil"
                                                            :key="perfil.idPerfil">{{ perfil.perfil }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select v-model="create_lim_sup" class="form-control custom-select">
                                                        <option v-for="perfil in arrayPerfiles" :value="perfil.idPerfil"
                                                            :key="perfil.idPerfil">{{ perfil.perfil }}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            <div class="form-group row div-form">

                                <div class="col-md-11 mx-auto" v-show='flagTodasLasCarreras == "2"'>

                                    <div class="form-button-container">
                                        <div>
                                            <button type="button" @click="agregarTodasLasCarreras()"
                                                class="btn btn-outline-info mb-2 mr-4"><i class="icon-plus"></i>
                                                Seleccionar
                                                todas las carreras</button>
                                            <button type="button" @click="eliminarTodasLasCarreras()"
                                                class="btn btn-outline-danger mb-2"><i class="icon-trash"></i> Limpiar
                                                selección</button>
                                        </div>
                                    </div>
                                    <br>
                                    <th style="padding: 0.3rem">Añadir carreras con rango de año:</th>
                                    <table class="table-sm table-borderless mb-4">
                                        <thead>
                                            <tr>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select v-model="create_lim_inf" class="form-control custom-select">
                                                        <option v-for="perfil in arrayPerfiles" :value="perfil.idPerfil"
                                                            :key="perfil.idPerfil">{{ perfil.perfil }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select v-model="create_lim_sup" class="form-control custom-select">
                                                        <option v-for="perfil in arrayPerfiles" :value="perfil.idPerfil"
                                                            :key="perfil.idPerfil">{{ perfil.perfil }}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="table-sm table-borderless appear-table">
                                        <thead>
                                            <td>
                                            <th>Carrera/Rango</th>
                                            </td>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(acp, index) in arrayCarreraPerfil" :key="acp.id">
                                                <td>
                                                    <div class="d-flex flex-column mb-4" style="gap: 0.5rem">
                                                        <div>
                                                            <select class="form-control custom-select" v-model="acp[0]">
                                                                <option v-for="carrera in arrayCarrerasSelector"
                                                                    :value="carrera.idCarrera" :key="carrera.idCarrera">
                                                                    {{ carrera.nombre }}</option>
                                                            </select>
                                                        </div>

                                                        <div class="d-flex" style="gap: 1rem">
                                                            <select class="form-control custom-select" v-model="acp[1]">
                                                                <option v-for="perfil in arrayPerfiles"
                                                                    :value="perfil.idPerfil" :key="perfil.idPerfil">{{
                perfil.perfil }}</option>
                                                            </select>

                                                            <select class="form-control custom-select" v-model="acp[2]">
                                                                <option v-for="perfil in arrayPerfiles"
                                                                    :value="perfil.idPerfil" :key="perfil.idPerfil">{{
                perfil.perfil }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="mb-4">
                                                        <button type="button" class="close" @click="eliminarACP(acp)"
                                                            style="float: none" aria-label="Close">
                                                            <span aria-hidden="true" style="color: #000000">×</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <button id="agregarCP" :disabled="disabledBotonAgregarCarrera()"
                                                        type="button" @click="agregarACP()"
                                                        class="btn btn-primary mb-2"><i class="icon-plus"></i>
                                                        Agregar</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="table-sm table-borderless disappear">
                                        <thead>
                                            <tr>
                                                <th>Carrera</th>
                                                <th>Rango</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(acp, index) in arrayCarreraPerfil" :key="acp.id">
                                                <td>
                                                    <select class="form-control custom-select" v-model="acp[0]">
                                                        <option v-for="carrera in arrayCarrerasSelector"
                                                            :value="carrera.idCarrera" :key="carrera.idCarrera">
                                                            {{ carrera.nombre }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control custom-select" v-model="acp[1]">
                                                        <option v-for="perfil in arrayPerfiles" :value="perfil.idPerfil"
                                                            :key="perfil.idPerfil">{{ perfil.perfil }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control custom-select" v-model="acp[2]">
                                                        <option v-for="perfil in arrayPerfiles" :value="perfil.idPerfil"
                                                            :key="perfil.idPerfil">{{ perfil.perfil }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="button" class="close" @click="eliminarACP(acp)"
                                                        aria-label="Close">
                                                        <span aria-hidden="true" style="color: #000000">×</span>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button id="agregarCP" :disabled="disabledBotonAgregarCarrera()"
                                                        type="button" @click="agregarACP()"
                                                        class="btn btn-primary mb-2"><i class="icon-plus"></i>
                                                        Agregar</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div v-if="flagError">
                                        <P class="show error">{{ errorPerfilMsg }}</P>
                                    </div>
                                    <div v-else>
                                        <p class="hide error">.</p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-success"
                            v-bind:data-dismiss="flagErrorProyecto ? '' : 'modal'"
                            @click="actualizarInsertarProyecto()">Guardar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        <!--Inicio del modal estado del proyecto-->
        <div class="modal fade" tabindex="-1" role="dialog" id="statusModal" aria-hidden="true">
            <div v-if="loading == 1">
                <spinner></spinner>
            </div>
            <div v-if="loading == 0" class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cambiar estado del proyecto</h5>
                        <button type="button" data-dismiss="modal" class="close" @click="cerrarModal()"
                            aria-label="Close">
                            <span aria-hidden="true" style="color: #ffffff">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <h5>Por favor escriba <b>{{ modal_nombre }}</b> para confirmar el cambio de estado de este
                            proyecto</h5>
                        <div class="col-md-9 -alt">
                            <input type="text" v-model="modal_confirmar" class="form-control">
                            <p :class="{ show: errorEstado == 1, hide: errorEstado != 1 }" class="error">El texto
                                ingresado no coincide con el solicitado</p>
                        </div>

                        <div class="state-btn-container" disabled>
                            <button type="button" class="btn btn-success"
                                @click="showChangeStatusConfirm('finalizar')"><i class="icon-check"></i> Finalizar
                                proyecto</button>
                            <button type="button" class="btn btn-danger" @click="showChangeStatusConfirm('cancelar')"><i
                                    class="icon-close"></i> Cancelar
                                proyecto</button>
                            <button type="button" class="btn btn-secondary"
                                @click="showChangeStatusConfirm('eliminar')"><i class="icon-trash"></i> Eliminar
                                Proyecto</button>
                        </div>
                    </div>
                    <!-- <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" v-bind:data-dismiss="flagErrorEstado ? '' : 'modal' " @click="estadoProyecto()">Confirmar</button>
                        </div> -->
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        <!--Inicio de modal de estudiantes por proyecto-->
        <div class="modal fade" tabindex="-1" role="dialog" id="membersModal" aria-hidden="true">
            <div v-if="loading == 1">
                <spinner></spinner>
            </div>
            <div v-if="loading == 0" class="modal-dialog modal-primary modal-lg modal-student" role="document"
                style="max-width: 70vw;">
                <div class="modal-content modal-student">
                    <div class="modal-header">
                        <h5 class="modal-title">Estudiantes</h5>
                        <button type="button" class="close" data-dismiss="modal" @click="cerrarModal()"
                            aria-label="Close">
                            <span style="color: #ffff" aria-hidden="true">×</span>
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
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="estudiante in arrayEstudiantes" :key="estudiante.idUser">
                                        <td v-text="estudiante.nombres"></td>
                                        <td v-text="estudiante.apellidos"></td>
                                        <td v-text="estudiante.correo"></td>
                                        <td v-text="estudiante.perfil"></td>
                                        <td v-text="estudiante.carrera"></td>
                                        <td>
                                            <div v-if="estudiante.estado == 0" id="estado-btns-container">
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

                                            <div v-else-if="estudiante.estado == 2">
                                                <span class="badge badge-danger" style="border-radius: 5px;">
                                                    <p id="estadorp" style="display: inline;">RECHAZADO</p>
                                                </span>
                                            </div>
                                            <div v-else-if="estudiante.estado == 1 || estudiante.estado == 3">
                                                <span class="badge badge-success" style="border-radius: 5px;">
                                                    <p id="estadoap" style="display: inline;">ACEPTADO</p>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div v-if="estudiante.estado == 1"
                                                style="display: flex; flex-direction: row; justify-content: center; align-items: center;">

                                                <button type="button" data-toggle="modal"
                                                    data-target="#removeStudentModal"
                                                    @click="abrirModal('remover_estudiante', estudiante)"
                                                    class="btn btn-danger btn-sm">
                                                    Remover
                                                </button> &nbsp;
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
        <div class="modal fade" :class="{ 'mostrar': modal7 }" tabindex="-1" role="dialog" id="confirmModal"
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
                        <table class="table table-bordered table-sm appear-table">
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
                                    <td v-text="modal_correo" style="padding-left: 12px;"></td>
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
                            <tbody>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Contraparte</th>
                                    <td v-text="modal_contraparte" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Perfil del
                                        estudiante</th>
                                    <td v-text="modal_perfil_estudiante" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Tipo de horas
                                    </th>
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
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Correo encargado
                                    </th>
                                    <td v-text="modal_correo" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 15%">Fecha inicial
                                    </th>
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
        <!--Inicio del modal programar reunión-->
        <div class="modal fade" tabindex="-1" role="dialog" id="meetingModal" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title">Programar reunión</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" @click="cerrarModal()"
                            aria-label="Close">
                            <span aria-hidden="true" style="color: #ffffff">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre del proyecto</label>
                                <div class="col-md-9">
                                    <p v-text="proyecto"></p>
                                </div>
                            </div>
                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Fecha y hora</label>
                                <div class="col-md-3">
                                    <input type="date" value="2017-06-01" v-model="modal_fecha">
                                    <p :class="{ show: errorProyecto[0] == 1, hide: errorProyecto[0] != 1 }"
                                        class="error">El nombre no puede ir vacío</p>
                                </div>
                                <div class="col-md-3">
                                    <input type="time" value="09:00 am" v-model="modal_hora">
                                    <div v-if="errorProyecto[1] == 1 || errorProyecto[1] == 2">
                                        <p class="show error">{{ errorDateMsg }}</p>
                                    </div>
                                    <div v-else>
                                        <p class="hide error">.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Lugar o enlace</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="modal_lugar" class="form-control"
                                        placeholder="Lugar o enlace de la reunión">
                                    <p :class="{ show: errorProyecto[2] == 1, hide: errorProyecto[2] != 1 }"
                                        class="error">Debe incluir un lugar o enlace para la reunión</p>
                                </div>
                            </div>
                            <div class="form-group row div-form" style="margin-bottom: 20px">
                                <label class="col-md-3 form-control-label" for="text-input">Motivo o descripción de la
                                    reunión (opcional)</label>
                                <div class="col-md-9">
                                    <textarea type="text" v-model="modal_descripcion" class="form-control"
                                        style="resize: none;"
                                        placeholder="Motivo o descripción de la reunión"></textarea>
                                </div>
                            </div>
                            <div class="form-group row div-form">
                                <label class="col-md-3 form-control-label" for="text-input">Miembros inscritos</label>
                                <div class="col-md-9">
                                    
                                    <p v-if="arrayEstudiantes.filter(e=> e.estado == 1).length == 0" class="show error">No hay miembros inscritos para mandar correos</p>
                                    <div v-for="estudiante in arrayEstudiantes.filter(e => e.estado == 1)">
                                        <p v-text="estudiante.nombreCompleto"></p>
                                        <p v-text="estudiante.correoCompleto"></p>
                                    </div>
                                    <div v-if="flagError">
                                        <P class="show error">{{ errorPerfilMsg }}</P>
                                    </div>
                                    <div v-else>
                                        <p class="hide error">.</p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary"
                            v-if="arrayEstudiantes.filter(e=> e.estado == 1).length != 0"
                            v-bind:data-dismiss="flagErrorProyecto ? '' : 'modal'"
                            @click="enviarReunion()">Aceptar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        <!--Inicio del modal estado del proyecto-->
        <!-- <div class="modal fade" tabindex="-1" role="dialog" id="removeStudentModal" aria-hidden="true"> -->
        <div class="modal fade" :class="{ 'mostrar': modal4 }" tabindex="-1" role="dialog" id="removeStudentModal"
            aria-hidden="true">
            <div v-if="loading == 1">
                <spinner></spinner>
            </div>
            <div v-if="loading == 0" class="modal-dialog modal-primary" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title">Remover estudiante</h5>
                        </div>
                        <button id="cerrarModalARE1" type="button" class="close" data-dismiss="modal"
                            @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true" style="color: #ffffff">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div style="display: flex; flex-direction: row; align-items: baseline;">
                            <h6>Estás a punto de remover a &nbsp; </h6>
                            <h5 v-text="rem_nombre_completo"></h5>
                        </div>
                        <div style="display: flex; flex-direction: row; align-items: baseline;">
                            <h6>de: &nbsp;</h6>
                            <h6 style="font-weight: bold;" v-text="nombre_proyecto"></h6>
                        </div>
                        <p>¿Estás seguro/a de que deseas remover a este estudiante?</p>

                    </div>
                    <div class="modal-footer">

                        <button id="cerrarModalARE2" type="button" class="btn btn-secondary" data-dismiss="modal"
                            @click="cerrarModal()">Cancelar</button>
                        <button id="aceptarRechazarEst" type="button" class="btn btn-primary" data-dismiss="modal"
                            @click="removerEstudianteProyecto()">Confirmar</button>
                    </div>
                </div>
            </div>
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
            user_email: '',
            arrayProyectos: [],
            arrayCarreras: [''],
            arrayCarrerasSin: [''],
            arrayCarrerasCon: [''],
            arrayPerfiles: [''],
            arrayCarreraPerfil: [[]],
            arrayEstudiantes: [],
            nombre_estudiante_msg: '',
            add_edit_flag: 0,
            modal: 0,
            modal2: 0,
            modal3: 0,
            modal4: 0,
            modal5: 0,
            modal6: 0,
            modal7: 0,
            id_proyecto: 0,
            id_estudiante: 0,
            modal_encargado: '',
            modal_nombre: '',
            modal_desc: '',
            modal_correo: '',
            modal_tipo_horas: '',
            modal_cupos_act: 0,
            modal_cupos: '',
            modal_horario: '',
            modal_fecha_in: '',
            modal_fecha_fin: '',
            modal_contraparte: '',
            modal_carrera: 0,
            modal_perfil_estudiante: 0,
            modal_createdAt: '',
            modal_confirmar: '',
            modal_descripcion: '',
            modal_fecha: '',
            modal_hora: '',
            modal_lugar: '',
            carnet: '',
            nombre_completo: '',
            errorProyecto: [''],
            errorDateMsg: '',
            errorPerfilMsg: '',
            errorEstudianteMsg: '',
            errorEstado: 0,
            flagError: false,
            flagErrorProyecto: false,
            flagEstudiante: null,
            flagErrorEstado: false,
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0
            },
            offset: 3,
            rem_nombre_completo: '',
            proyecto: '',
            nombre_proyecto: '',
            modal_estado_proyecto: '',
            modal_estado: '2',
            filtrandoPorNombre: "",
            filtrandoPorCarrera: JSON.stringify({ 'por': 'carrera', 'id': -1 }),
            ordenandoPor: "recientes",
            selectedFilter: "",
            proyecto: "",
            arrayFactultad: [],
            estado_proyecto: '',
            flagTodasLasCarreras: '1',
            flagTodasLasCarrerasExcepto: false,
            arrayCarrerasSelector: [],
            arrayCarrerasSeleccionadas: [],
            filter_label: "Todas las carreras",
            order_label: "Recientes",
            default_filter: true,
            default_order: true,
            create_lim_inf: 1,
            create_lim_sup: 5
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
        }
    },
    methods: {
        bindData(page) {

            const me = this
            me.loadTable = true;

            var url = `${API_HOST}/todos_proyectos?page=${page}`;
            me.getFactultadesCarrerasAndPerfils();
            axios.get(`${API_HOST}/get_user`).then(function (response) {
                me.user_email = response.data.correo;
            })
                .catch(function (error) {
                    console.log(error);
                });
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayProyectos = respuesta.proyectos.data;
                me.pagination = respuesta.pagination;
                me.loadTable = false;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        bindDataByFilters(page) {

            const me = this

            me.loadTable = true;

            let filtros = JSON.parse(me.filtrandoPorCarrera)

            var url = `${API_HOST}/buscar_filtros?nombre=${me.filtrandoPorNombre}&filtro=${filtros.por}&id=${filtros.id}&orden=${me.ordenandoPor}&page=${page}`;

            me.getFacultadesCarrerasAndPerfils();

            axios.get(`${API_HOST}/get_user`).then(function (response) {
                me.user_email = response.data.correo;
            })
                .catch(function (error) {
                    console.log(error);
                });

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayProyectos = respuesta.proyectos.data;
                me.pagination = respuesta.pagination;
                me.loadTable = false;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cambiarPagina(page) {
            const me = this
            me.pagination.current_page = page;

            me.bindDataByFilters(page);

        },
        cambiarFiltro(filtro, label, defaultFilter = false) {
            const me = this
            me.filtrandoPorCarrera = filtro;
            me.filter_label = label;
            me.default_filter = defaultFilter
            me.bindDataByFilters(0);

        },
        cambiarOrden(orden, label, defaultOrder = false) {
            const me = this
            me.ordenandoPor = orden;
            me.order_label = label;
            me.default_order = defaultOrder
            me.bindDataByFilters(0);

        },
        async actualizarInsertarProyecto() {
            if (this.validarProyecto()) {
                return;
            }
            const me = this
            if (this.add_edit_flag == 1) {
                try {
                    const response = await axios.post(`${API_HOST}/proyecto/insertar`, {
                        'idProyecto': this.id_proyecto,
                        'nombre': this.modal_nombre,
                        'estado': 1,
                        'estado_proyecto': 'En curso',
                        'contraparte': this.modal_contraparte,
                        'cupos_act': 0,
                        'cupos': this.modal_cupos,
                        'descripcion': this.modal_desc,
                        'perfil_estudiante': this.modal_perfil_estudiante,
                        'encargado': this.modal_encargado,
                        'fecha_inicio': this.modal_fecha_in,
                        'fecha_fin': this.modal_fecha_fin,
                        'horario': this.modal_horario,
                        'tipo_horas': this.modal_tipo_horas,
                        'correo_encargado': this.modal_correo,
                        'carreraPerfil': this.arrayCarreraPerfil,
                        'aplicarTodasCarreras': this.flagTodasLasCarreras === '1' ? true : false,
                        'p_lim_inf': this.create_lim_inf,
                        'p_lim_sup': this.create_lim_sup
                    })

                    if (response.status == 200) {
                        this.$swal.fire({
                            icon: 'success',
                            title: 'Proyecto guardado',
                            text: 'El proyecto fue guardado exitosamente.',
                        });
                    }
                    else {
                        this.$swal.fire({
                            icon: 'error',
                            title: 'Error al guardar proyecto',
                            text: 'Ha ocurrido un error al guardar el proyecto. Intente nuevamente.',
                        });
                    }

                } catch (e) {
                    console.log(error);
                    this.$swal.fire({
                        icon: 'error',
                        title: 'Error al guardar proyecto',
                        text: 'Ha ocurrido un error al guardar el proyecto. Intente nuevamente.',

                    });
                } finally {
                    me.cerrarModal();
                    this.bindDataByFilters(0)
                }

            }
            else {
                try {

                    const response = await axios.put(`${API_HOST}/proyecto/actualizar`, {
                        'idProyecto': this.id_proyecto,
                        'nombre': this.modal_nombre,
                        'estado': 1,
                        'contraparte': this.modal_contraparte,
                        'cupos': this.modal_cupos,
                        'estado_proyecto': 'En curso',
                        'descripcion': this.modal_desc,
                        'perfil_estudiante': this.modal_perfil_estudiante,
                        'encargado': this.modal_encargado,
                        'fecha_inicio': this.modal_fecha_in,
                        'fecha_fin': this.modal_fecha_fin,
                        'horario': this.modal_horario,
                        'tipo_horas': this.modal_tipo_horas,
                        'correo_encargado': this.modal_correo,
                        'carreraPerfil': this.arrayCarreraPerfil,
                        'aplicarTodasCarreras': this.flagTodasLasCarreras === '1' ? true : false,
                        'p_lim_inf': this.create_lim_inf,
                        'p_lim_sup': this.create_lim_sup
                    })

                    if (response.status == 200) {
                        this.$swal.fire({
                            icon: 'success',
                            title: 'Proyecto guardado',
                            text: 'El proyecto fue guardado exitosamente.',
                        });
                    }
                    else {
                        this.$swal.fire({
                            icon: 'error',
                            title: 'Error al guardar proyecto',
                            text: 'Ha ocurrido un error al guardar el proyecto. Intente nuevamente.',
                        });
                    }
                }
                catch (e) {
                    this.$swal.fire({
                        icon: 'error',
                        title: 'Error al guardar proyecto',
                        text: 'Ha ocurrido un error al guardar el proyecto. Intente nuevamente.',
                    });
                    console.log(error);
                } finally {
                    me.cerrarModal();
                    this.bindDataByFilters(0)
                }
            }
        },
        async enviarReunion() {
            if (this.validarReunion()) {
                return;
            }
            const me = this
            if (this.id_proyecto) {
                try {

                    const response = await axios.post(`${API_HOST}/reunion`, {
                        'nombre_proyecto': this.proyecto,
                        'descripcion': this.modal_descripcion,
                        'encargado': this.modal_encargado,
                        "encargado_correo": this.modal_correo,
                        'estudiantes': this.arrayEstudiantes.filter(e => e.estado == 1).map(e => e.correoCompleto),
                        'lugar': this.modal_lugar,
                        'fecha': this.modal_fecha,
                        'hora': this.modal_hora
                    })

                    me.$swal.fire({
                        icon: 'success',
                        title: 'Correos enviados',
                        text: 'Los correos con información de la reunión fue enviado exitosamente.',
                    });

                    me.cerrarModal();
                } catch (e) {
                    me.$swal.fire({
                        icon: 'error',
                        title: 'Correos no enviados',
                        text: 'El proceso de envío de correos no se completó correctamente. Intente nuevamente.',
                    });
                    console.log(error);
                    me.cerrarModal();
                }
            }
        },
        regexCorreo(correo) {
            let re = new RegExp("^[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$");
            return re.test(correo)
        },
        validarProyecto() {
            this.errorProyecto = [];
            this.flagError = false;
            this.errorPerfilMsg = "";

            if (!this.modal_nombre) this.errorProyecto.push(1);
            else this.errorProyecto.push(0);
            if (!this.modal_encargado) this.errorProyecto.push(1);
            else this.errorProyecto.push(0);
            if (!this.modal_cupos) this.errorProyecto.push(1);
            else if (this.modal_cupos < 1) this.errorProyecto.push(2);
            else this.errorProyecto.push(0);
            if (!this.modal_tipo_horas) this.errorProyecto.push(1);
            else this.errorProyecto.push(0)
            //Ya no se comprueba la descripción (pasa a ser extra)
            this.errorProyecto.push(0)
            if (!this.modal_perfil_estudiante) this.errorProyecto.push(1);
            else if (this.modal_perfil_estudiante.length > 2000) this.errorProyecto.push(2);
            else this.errorProyecto.push(0)
            if (!this.modal_correo) this.errorProyecto.push(1)
            else if (!this.regexCorreo(this.modal_correo)) this.errorProyecto.push(2)
            else this.errorProyecto.push(0)
            if (!this.modal_horario) this.errorProyecto.push(1);
            else this.errorProyecto.push(0);
            if (!this.modal_contraparte) this.errorProyecto.push(1);
            else this.errorProyecto.push(0);
            if (!this.modal_fecha_in) {
                this.errorProyecto.push(1);
                this.errorDateMsg = "Debe seleccionar una fecha";
            }
            else if (this.modal_fecha_in >= this.modal_fecha_fin) {
                this.errorProyecto.push(2);
                this.errorDateMsg = "La fecha seleccionada no concuerda con la otra fecha"
            }
            else this.errorProyecto.push(0);
            if (!this.modal_fecha_fin) this.errorProyecto.push(1);
            else if (this.modal_fecha_in >= this.modal_fecha_fin) this.errorProyecto.push(2);
            else this.errorProyecto.push(0);

            var flagCP1 = true, flagCP2 = true, flagCP3 = true;
            var msg1 = "", msg2 = "", msg3 = "", msg4 = "";
            var i = 0, j = 0;

            if (this.arrayCarreraPerfil.length == 0 && this.flagTodasLasCarreras === '2') {
                this.flagError = true
                msg4 = "Debe agregar carreras"
            }

            if (this.flagTodasLasCarreras === '2') {
                this.arrayCarreraPerfil.forEach(document => {
                    if ((!document[0] || !document[1] || !document[2]) && flagCP1) {
                        msg1 = "Debe seleccionar todos los campos. ";
                        flagCP1 = false;
                        this.flagError = true;
                    }
                    if (document[1] > document[2] && flagCP2) {
                        msg2 = "Rango invalido, seleccione rangos válidos. ";
                        flagCP2 = false;
                        this.flagError = true;
                    }
                    j = 0;
                    this.arrayCarreraPerfil.forEach(subDocument => {
                        if (subDocument[0] && (i != j && document[0] == subDocument[0]) && flagCP3) {
                            msg3 = "No puede seleccionar la misma carrera más de una vez."
                            flagCP3 = false;
                            this.flagError = true;
                        }
                        j++
                    })
                    i++;
                })
            }


            this.errorPerfilMsg += msg1 + msg2 + msg3 + msg4;
            var tempFlag = false
            if (this.errorProyecto.find(element => element > 0) == undefined) {
                tempFlag = true
            }
            if (tempFlag && !this.flagError) {
                //No hay errores
                this.flagErrorProyecto = false
                return false;
            }
            this.flagErrorProyecto = true
            return true;
        },
        validarReunion() {
            this.errorProyecto = [];
            this.flagError = false;
            this.errorPerfilMsg = "";

            if (!this.modal_fecha) {
                this.errorProyecto.push(1);
                this.errorDateMsg = "Debe seleccionar una fecha";
            }
            else this.errorProyecto.push(0);
            if (!this.modal_hora) this.errorProyecto.push(1);
            else this.errorProyecto.push(0);
            if (!this.modal_lugar) this.errorProyecto.push(1);
            else this.errorProyecto.push(0);

            if (this.arrayEstudiantes.length == 0) {
                this.flagError = true
                this.errorPerfilMsg = "El proyecto no tiene estudiantes inscritos"
            }

            var tempFlag = false
            if (this.errorProyecto.find(element => element > 0) == undefined) {
                tempFlag = true
            }
            if (tempFlag && !this.flagError) {
                //No hay errores
                this.flagErrorProyecto = false
                return false;
            }
            this.flagErrorProyecto = true;
            return true;
        },
        async estadoProyecto() {
            const me = this
            if (me.modal_confirmar != me.modal_nombre) {
                me.flagErrorEstado = true
                me.errorEstado = 1
            }
            else {
                try{
                    me.loading = 1;
                    await axios.post(`${API_HOST}/proyecto/${me.modal_estado == '2' ? 'finalizar' : 'cancelar'}`, {
                        idProyecto: this.id_proyecto
                    })

                    this.$swal.fire({
                        icon: response.data.success ? 'success' : 'error',
                        title: 'Estado del proyecto',
                        text: response.data.message,
                    });
                }
                catch(error) {
                    console.log(error);
                }
                finally {
                    $('#statusModal').modal('hide');
                    me.loading = 0;
                    me.bindDataByFilters();
                    me.cerrarModal();
                }
            }
        },

        agregarTodasLasCarreras() {

            let i = 0;
            const me = this

            me.arrayCarreraPerfil = [];
            let carreras = this.arrayCarrerasSin;

            carreras.forEach(carrera => {
                me.agregarCarrera(carrera, me.create_lim_inf, me.create_lim_sup);
                i++;
            })

        },
        eliminarTodasLasCarreras() {
            this.arrayCarreraPerfil = [];
            this.arrayCarrerasSelector = this.arrayCarreras;
        },
        agregarCarrera(carrera, lim_inf, lim_sup) {
            this.arrayCarreraPerfil.push([carrera.idCarrera, lim_inf, lim_sup]);
            this.arrayCarrerasSeleccionadas.push(carrera.idCarrera);
        },
        cerrarModal() {
            if (this.modal == 1 || this.modal4 == 1) {
                this.modal = 0;
                this.modal4 = 0;
            }
            else {
                this.add_edit_flag = 0;
                this.modal2 = 0;
                this.modal3 = 0;
                this.modal5 = 0;
                this.modal6 = 0;
                this.modal7 = 0;
            }
        },
        abrirModal(modelo, data = [], flag) {
            this.loading = 0
            switch (modelo) {
                case "insertar":
                    {
                        this.add_edit_flag = 1;
                        this.modal = 1;
                        this.modal_nombre = '';
                        this.modal_encargado = '';
                        this.modal_cupos = ''
                        this.modal_desc = '';
                        this.modal_perfil_estudiante = '';
                        this.modal_correo = '';
                        this.modal_horario = '';
                        this.modal_contraparte = '';
                        this.modal_tipo_horas = '';
                        //this.modal_estado_proyecto = '';
                        this.contraparte = '';
                        this.modal_fecha_in = '';
                        this.modal_fecha_fin = '';
                        this.errorProyecto = [];
                        this.errorPerfilMsg = '';
                        this.flagError = false;
                        this.flagErrorProyecto = false;
                        this.arrayCarreraPerfil = [];
                        this.flagTodasLasCarreras = '1';
                        break;
                    }
                case "editar":
                    {
                        this.add_edit_flag = 2;
                        this.modal = 1;
                        this.id_proyecto = data.idProyecto;
                        this.modal_encargado = data.encargado;
                        this.modal_nombre = data.nombre;
                        this.modal_desc = data.descripcion;
                        this.modal_perfil_estudiante = data.perfil_estudiante;
                        this.modal_correo = data.correo_encargado;
                        this.modal_tipo_horas = data.tipo_horas;
                        //this.modal_estado_proyecto = data.estado_proyecto;
                        this.modal_cupos = data.cupos;
                        this.modal_horario = data.horario;
                        this.modal_fecha_in = data.fecha_inicio;
                        this.modal_fecha_fin = data.fecha_fin;
                        this.modal_contraparte = data.contraparte;
                        this.modal_createdAt = data.createdAt;
                        this.flagError = false;
                        this.flagErrorProyecto = false;
                        this.errorPerfilMsg = "";
                        this.arrayCarreraPerfil = [[]];
                        this.updateCarrerasAndPerfil();
                        this.flagTodasLasCarreras = '2';
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
                case "estudiantes":
                    {
                        this.modal3 = 1;
                        this.id_proyecto = data.idProyecto;
                        this.modal_nombre = data.nombre;
                        this.modal_cupos = data.cupos;
                        this.carnet = '';
                        this.nombre_completo = '';
                        this.id_estudiante = 0;
                        this.flagError = false;
                        this.errorEstudianteMsg = '';
                        this.getEstudiantes()
                        this.proyectoInscrito = data;
                        break;
                    }
                case "confirmacion":
                    {
                        this.modal4 = 1;
                        if (flag) this.nombre_estudiante_msg = "¿Aceptar al estudiante " + data.nombres + " " + data.apellidos + "?";
                        else this.nombre_estudiante_msg = "¿Rechazar al estudiante " + data.nombres + " " + data.apellidos + "?";
                        this.flagEstudiante = flag;
                        this.id_estudiante = data.idUser;
                        break;
                    }
                case "info":
                    {
                        this.modal5 = 1;
                        this.id_proyecto = data.idProyecto;
                        this.modal_encargado = data.encargado;
                        this.modal_correo = data.correo_encargado;
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
                case "reunion":
                    {
                        this.modal6 = 1;
                        this.id_proyecto = data.idProyecto;
                        this.proyecto = data.nombre;
                        this.modal_descripcion = '';
                        this.modal_encargado = data.encargado;
                        this.modal_correo = data.correo_encargado;
                        this.arrayEstudiantes = this.getEstudiantes();
                        this.modal_lugar = '';
                        this.modal_fecha = '';
                        this.modal_hora = '';
                        this.flagError = false;
                        this.flagErrorProyecto = false;
                        this.errorPerfilMsg = "";
                        this.errorProyecto = [];
                        this.errorPerfilMsg = '';
                        break;
                    }
                case "remover_estudiante":
                    {
                        this.modal7 = 1;
                        this.id_proyecto = this.proyectoInscrito.idProyecto;
                        this.nombre_proyecto = this.proyectoInscrito.nombre;
                        this.modal_cupos = data.cupos;
                        this.carnet = '';
                        this.rem_nombre_completo = data.nombres + " " + data.apellidos;
                        this.id_estudiante = data.idUser;
                        this.flagError = false;
                        this.errorEstudianteMsg = '';
                        break;
                    }
                default:
                    break;
            }
        },
        getFacultadesCarrerasAndPerfils() {
            const me = this
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
        updateCarrerasAndPerfil() {
            const me = this
            axios.get(`${API_HOST}/proyectos/carreras`, {
                params: {
                    idProyecto: me.id_proyecto
                }
            }).then(function (response) {
                var i = 0;
                response.data.forEach(document => {
                    me.arrayCarreraPerfil[i][0] = document.idCarrera;
                    me.arrayCarreraPerfil[i][1] = document.limite_inf;
                    me.arrayCarreraPerfil[i][2] = document.limite_sup;
                    me.arrayCarreraPerfil.push([]);
                    i++;
                })
                if (i != 0) me.arrayCarreraPerfil.pop()
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        agregarACP() {
            this.arrayCarreraPerfil.push([, this.create_lim_inf, this.create_lim_sup])
        },
        eliminarACP(acp) {
            let index = this.arrayCarreraPerfil.indexOf(acp);
            this.arrayCarreraPerfil.splice(index, 1)
        },
        getEstudiantes() {
            const me = this
            axios.get(`${API_HOST}/proyecto/estudiantes`, {
                params: {
                    idProyecto: me.id_proyecto
                }
            }).then(function (response) {
                me.arrayEstudiantes = response.data;
                me.arrayEstudiantes.forEach(function (element, index, array) {
                    me.arrayEstudiantes[index].correoCompleto = element.correo;
                    me.arrayEstudiantes[index].correo = element.correo.substr(0, 8);
                    me.arrayEstudiantes[index].nombreCompleto = element.nombres + " " + element.apellidos;
                    me.arrayEstudiantes[index].carrera = element.n_carrera;
                    me.arrayEstudiantes[index].perfil = element.n_perfil;
                })
            })
                .catch(function (error) {
                    console.log(error);
                });
            axios.get(`${API_HOST}/proyecto/cupos_actuales`, {
                params: {
                    idProyecto: me.id_proyecto
                }
            }).then(function (response) {
                me.modal_cupos_act = response.data.cupos - response.data.cupos_act;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        buscarEstudiante() {
            const me = this
            //this.errorActualizar = false
            var url = `${API_HOST}/estudiante/carnet`
            axios.get(url, {
                params: {
                    carnet: me.carnet
                }
            }).then(function (response) {
                var estudiante = response.data[0];
                if (estudiante != null) {
                    me.nombre_completo = estudiante.nombres + " " + estudiante.apellidos;
                    me.id_estudiante = estudiante.idUser;
                    me.flagError = false;
                }
                else {
                    me.errorEstudianteMsg = "No se ha encontrado resultados";
                    me.flagError = true;
                }
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        aplicarPorAdmin() {
            const me = this
            me.loading = 1;
            var url = `${API_HOST}/proyecto/estudiantes/add`
            axios.post(url, {
                'idProyecto': me.id_proyecto,
                'idUser': me.id_estudiante,
                'estado': 1,
            }).then(function (response) {
                if (response.data) {
                    me.errorEstudianteMsg = response.data;
                    me.flagError = true;
                }
                me.loading = 0;
                me.getEstudiantes();
                me.bindDataByFilters();
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        aceptarRechazarEstudiante() {


            /*
                            WARNING: Existe duplicidad de codigo en el componente TablaSolicitudes 
                            en el metodo aceptarRechazarEstudiante, se debe de refactorizar el codigo si hay 
                            una modificacion en este metodo
            */


            // console.log("Aceptando o rechazando estudiante")
            const me = this
            me.loading = 1;
            // var estadoEst = 2;
            // Si la flag del modal Aceptar / Rechazar es verdadera, entonces se acepta al estudiante
            if (me.flagEstudiante == true) {
                // console.log("Aceptando estudiante")
                axios.put(`${API_HOST}/proyecto/estudiantes/aceptar`, {
                    'idUser': me.id_estudiante,
                    'idProyecto': me.id_proyecto,
                    // 'estado' : estadoEst
                }).then(function (response) {
                    $('#confirmModal').modal('hide');
                    me.loading = 2;
                    me.cerrarModal();
                    me.getEstudiantes();
                    me.bindDataByFilters();
                    me.loading = 0;
                    me.flagEstudiante = null;
                }).catch(function (error) {
                    console.log(error);
                    me.flagEstudiante = null;
                });
                return
            }
            else {
                // console.log("Rechazando estudiante")
                axios.put(`${API_HOST}/proyecto/estudiantes/rechazar`, {
                    'idUser': me.id_estudiante,
                    'idProyecto': me.id_proyecto,
                }).then(function () {
                    me.flagEstudiante = null;
                    $('#confirmModal').modal('hide');
                    me.getEstudiantes();
                    me.bindDataByFilters();
                    me.loading = 0;

                }).catch(function (error) {
                    console.log(error);
                });
                // estadoEst = 1;
                return
            }

        },
        logout() {
            var url = `${API_HOST}/logout`;
            axios.post(url).then(() => location.href = `${API_HOST}/`)
        },
        removerEstudianteProyecto() {
            const me = this
            me.loading = 1;
            axios.delete(`${API_HOST}/proyectos/${me.id_proyecto}/estudiante/${me.id_estudiante}`, {
                'estado': 0
            }).then(function (response) {
                $('#removeStudentModal').modal('hide');
                $('#membersModal').modal('hide');
                me.loading = 2;


                me.getEstudiantes();
                me.bindDataByFilters();
                me.loading = 0;
            }).catch(function (error) {
                console.log(error);
            });
        },
        showChangeStatusConfirm(estado) {

            if (estado != 'finalizar' && estado != 'cancelar' && estado != 'eliminar') {
                //console.log("Estado no valido")
                return
            }

            if (this.modal_confirmar != this.modal_nombre) {
                this.flagErrorEstado = true
                this.errorEstado = 1
                return
            }

            this.$swal.fire({
                title: `¿Estas seguro? de ${estado} el proyecto`,
                text: `No podras deshacer esta acción\n ${estado == 'eliminar' ? 'Se eliminara el registro completo de este proyecto.' : ''}`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.cambiarEstadoProyecto(estado);
                }
                this.cerrarModal();
            });
        },
        async cambiarEstadoProyecto(estado) {
            if (estado != 'finalizar' && estado != 'cancelar' && estado != 'eliminar') {
                //console.log("Estado no valido")
                return
            }
            const me = this
            if (me.modal_confirmar != me.modal_nombre) {
                me.flagErrorEstado = true
                me.errorEstado = 1
            }
            else {
                me.loading = 1;
                try {
                    const response = await axios.post(`${API_HOST}/proyecto/${estado}`, {
                        idProyecto: this.id_proyecto
                    })
                    // console.log(response);

                    // $('#statusModal').modal('hide');

                    this.$swal.fire({
                        icon: response.data.success ? 'success' : 'error',
                        title: 'Estado del proyecto',
                        text: response.data.message,
                    });

                } catch (error) {
                    console.log(error);
                } finally {
                    $('#statusModal').modal('hide');
                    me.loading = 0;
                    me.bindDataByFilters();
                    me.cerrarModal();
                };

            }
        },
        disabledBotonAgregarCarrera() {
            if (this.arrayCarreraPerfil.length > 0) {
                if (this.arrayCarreraPerfil[this.arrayCarreraPerfil.length - 1][0]) {
                    if (this.arrayCarreraPerfil[0][0] == -1 || this.arrayCarreraPerfil[0][0] == -2) {
                        // document.getElementById("agregarCP").disabled = true;
                        return true;
                    }
                    else {
                        // document.getElementById("agregarCP").disabled = false;
                        // this.arrayCarreras = this.arrayCarrerasSin.slice();
                        return false;
                    }
                }
                else {
                    // document.getElementById("agregarCP").disabled = true;
                    return true;
                }
            }
            else {
                // document.getElementById("agregarCP").disabled = false;
                // this.arrayCarreras = this.arrayCarrerasCon.slice();
                return false;
            }
        }
    },
    watch: {
        arrayCarreraPerfil: function (val) {
            // if(this.arrayCarreraPerfil.length > 0){
            //     if(this.arrayCarreraPerfil[this.arrayCarreraPerfil.length-1][0]){
            //         if(this.arrayCarreraPerfil[0][0] == -1 || this.arrayCarreraPerfil[0][0] == -2){
            //             document.getElementById("agregarCP").disabled = true;
            //         }
            //         else{
            //             document.getElementById("agregarCP").disabled = false;
            //             // this.arrayCarreras = this.arrayCarrerasSin.slice();
            //         }
            //     }
            //     else{
            //         document.getElementById("agregarCP").disabled = true;
            //     }
            // }
            // else{
            //     document.getElementById("agregarCP").disabled = false;
            //     // this.arrayCarreras = this.arrayCarrerasCon.slice();
            // }
        },
        flagErrorEstado: function () {
            //console.log("Hola")
        },
        arrayCarreras: function (val) {
            // //console.log("Carreras", val)
        }
    },
    mounted() {
        this.bindDataByFilters(1);
    },
}
</script>
<style lang="scss">
body {
    font-size: 1em;
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

.main {
    font-family: "Abel", sans-serif;
    background-color: white;
    display: flex;
    flex-direction: column;
    min-height: 95vh;
}

.container-fluid {
    flex: 1;
}

.modal-primary .modal-header {
    background-color: #003C71;
    display: flex;
    flex-flow: row;
    justify-content: space-between;
    border: none;
}



.button-container {
    margin: 0em 0.25em;
    display: flex;
    justify-content: center;
}

.btn {
    border-radius: 6px;
}

.text-button {
    background: none;
    border: none;
}

.text-button:hover {
    background: #c2c2c2;
}

.error {
    color: red;
    margin: 0.25em;
    margin-bottom: 0.5em;
}

.show {
    visibility: visible;
}

.hide {
    visibility: hidden;
}

.div-form {
    margin-bottom: 0em;
}

.modal-primary .modal-content {
    width: 100%;
    max-width: none;
}

.modal-student {
    max-width: none;
}

.sidebar-fixed .sidebar {
    height: 100%;
}

.card-body {
    padding: 0;
}

.search-student {
    display: inline-block;
}

.dropdown-header {
    background-color: #003C71;
    color: white;
}

.sidebar {
    background-color: #003C71;

}

.-alt {
    padding-left: 0;
}

.form-button-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dropdown-menu {
    min-width: max-content;
    max-height: 250px;
    overflow: auto;
    margin-top: 1em;
    padding: 5px;
}


.appear-table {
    display: none;
}

.f-8em {
    font-size: 14px;
    font-weight: normal;
}

@media screen and (max-width: 450px) {
    .btn-label {
        display: none;
    }

    .modal-dialog {
        min-width: 95%;
        margin: 20px 0px;
    }
}

/* Ponelo por aqui lo del sidebar */
@media screen and (max-width: 500px) {
    .btn-label {
        display: none;
    }

    .modal-dialog {
        max-width: 95%;
        margin: 20px auto;
    }

    #estado-btns-container {
        display: flex;
        flex-direction: column;
    }

}


#estado-btns-container {
    display: flex;
    justify-content: center;
}

// .modal-dialog {
//     max-width: 100%;
//     margin: auto;
// }

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

    .state-btn-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 1em;
    }

}

@media screen and (min-width: 991px) {
    #logout {
        margin-right: 30px;
    }

    .inputs {
        width: 100%;
    }
}


#footer {
    margin-left: 0px;
}

#membersbutton #badge {
    position: absolute;
    margin-top: -8px;
    right: 9px;
    padding: 6px 6px;
    border-radius: 50%;
    background-color: red;
    color: white;
}

@media screen and (max-width: 900px) {
    #student-button {
        font-size: 1em;
        width: 12vw;
    }
}

@media screen and (max-width: 575px) {
    #student-button {
        font-size: .75em;
    }


}



@media screen and (max-width: 500px) {
    .disappear {
        display: none;
    }

    .appear-table {
        display: table;
    }
}

@media screen and (max-width: 1000px) {
    #estadoa {
        display: inline;
    }

    #estador {
        display: inline;
    }

    #estadoap {
        display: none;
    }

    #estadorp {
        display: none;
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
    height: 100%;
    flex: 1;
}

.search-group {
    align-items: center;
    flex: 2;
    display: flex;
    gap: 1em;
    max-width: 60%;
    height: 2.5em;
    flex-wrap: nowrap;
}

.filter-selector {
    max-width: 25%;
}

.search-input {
    flex: 2;
    height: 100%;
}

.form-check {
    margin: 0;
    padding: 0.5em;
    border-radius: 0.5em;
    margin-bottom: 10px;

}

.form-check-label {
    margin-left: 0.5em;
    font-size: 1.5em;
}

.form-check-input {
    margin-left: 0.5em;
}

.state-btn-container {
    display: flex;
    align-items: center;
    gap: 1em;

}

@media screen and (max-width: 1000px) {

    .search-group {
        max-width: 100%;
        min-width: 50vw;
    }

}

.badge-pill {
    height: 2em;
    border-radius: 6px;
    padding: 6px;
}
</style>