<template>
    <main class="main" style="background-color: white;">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Inicio</li>
            <li class="breadcrumb-item active">Proyectos Aplicados</li>
        </ol>
        <div class="container-fluid px-4">
            <!-- Ejemplo de tabla Listado -->
            <div v-if="loadTable == true" class="card" style="border: none;">
                <table-loader></table-loader>
            </div>
            <div v-else class="card" style="border: none;">
                <div class="card-body px-0 py-0">
                    <table v-if="arrayProyectos.length > 0" class="table table-bordered table-hover table-sm">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 15%;">Contraparte</th>
                                <th style="text-align: center; width: 25%;">Proyecto</th>
                                <th style="text-align: center; width: 25%;" class="disappear">Perfil Estudiante</th>
                                <th style="width: 10%; text-align: center;" class="disappear">Cupos</th>
                                <th style="width: 10%; text-align: center;">Estado</th>
                                <th style="width: 10%; text-align: center;">Cancelar aplicación</th>
                                </tr>
                        </thead>
                        <tbody>
                            <tr v-for="proyecto in arrayProyectos" :key="proyecto.idProyecto">
                                <td v-text="proyecto.contraparte" data-toggle="modal" data-target="#modal-info"
                                    @click="abrirModal('info', proyecto)"></td>
                                <td v-text="proyecto.nombre" data-toggle="modal" data-target="#modal-info"
                                    @click="abrirModal('info', proyecto)"></td>
                                <td v-text="proyecto.perfil_estudiante" data-toggle="modal" data-target="#modal-info"
                                    @click="abrirModal('info', proyecto)" class="disappear"></td>
                                <td v-text="`${proyecto.cupos_act}${'/'}${proyecto.cupos}`" data-toggle="modal"
                                    data-target="#modal-info" @click="abrirModal('info', proyecto)"
                                    style="text-align: center;" class="disappear"></td>
                                <td>
                                    <div v-if="proyecto.estadoPxe === 0" style="margin: 8px -9px 8px -5px;">
                                        <div
                                            style="display: flex; flex-direction: row; justify-content: center; margin: 0px 10px;"
                                            @click="abrirModal('info', proyecto)">
                                            <span  class="badge badge-info" style="border-radius: 5px;">
                                                <p id="estadoap" style="display: inline; font-weight: 300; font-size: 1.0rem; ">
                                                    <i class="icon-clock"></i>
                                                    <span class="disappear">SOLICITUD ENVIADA</span> 
                                                </p></span>
                                        </div>
                                    </div>
                                    <div v-if="proyecto.estadoPxe === 1" style="margin: 8px -9px 8px -5px;">
                                        <div
                                            style="display: flex; flex-direction: row; justify-content: center; margin: 0px 10px;">
                                            <span class="badge badge-primary" style="border-radius: 5px;" @click="abrirModal('info', proyecto)">
                                                <p id="estadoap" style="display: inline; font-weight: 300; font-size: 1.0rem; ">
                                                    <i class="icon-check"></i>
                                                    <span class="disappear"> ACEPTADO </span>
                                                </p></span>
                                        </div>
                                    </div>
                                    <div v-if="proyecto.estadoPxe === 2 " style="margin: 8px -9px 8px -5px;">
                                        <div
                                            style="display: flex; flex-direction: row; justify-content: center; margin: 0px 10px;">
                                            <span  class="badge badge-danger" style="border-radius: 5px;">
                                                <p id="estadorp" style="display: inline; font-weight: 300; font-size: 1.0rem;">
                                                    <i class="icon-close"></i>
                                                    <span class="disappear">RECHAZADO</span>
                                                </p></span>                                            
                                        </div>
                                    </div>
                                    <div v-if="proyecto.estadoPxe === 3 " style="margin: 8px -9px 8px -5px;">
                                        <div
                                            style="display: flex; flex-direction: row; justify-content: center; margin: 0px 10px;">
                                            <span  class="badge badge-success" style="border-radius: 5px;">
                                                <p id="estadorp" style="display: inline; font-weight: 300; font-size: 1.0rem; ">
                                                    <i class="icon-folder"></i>
                                                    <span class="disappear">PROYECTO FINALIZADO</span>
                                                </p></span>
                                        </div>
                                    </div>

                                    <div v-if="proyecto.estadoPxe === 4 " style="margin: 8px -9px 8px -5px;">
                                        <div
                                            style="display: flex; flex-direction: row; justify-content: center; margin: 0px 10px;">
                                            <span  class="badge badge-danger" style="border-radius: 5px;">
                                                <p id="estadorp" style="display: inline; font-weight: 300; font-size: 1.0rem; ">
                                                    <i class="icon-info"></i>
                                                    <span class="disappear">PROYECTO CANCELADO</span>
                                                </p></span>
                                        </div>
                                    </div>

                                </td>
                                <td>
                                    <div v-if="proyecto.estadoPxe === 0" style="margin: 8px -9px 8px -5px;">
                                        <div
                                            style="display: flex; flex-direction: row; justify-content: center; margin: 0px 10px;">
                                            <button type="button" data-toggle="modal" data-target="#modal-eliminar"
                                                @click="abrirModal('desaplicar', proyecto)" class="btn btn-danger "
                                                style="border-radius: 5px;">
                                                <i class="icon-trash"></i>
                                                <span class="disappear">Desaplicar</span>
                                                
                                            </button> &nbsp;
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-else class="alert alert-warning" role="alert">
                        No has aplicado a ningun proyecto
                    </div>
                </div>
                <div>
                    <div class="appear">
                        <p> Estado de las solicitudes: </p>
                        <span class="badge badge-info" style="border-radius: 5px; margin: 0px 0.6em 0.6em 0px;">
                            <p id="estadorp" style="display: inline; font-weight: 300; font-size: 1.0rem;">
                                <i class="icon-clock"></i>
                                <span>Solicitud Enviada</span>
                            </p>
                        </span>
                        <span class="badge badge-success" style="border-radius: 5px; margin: 0px 0.6em 0.6em 0px;">
                            <p id="estadorp" style="display: inline; font-weight: 300; font-size: 1.0rem;">
                                <i class="icon-check"></i>
                                <span>Aceptado</span>
                            </p>
                        </span>
                        <span class="badge badge-danger" style="border-radius: 5px; margin: 0px 0.6em 0.6em 0px;">
                            <p id="estadorp" style="display: inline; font-weight: 300; font-size: 1.0rem;">
                                <i class="icon-close"></i>
                                <span>Rechazado</span>
                            </p>
                        </span>
                        <span class="badge badge-secondary" style="border-radius: 5px; margin: 0px 0.6em 0.6em 0px;">
                            <p id="estadorp" style="display: inline; font-weight: 300; font-size: 1.0rem;">
                                <i class="icon-folder"></i>
                                <span>Proyecto finalizado</span>
                            </p>
                        </span>
                        <span class="badge badge-danger" style="border-radius: 5px; margin: 0px 0.6em 0.6em 0px;">
                            <p id="estadorp" style="display: inline; font-weight: 300; font-size: 1.0rem;">
                                <i class="icon-close"></i>
                                <span>Proyecto Cancelado</span>
                            </p>
                        </span>
                    </div>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal informacion de proyecto-->
        <div class="modal fade" tabindex="-1" id="modal-info" role="dialog" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-text="modal_nombre"></h5>
                        <button type="button" class="close" data-dismiss="modal" @click="cerrarModal()" aria-label="Close">
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
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Contraparte</th>
                                    <td v-text="modal_contraparte" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Perfil del estudiante</th>
                                    <td v-text="modal_perfil_estudiante" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Tipo de horas</th>
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
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Fecha inicial</th>
                                    <td v-text="modal_fecha_in" style="padding-left: 12px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-4" style="background-color: #dedede; width: 25%">Fecha final</th>
                                    <td v-text="modal_fecha_fin" style="padding-left: 12px;"></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            @click="cerrarModal()">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        <!--Inicio del modal eliminar-->
        <div class="modal fade" tabindex="-1" id="modal-eliminar" role="dialog" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-text="nombre"></h5>
                        <button type="button" class="close" data-dismiss="modal" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true" style="color: #ffffff">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h3>¿Eliminar proyecto de su lista de aplicados?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal"
                            @click="desAplicarProyecto()">Confirmar</button>
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
            loadTable: false,
            user_email: '',
            nombre: '',
            user_id: '',
            user_email: '',
            descripcion: '',
            arrayProyectos: [''],
            arrayPXE: [''],
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
            modal_contraparte: '',
            modal_perfil_estudiante: '',
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0
            },
            offset: 3
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
            var url = `${API_HOST}/proyecto/aplicado` /*?page=' + page*/;
            axios.get(url).then(function (response) {
                me.arrayProyectos = response.data;
                //console.log(me.arrayProyectos)
                me.loadTable = false;
            })
                .catch(function (error) {
                    console.log(error);
                });

            axios.get(`${API_HOST}/get_user`).then(function (response) {
                me.user_id = response.data.idUser;
                me.user_email = response.data.correo;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        desAplicarProyecto() {
            const me = this;
            axios.post(`${API_HOST}/proyecto/desaplicar`, {
                'idProyecto': this.id_proyecto,
                'idUser': this.user_id
            }).then(function (response) {
                me.cerrarModal();
                me.bindData();
            }).catch(function (error) {
                console.log(error);
            });
        },
        cerrarModal() {
            this.modal = 0;
            this.modal2 = 0;
        },
        abrirModal(modelo, data = []) {
            switch (modelo) {
                case "info":
                    {
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
                        this.modal_contraparte = data.contraparte;
                        this.modal_perfil_estudiante = data.perfil_estudiante;
                        break;
                    }
                case "desaplicar":
                    {
                        this.modal2 = 1;
                        this.id_proyecto = data.idProyecto;
                        this.nombre = data.nombre;
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
    }
}
</script>
<style scoped>

.main {
    font-family: "Abel", sans-serif;
}

#footer {
    margin-left: 0px;
}

.modal-content {
    width: 100% !important;
    position: absolute !important;
}
.appear {
        display: none;
}
.appear-table {
        display: none;
}
.mostrar  {
    display: list-item !important;
    opacity: 1 !important;
    background-color: #3c29297a !important;
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

@media screen and (max-width: 500px) {
    .disappear {
        display: none;
    }
    .appear {
        display: block;
    }
    .appear-table {
        display: table;
    }

}

@media screen and (max-width: 450px) {
    .btn-label {
        display: none;
    }
}

@media screen and (max-width: 760px) {
    .btn-label {
        display: none;
    }
}</style>