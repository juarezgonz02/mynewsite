<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb" style="padding-left: 30px;">
                <li class="breadcrumb-item">Inicio</li>
                <li class="breadcrumb-item active">Administración de Estudiantes</li>
            </ol>
            <div class="container-fluid px-4">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Estudiantes
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8" style="margin: 1rem">
                                <div class="form-group">
                                    <input type="text" v-model="carnet" class="form-control inputs" placeholder="Ingrese el carnet del estudiante">
                                    <div v-if="flagError" class="mt-2 text-danger">
                                        No se ha encontrado resultados
                                    </div>
                                    <div v-else class="mt-2" style="display:none;">
                                        Nada
                                    </div>
                                </div>
                                <button type="submit" @click="buscarEstudiante()" class="btn btn-primary">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; align-items: center; ">
                            <div v-if="nombre_completo == ''">
                                <p style="visibility:hidden; margin-bottom:0">Nada</p>
                            </div>
                            <div v-else>
                                <p v-text="nombre_completo" style="margin-bottom:0"></p>
                            </div>
                            <div v-if="nombre_completo != ''">
                                <!-- icon button  -->
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editarEstudiante" style="margin-right: 15px; margin-top: 0px;" @click="toggleEditDisabled(!editDisabled)" >Editar</button>
                            </div>
                        
                    </div>
                    <div class="card-body">
                        <div class="col-md-8" style="width: auto; margin: 1rem;">
                            <div class="form-group row">
                                <label class="form-control-label">Facultad</label>

                                <select :disabled="editDisabled" class="form-control custom-select" id="facultad " v-model="idFacultad">
                                    <option v-for="facultad in arrayFacultad" :value="facultad.idFacultad" :key="facultad.idFacultad">{{facultad.nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label">Carrera</label>
 
                                <select :disabled="editDisabled" class="form-control custom-select" id="carrera" v-model="idCarrera">
                                    <option v-for="carrera in arrayCarreraFact" :value="carrera.idCarrera" :key="carrera.idCarrera">{{carrera.nombre}}</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="perfil" class="form-control-label">Año de carrera</label>

                                <select :disabled="editDisabled" class="form-control custom-select" id="perfil" v-model="idPerfil">
                                    <option v-for="perfil in arrayPerfil" :value="perfil.idPerfil" :key="perfil.idPerfil">{{perfil.perfil}}</option>
                                </select>
                            </div>
                           
                            <div class="form-group row" style="display: flex; flex-direction: column; margin-right: 30px;">
                                <label for="perfil" class="form-control-label" v-if="proyectoInscritoFlag">Proyecto inscrito:</label>
                                <label for="perfil" class="form-control-label" v-if="!proyectoInscritoFlag">No se encuentra inscrito en ningun proyecto</label>
                                <!-- <input type="text" class="form-control" v-if="proyectoInscritoFlag" v-model="proyectoInscrito" style="margin-right: 20px;" readonly> -->
                                 <ul v-if="proyectoInscritoFlag" v-text="proyectoInscrito" style="font-weight: 600;" styles="margin-right: 30px;"></ul>
                            </div>
                                <!-- <div class="form-group row">
                                    <label for="perfil" class="label" style="font-weight: bold;" v-text="proyectoInscrito"></label>
                                </div> -->
                                <div class="form-group row" style="margin-right: 20px;"> 

                                    <div v-if="!proyectoInscritoFlag" id="message" class="alert alert-info" role="alert">
                                        No se encuentra inscrito en ningun proyecto.
                                    </div>
                                    <div v-if="errorActualizar == 1" id="message" class="alert alert-success" role="alert">
                                        Estudiante actualizado correctamente
                                    </div>
                                    <div v-else-if="errorActualizar == 2" id="message" class="alert alert-danger" role="alert">
                                        Busque un estudiante
                                    </div>
                                    <div v-else-if="errorActualizar == 3" id="message" class="alert alert-danger" role="alert">
                                        Seleccione una carrera
                                    </div>
                                    <div v-else style="visibility:hidden;"  class="alert row" role="alert">.</div>
                                    </div>
                              </div>
                               <!-- Hide timeout feature -->
                              <div v-if="timeout" hide>

                                <div class="form-group row">
                                    <label for="perfil" class="form-control-label">Penalizado hasta:</label>
                                </div>
                                <div class="form-group row" style="align-items: center;">
                                    <label for="perfil" class="form-control-label" style="font-weight: bold; color: red; margin-left: 20px; font-size: medium; margin-right: 30px;" v-text="timeout"></label>
                                    <button type="button" class="btn btn-warning" style="margin-right: 15px;" @click ="removerTimeOut()">Remover penalización</button>
                                    <label for="perfil" class="form-control-label">*Al dar click en "Remover penalización" el estudiante podra volver a aplicar a otros proyectos inmediatamente</label>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    <div class="card-footer">
                        <button :disabled="editDisabled" type="button" class="btn btn-primary" @click ="actualizarEstudiante()">Confirmar</button>
                    </div>
                </div>
            </div>
            <footer class="app-footer" id="footer" style="display: flex; flex-direction: column; justify-content: center; font-size: 15px; padding: 10px 0px">
                <span><a target="_blank" href="http://www.uca.edu.sv/servicio-social/">Centro de Servicio Social | UCA</a> &copy; 2024</span>
                <span>Desarrollado por <a href="#"></a>Grupo de Horas Sociales</span>
            </footer>
        </main>
</template>

<script>
import {API_HOST} from '../constants/endpoint.js';
    export default {
        data(){
            return{
                carnet : '',
                user_email: '',
                nombres : '',
                apellidos : '',
                idCarrera : 0,
                idCarreraAux: 0,
                idFacultad : 0,
                idFacultadAux: 0,
                idPerfil : 0,
                idPerfilAux: 0,
                nombre_completo : '',
                arrayFacultad : [],
                arrayCarrera : [],
                arrayCarreraFact : [],
                arrayPerfil : [],
                errorActualizar : false,
                flagError : false,
                timeout : '',
                idUser : 0,
                editDisabled : true,
                proyectoInscrito : '',
                proyectoInscritoFlag : true
                
            }
        },
        methods:{
            bindData(){
                const me = this
                axios.get(`${API_HOST}/facultad`).then(function (response){
                    me.arrayFacultad = response.data
                })
                axios.get(`${API_HOST}/carrera`).then(function (response) {
                    me.arrayCarrera = response.data;
                    // me.getCarreras(false)
                })
                axios.get(`${API_HOST}/perfil`).then(function (response) {
                    me.arrayPerfil = response.data;
                })
                axios.get(`${API_HOST}/get_user`).then(function (response) {
                    me.user_email = response.data.correo;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            buscarEstudiante(){
                const me = this
                this.errorActualizar = false
                var url = `${API_HOST}/estudiante/carnet`
                axios.get(url, {
                    params:{
                        carnet: me.carnet
                    }
                }).then(function (response) {
                    var estudiante = response.data[0];
                    if(estudiante != null){
                        me.flagError = false
                        var carrera = response.data[1];
                        me.nombres = estudiante.nombres;
                        me.apellidos = estudiante.apellidos;
                        me.idPerfil = estudiante.idPerfil;
                        me.idPerfilAux = estudiante.idPerfil;
                        me.nombre_completo = estudiante.nombres + " " + estudiante.apellidos;
                        me.idCarrera = estudiante.idCarrera;  
                        me.idCarreraAux = estudiante.idCarrera;  
                        me.idFacultad = carrera.idFacultad;
                        me.idFacultadAux = carrera.idFacultad;
                        me.timeout = me.fechaLegible(estudiante.timeout);
                        me.idUser = estudiante.idUser;
                        me.getUserProyectos()
                    }
                    else me.flagError = true
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            fechaLegible(fecha){
                if (fecha == null || fecha == "") return null;
                var date = new Date(fecha);
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                return day + "/" + month + "/" + year;
            },
            getCarreras(flag){
                const me = this
                if(flag) this.idCarrera = 0
                else this.idCarrera = this.idCarreraAux
                this.arrayCarreraFact = []
                this.arrayCarrera.forEach(function(element){
                    if(element.idFacultad == me.idFacultad) me.arrayCarreraFact.push(element)
                })
                    //console.log("Exec get carreras", flag)
                    //console.log(this.idCarrera)
            },
            async actualizarEstudiante(){
                const me = this
                if(this.carnet == '' && this.idPerfil == 0){
                    this.errorActualizar = 2
                    return;
                }
                if(this.idCarrera == this.idCarreraAux && this.idPerfil == this.idPerfilAux){
                    this.errorActualizar = 1
                    return;
                }
                try{

                    if(this.idCarrera != null && this.idCarrera != 0){

                        const response = await axios.put(`${API_HOST}/estudiante/actualizar`, {
                            'carnet' : this.carnet,
                            'idCarrera' : this.idCarrera,
                            'idPerfil' : this.idPerfil
                        })

                        // .then(function (response) {
                            me.errorActualizar = 1
                            //console.log(response)
                            if(response.status == 200)
                            {
                                 this.$swal.fire({
                                     title: 'Estudiante actualizado',
                                     text: 'El estudiante ha sido actualizado correctamente',
                                     icon: 'success',
                                     timer: 2000
                                    });
                                    me.idCarreraAux = me.idCarrera
                                    me.idFacultadAux = me.idFacultad
                                    me.idPerfilAux = me.idPerfil
                                }
                            }
                        
                            me.toggleEditDisabled(true);
                            me.buscarEstudiante()
                }
                catch(e) {
                        this.$swal.fire({
                            title: 'Error',
                                    text: 'Ha ocurrido un error al actualizar el estudiante',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                })
                            console.log(error);
                            this.errorActualizar = 3
                }
                        
            },
            removerTimeOut(){
                const me = this
                axios.patch(`${API_HOST}/estudiante/${me.idUser}/remover-timeout`, {
                }).then(function (response) {
                    me.errorActualizar = 1
                    me.buscarEstudiante()
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getUserProyectos(){
                const me = this
                axios.get(`${API_HOST}/estudiante/${me.idUser}/proyectos`, {
                }).then(function (response) {
                    
                    if (response.data[0].nombre == null)
                    {
                        me.proyectoInscritoFlag = false
                        me.proyectoInscrito = ''
                        
                        return
                    } 
                    else me.proyectoInscritoFlag = true
                    me.proyectoInscrito = response.data[0].nombre
                    //console.log(response.data)
                })
                .catch(function (error) {
                    console.log(error);
                    me.proyectoInscritoFlag = false
                        me.proyectoInscrito = ''
                });
            },
            cerrarModal(){
                
            },
            toggleEditDisabled(disabled){
                if(disabled){
                    this.idCarrera = this.idCarreraAux
                    this.idFacultad = this.idFacultadAux
                    this.idPerfil = this.idPerfilAux
                }
                    this.editDisabled = disabled
            },
            abrirModal(modelo, data = []){
                switch (modelo) {
                    case "editar":
                        {
                            
                        }
                    case "aplicar":
                        {
                            
                            break;
                        }
                    default:
                        break;
                }
            },
            logout(){
                var url = `${API_HOST}/logout`;
                axios.post(url).then(() => location.href = `${API_HOST}/`)
            }
        },
        watch:{
            idFacultad:function(val){
                //console.log("Facultad", val)
                this.getCarreras(val != this.idFacultadAux);
            }
        },
        mounted(){
            this.bindData()
        }
    }
</script>
<style lang="scss">



.main{
    font-family: "Abel", sans-serif;
    background-color: white;
}


.modal-primary .modal-header{
    background-color: #003C71;
    display: flex;
    flex-flow: row;
    justify-content: space-between;
}

.button-container{
    margin: 0em 0.25em;
    display: flex;
    justify-content: center;
}

.btn {
    border-radius: 6px;
}

.error{
    color: red;
    margin: 0.25em;
    margin-bottom: 0.5em;
}
.show{
    visibility: visible;
}
.hide{
    visibility: hidden;
}
.div-form{
    margin-bottom: 0em;
}
.modal-primary .modal-content{
    width: 100%;
    max-width: none;
}

.modal-student{
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

@media screen and (max-width: 450px) {
    .btn-label {
        display: none;
    }
}
/* Ponelo por aqui lo del sidebar */
@media screen and (max-width: 500px) {
    .btn-label {
        display: none;
    }
}

@media screen and (max-width: 991px) {

    #sidebarMenu {
        margin-top: 55px;
    }
    
    #logout {
        margin-right: 30px;
    }

    .main{
        overflow: scroll;
    }
    
}

@media screen and (min-width: 991px) {
    #logout {
        margin-right: 30px;
    }
    .inputs{
        width: 100%;
    }
}

@media screen and (min-width: 760px) {
    .inputs{
        width: 80%;
    }
}
@media screen and (max-width: 760px) {
    .inputs{
        width: 70%;
    }
}

</style>