<template>
    <main class="main">
        <ol class="breadcrumb" style="padding-left: 30px;">
            <li class="breadcrumb-item">Inicio</li>
            <li class="breadcrumb-item active">Administración de Carreras</li>
        </ol>
        <div class="container container-fluid">
            <table class="table table-bordered table-hover table-sm">

            <thead>
                <tr>
                    <th>Facultad</th>
                    <th>Carrera</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="carrera in carreras" :key="carrera.id">
                    <td>{{ carrera.nombre_facultad }}</td>
                    <td>{{ carrera.nombre_carrera }}</td>
                    <td id="icons-pos" >
                        <div class="button-container">
                            <button type="button" @click="abrirModal('editar', carrera)" data-toggle="modal" data-target="#carreersModal" class="btn btn-warning btn-sm" style="width: 100%;">
                                <i class="icon-pencil"></i>
                                <span class="btn-label">Editar</span>
                            </button>
                        </div>
                        <div class="button-container">
                            <button v-if="carrera.estado == 1" type="button" @click="abrirModal('eliminar', carrera)" data-toggle="modal" data-target="#statusModal" class="btn btn-danger btn-sm" style="margin: 2px 0; width: 100%;">
                                <i class="icon-lock"></i>
                                <span class="btn-label">Desactivar</span>
                            </button>
                            <button v-else type="button" @click="abrirModal('activar', carrera)" data-toggle="modal" data-target="#statusModal" class="btn btn-success btn-sm" style="margin: 2px 0; width: 100%;">
                                <i class="icon-check"></i>
                                <span class="btn-label">Activar</span>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <button class="btn btn-success mb-3" @click="abrirModal('insertar', null)" data-toggle="modal" data-target="#carreersModal">Nueva carrera</button>
        <!--Inicio del modal-->        
        <div class="modal fade" tabindex="-1" role="dialog" id="carreersModal" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div v-if="add_edit_flag = 1">
                                <h4 class="modal-title">Nueva carrera</h4>
                            </div>
                            <div v-else-if="add_edit_flag = 2">
                                <h4 class="modal-title">Editar carrera</h4>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true" style="color: #ffffff">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <select class="form-control custom-select" v-model="modal_facultad" required >
                                    <option value="" disabled selected>Seleccione la facultad</option>
                                    <!--<option v-for="facultad in facultades" :value="JSON.stringify({nombre: 'facultad', id: facultad.idFacultad})" :key="facultad.idFacultad">{{facultad.nombre}}</option>-->
                                    <option v-for="facultad in facultades" :value="facultad.idFacultad" :key="facultad.nombre">{{facultad.nombre}}</option>
                                </select>
                                <input class="form-control" type="text" v-model="nombre_carrera" placeholder="Carrera" required>
                            </form>
                        </div>
                        <div class="modal-footer" style="border-top: none;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-success" v-bind:data-dismiss="flagErrorProyecto ? '' : 'modal' " @click ="actualizarInsertarCarrera()">Guardar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
    </div>

    <footer class="app-footer" id="footer"
    style="display: flex; flex-direction: column; justify-content: center; font-size: 15px; padding: 10px 0px">
    <span><a target="_blank" href="http://www.uca.edu.sv/servicio-social/">Centro de Servicio Social | UCA</a>
        &copy; 2024</span>
        <span>Desarrollado por <a href="#"></a>Grupo de Horas Sociales</span>
    </footer>
</main>
</template>

<script>
import { API_HOST } from '../constants/endpoint';
import Swal from 'sweetalert2';
export default {
    data() {
        return {
            carreras: [],
            facultades: [],
            showRegisterModal: false,
            add_edit_flag : 0,
            modal: 0,
            idFacultad: 0,
            infoFacultad: JSON.stringify({'nombre': 'facultad', 'id': -1}),
            facultad: '',
            idCarrera: 0,
            nombre_carrera: '',
            errorMessage: '',
            showSuccess: false,
            modal_facultad: '',
            flagErrorProyecto: false
        };
    },
    methods: {
        showModal() {
            this.showRegisterModal = !this.showRegisterModal;
        },
        actualizarInsertarCarrera(){
            const me = this;

            if(!this.idCarrera){
                axios.post(`${API_HOST}/carreras/insertar`, {
                    'idCarrera' : this.idCarrera,
                    'idFacultad' : this.modal_facultad,
                    'nombre' : this.nombre_carrera,
                    'estado' : 1
                }).then(function (response) {
                    me.cerrarModal();
                    if (response.status == 200) {
                        me.showSuccess = true
                        Swal.fire({
                            icon: 'success',
                            title: 'Carrera creada',
                            text: 'La carrera ha sido creada exitosamente.',
                        });
                    }
                    me.bindData(); 
                }).catch(function (error) {
                    console.log(error);
                }); 
            }
            else{
                axios.put(`${API_HOST}/carreras/actualizar`, {
                    'idCarrera' : this.idCarrera,
                    'idFacultad' : this.modal_facultad,
                    'nombre' : this.nombre_carrera,
                    'estado' : 1
                }).then(function (response) {
                    me.cerrarModal();
                    //console.log(response);
                    if (response.status == 200) {
                        me.showSuccess = true
                        Swal.fire({
                            icon: 'success',
                            title: 'Carrera actualizada',
                            text: 'La carrera ha sido actualizada exitosamente.',
                        });
                    }
                    me.bindData();                   
                }).catch(function (error) {
                    console.log(error);
                }); 
            }
        },
        abrirModal(modelo, data = [], flag){
            this.loading = 0;
            this.showSuccess = false;
            let facultadJSON = JSON.parse(this.infoFacultad);

            switch (modelo) {
                case "insertar":
                    {
                        this.modal = 1;
                        this.add_edit_flag = 1;
                        this.idCarrera = 0;
                        this.nombre_carrera = '';
                        this.idFacultad = 0;
                        this.facultad = '';
                        this.flagError = false;

                        break;
                    }
                case "editar":
                    {
                        this.modal = 1;
                        this.add_edit_flag = 2;
                        this.idCarrera = data.idCarrera;
                        this.nombre_carrera = data.nombre_carrera;
                        this.idFacultad = data.idFacultad;
                        this.modal_facultad = data.idFacultad;
                        this.facultad = data.nombre_facultad;
                        this.flagError = false;
                        break;
                    }
                case "eliminar":
                    {
                        this.idCarrera = data.idCarrera;
                        this.modal_nombre = data.nombre;
                        this.errorEstado = 0;
                        this.flagErrorEstado = false;
                        Swal.fire({
                            title: `¿Desea desactivar esta carrera?`,
                            text: `Se desactivará esta carrera\n No se podrán inscribir proyectos ni estudiantes con esta carrera.`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Confirmar",
                            cancelButtonText: "Cancelar",
                            }).then((result) => {
                            if (result.isConfirmed) {
                                this.inactivarCarrera(data.idCarrera);
                            }
                        });
                        break;
                    }
                case "activar":
                    {
                        this.idCarrera = data.idCarrera;
                        this.modal_nombre = data.nombre;
                        this.errorEstado = 0;
                        this.flagErrorEstado = false;
                        Swal.fire({
                            title: `¿Desea activar la carrera?`,
                            text: `La carrera se activará y se podrá registrar proyectos y estudiantes con ella`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Confirmar",
                            cancelButtonText: "Cancelar",
                            }).then((result) => {
                            if (result.isConfirmed) {
                                this.activarCarrera(data.idCarrera);
                            }
                        });
                        break;
                    }
                default:
                    break;
            }
        },
        inactivarCarrera(idCarrera) {
            let estado = 0;
            const me = this;

            axios.put(`${API_HOST}/carreras/inactivar`, {
                    'idCarrera' : idCarrera,
                    'estado' : estado
            })
            .then(function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Carrera desactivado',
                    text: 'La carrera se ha desactivado exitosamente',
                });
                me.bindData();
            })
            .catch(error => {
                console.log(error);
            });
        },
        activarCarrera(idCarrera) {
            let estado = 1;
            const me = this;

            axios.put(`${API_HOST}/carreras/inactivar`, {
                    'idCarrera' : idCarrera,
                    'estado' : estado
            })
            .then(function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Carrera activada',
                    text: 'La carrera se ha activada exitosamente',
                });
                me.bindData();
            })
            .catch(error => {
                console.log(error);
            });
        },
        cerrarModal(){
            if(this.modal == 1){
                this.modal = 0;
                this.modal4 = 0;
                this.add_edit_flag = 0;
            }
            else{
                this.add_edit_flag = 0;
                this.modal2 = 0;
                this.modal3 = 0;
                this.modal5 = 0;
                this.modal6 = 0;
                this.modal7 = 0;
                this.idFacultad = 0;
                this.modal_facultad = 0;
                this.showSuccess = false;
            }
        },
        getFacultades() {
            const me = this;
            axios.get(`${API_HOST}/facultad`)
                .then(response => {
                    me.facultades = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        bindData() {
            this.carreras = [];
            this.getFacultades();
            const me = this;
            axios.get(`${API_HOST}/facultad/carreras`)
                .then(response => {
                    me.carreras = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    },
    mounted() {
        this.bindData();
    }
};
</script>


<style scoped>
  /* Estilos generales para mejorar la apariencia de la tabla y los elementos */


  .main {
    display: flex;
    flex-direction: column;
    min-height: 95vh;
}
  
  .container {
    max-width: 90%;
    margin: 0 auto;
  }

  .newCarreerContainer{
    padding: 5vh;
    max-width: 700px;
    margin: 0 auto;
    background-color: #f2f2f2;
    border-radius: 20px;
    
  }

  h1 {
    text-align: center;
  }

  /* Estilos para el modal */
  input, select {
    display: block;
    margin: 10px 0;
    width: 300px;
    
  }

  p{
    font-size: 1rem;
    font-weight: 300;
    color: #666;
  }

  /* Agregar estilos adicionales según sea necesario */
</style>