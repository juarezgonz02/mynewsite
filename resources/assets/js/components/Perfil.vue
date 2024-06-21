<template>
    <main class="main" style="background-color: white;">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Inicio</li>
            <li class="breadcrumb-item active">Perfil</li>
        </ol>
        <div class="container-fluid px-4">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Perfil
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped table-sm">
                        <tr>
                            <th style="background-color: #dedede; width: 10%;">Correo Institucional</th>
                            <td v-text="correo"></td>
                        </tr>
                        <tr>
                            <th style="background-color: #dedede">Carnet</th>
                            <td v-text="carnet"></td>
                        </tr>
                        <tr>
                            <th style="background-color: #dedede">Nombres</th>
                            <td v-text="nombres"></td>
                        </tr>
                        <tr>
                            <th style="background-color: #dedede">Apellidos</th>
                            <td v-text="apellidos"></td>
                        </tr>
                        <tr>
                            <th style="background-color: #dedede">Facultad</th>
                            <td v-text="facultad"></td>
                        </tr>
                        <tr>
                            <th style="background-color: #dedede">Carrera</th>
                            <td class="font-weight-bold" v-text="carrera"></td>

                        </tr>
                        <tr>
                            <th style="background-color: #dedede">Perfil</th>
                            <td class="font-weight-bold" v-text="perfil"></td>

                        </tr>
                    </table>
                    <div class="editButtosContainer">
                        <button class="button button1 w-[8]" id="editGradeButton" data-toggle="modal"
                            data-target="#editGrade">Cambiar año de carrera</button>

                        <button class="button button1 w-[8]" id="editCareerButton" data-toggle="modal"
                            data-target="#editCareer">Cambiar carrera</button>
                    </div>
                </div>





            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="editGrade">
            <div class="modal-dialog modal-primary" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cambiar año de carrera</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="sel1">Seleccione el año de carrera:</label>

                                <select class="form-control" id="sel1" v-model="newPerfil">
                                    <!-- <option v-for="perfil in arrayPerfiles" v-text="perfil.anio_carrera"></option> -->
                                    <option v-for="p in arrayPerfiles" :value="p.idPerfil" :key="p.idPerfil"
                                        :selected='p.idPerfil == 1'>{{ p.perfil }}</option>
                                </select>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                            @click="changePerfil">Guardar</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" role="dialog" aria-hidden="true" id="editCareer">
            <div class="modal-dialog modal-primary" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cambiar carrera</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="sel1">Seleccione la carrera:</label>
                                <select class="form-control" id="sel1" v-model="newCarrera">
                                    <option v-for="carrera in carreras" v-text="carrera.nombre"
                                        :value="carrera.idCarrera"></option>
                                </select>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"
                                @click="changeCareer">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
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
import { API_HOST } from '../constants/endpoint.js';
export default {
    data() {
        return {
            user_id: 0,
            user_email: '',
            correo: '',
            carnet: 0,
            nombres: '',
            apellidos: '',
            carrera: '',
            facultad: '',
            perfil: '',
            arrayPerfiles: [''],
            newPerfil: 0,
            idPerfil: 0,
            carreras: [],
            newCarrera: 0
        }
    },
    methods: {
        bindData() {
            const me = this
            axios.get(`${API_HOST}/get_user`).then(function (response) {
                me.user_id = response.data.idUser;
                me.correo = response.data.correo;
                var splitString = me.correo.split(/@/);
                me.carnet = splitString[0];
                me.nombres = response.data.nombres;
                me.apellidos = response.data.apellidos;
            })
                .catch(function (error) {
                    console.log(error);
                });

            axios.get(`${API_HOST}/estudiante`).then(function (response) {
                var res = response.data[0];
                me.carrera = res.nombre_c;
                me.facultad = res.nombre_f;
                me.perfil = res.anio_carrera;
                me.idPerfil = res.idPerfil;
                me.newPerfil = res.idPerfil;
                me.newCarrera = res.idCarrera;
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

            axios.get(`${API_HOST}/perfil`).then(function (response) {
                me.arrayPerfiles = response.data;
            })
                .catch(function (error) {
                    console.log(error);
                });

            axios.get(`${API_HOST}/carrera`).then(function (response) {
                me.carreras = response.data;
            }).catch(function (error) {
                console.log(error);
            });
        },
        logout() {
            var url = `${API_HOST}/logout`;
            axios.post(url).then(() => location.href = `${API_HOST}/`)
        },
        showEditGradeModal() {
            var modal = document.getElementById("editGrade");
            modal.style.display = "block";
            this.newPerfil = this.idPerfil;

        },
        showEditCareerModal() {
            var modal = document.getElementById("editCareer");
            modal.style.display = "block";
        },
        closeEditGrade() {
            var modal = document.getElementById("editGrade");
            modal.style.display = "none";
        },
        closeEditCareer() {
            var modal = document.getElementById("editCareer");
            modal.style.display = "none";
        },
        async changePerfil() {
            if (this.newPerfil == this.idPerfil)
                return;
            var me = this;
            try {
                const response = await axios.put(`${API_HOST}/estudiante/actualizar/perfil`, {
                    idPerfil: me.newPerfil,
                    idUsuario: me.user_id
                })
                // .then(function (response) {
                me.bindData();
                if (response.status == 200)
                    this.$swal.fire({
                        icon: 'success',
                        title: 'Perfil actualizado',
                        showConfirmButton: false,
                        timer: 1500
                    })

                else
                    this.$swal.fire({
                        icon: 'error',
                        title: 'Error al actualizar el perfil',
                        showConfirmButton: false,
                        timer: 1500
                    })
                me.closeEditGrade();

            }
            catch (error) {
                console.log(error);
                this.$swal.fire({
                    icon: 'error',
                    title: 'Error al actualizar el perfil',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        },
        async changeCareer() {
            var me = this;
            try {

                const response = await axios.put(`${API_HOST}/estudiante/actualizar/carrera`, {
                    idCarrera: me.newCarrera,
                    idUsuario: me.user_id
                })
                // .then(function (response) {
                me.bindData()
                if (response.status == 200)
                    this.$swal.fire({
                        icon: 'success',
                        title: 'Carrera actualizada',
                        showConfirmButton: false,
                        timer: 1500
                    })

                else
                    this.$swal.fire({
                        icon: 'error',
                        title: 'Error al actualizar la carrera',
                        showConfirmButton: false,
                        timer: 1500
                    })
                me.closeEditCareer();

            }
            catch (error) {
                console.log(error);
                this.$swal.fire({
                    icon: 'error',
                    title: 'Error al actualizar la carrera',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        }

    },
    mounted() {
        this.bindData();
    }
}
</script>

<style>
.main {
    font-family: "Abel", sans-serif;
}

#footer {
    margin-left: 0px;
}

.button {
    border: none;
    color: white;
    padding: 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    flex: 1;
    border-radius: 6px;
}

.button1 {
    background-color: #008CBA;
}

.button2 {
    background-color: #008CBA;
}

.editButtosContainer {
    display: flex;
    margin-top: 20px;
    width: 50%;
}

th,
td {
    padding: 10px;
}


@media screen and (min-width: 991px) {
    #logout {
        margin-right: 30px;
    }
}

.sidebar-fixed .sidebar {
    height: 100%;
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
</style>