<template>
    <main class="main">
        <ol class="breadcrumb" style="padding-left: 30px;">
                <li class="breadcrumb-item">Inicio</li>
                <li class="breadcrumb-item active">Administración de Coordinadores</li>
            </ol>
        <div class="container container-fluid">

            <div v-if="showSuccess"
                style="color: green; border: 1px solid green; background-color: rgb(242, 255, 242); padding: 10px; font-weight: 400;">
                <p>Coordinador registrado con exito</p>
            </div>
            <table class="table table-bordered table-hover table-sm">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="coordinador in coordinadores" :key="coordinador.id">
                        <td>{{ coordinador.nombres + ' ' + coordinador.apellidos }}</td>
                        <td>{{ coordinador.correo }}</td>
                    </tr>
                </tbody>
            </table>

            <button class="btn btn-success mb-3" @click="showModal">Nuevo Coordinador</button>

            <div class="newCoordContainer" v-if="showRegisterModal">
                <h3>Nuevo Coordinador</h3>
                <p v-if="errorMessage"
                    style="color: red; border: 1px solid red; background-color: rgb(255, 242, 242); padding: 10px; font-weight: 400;">
                    {{ errorMessage }}</p>


                <input class="form-control" type="text" v-model="newCoordinador.nombre" placeholder="Nombre" required>
                <input class="form-control" type="text" v-model="newCoordinador.apellido" placeholder="Apellido"
                    required>
                <input class="form-control" type="email" v-model="newCoordinador.correo" placeholder="Correo" required>
                <input class="form-control" type="password" v-model="newCoordinador.contrasena" placeholder="Contraseña"
                    required>
                <input class="form-control" type="password" v-model="confirmPassword" placeholder="Confirmar Contraseña"
                    required>
                <select class="form-control custom-select" v-model="newCoordinador.genero" required>
                    <option value="" disabled selected>Seleccione su genero</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenito</option>
                </select>
                <p>*Verifica los datos del coordinador, recuerda que una vez registrado no podras realizar ningun tipo
                    de cambio. </p>
                <button class="btn btn-success" @click="registerCoordinador">Registrar</button>
            </div>
            <!-- Confirm modal -->



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
export default {
    data() {
        return {
            coordinadores: [],
            showRegisterModal: false,
            newCoordinador: {
                nombre: '',
                apellido: '',
                correo: '',
                contrasena: '',
                genero: ''
            },
            errorMessage: '',
            confirmPassword: '',
            showSuccess: false
        };
    },
    methods: {
        showModal() {
            this.showRegisterModal = !this.showRegisterModal;
        },
        registerCoordinador() {
            this.errorMessage = '';
            if (this.newCoordinador.nombre == '') {
                // alert('El nombre es requerido');
                this.errorMessage = 'El nombre es requerido';

                return;
            }

            if (this.newCoordinador.apellido == '') {
                // alert('El apellido es requerido');
                this.errorMessage = 'El apellido es requerido';
                return;
            }

            if (this.newCoordinador.correo == '') {
                // alert('El correo es requerido');
                this.errorMessage = 'El correo es requerido';
                return;
            }

            if (this.newCoordinador.contrasena == '') {
                // alert('La contraseña es requerida');
                this.errorMessage = 'La contraseña es requerida';
                return;
            }

            if (this.newCoordinador.genero == '') {
                // alert('El genero es requerido');
                this.errorMessage = 'El genero es requerido';
                return;
            }

            // validate email format with regex includes domain @uca.edu.sv

            var email = this.newCoordinador.correo;
            var regexCorreo = /^[a-zA-Z0-9._-]+@uca\.edu\.sv$/;
            if (!regexCorreo.test(email)) {
                this.errorMessage = 'El correo no es un correo UCA valido';
                return;
            }

            var passregex = /^(?=.*[0-9])(?=.*[- ?!@#$%^&*\/\\])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9- ?!@#$%^&*\/\\]{8,30}$/

            if (!passregex.test(this.newCoordinador.contrasena)) {
                this.errorMessage = 'La contraseña debe tener almenos 8 caracteres, una mayuscula, un simbolo especial y un número';
                return;
            }

            if (this.newCoordinador.contrasena != this.confirmPassword) {
                this.errorMessage = 'Las contraseñas no coinciden';
                return;
            }


            console.log(this.newCoordinador)

            const me = this;

            // /users/admin/new
            axios.post(`${API_HOST}/users/admin/new`, {
                nombre: this.newCoordinador.nombre,
                apellido: this.newCoordinador.apellido,
                correo: this.newCoordinador.correo,
                contrasena: this.newCoordinador.contrasena,
                genero: this.newCoordinador.genero

            })
                .then(function (response) {
                    // console.log(response.status);
                    if (response.status == 200) {
                        me.showSuccess = true
                    }
                    me.bindData();
                })
                .catch(function (error) {
                    console.log(error.response);
                    this.errorMessage = error.response
                });




            // this.coordinadores.push(this.newCoordinador);
            this.showRegisterModal = false;
            this.newCoordinador = {
                nombre: '',
                apellido: '',
                correo: '',
                contrasena: '',
                genero: ''
            };
            this.confirmPassword = '';


        },
        bindData() {
            this.coordinadores = [];
            // console.log('bind data')
            const me = this;
            axios.get(`${API_HOST}/users/admin/all`)
                .then(response => {
                    me.coordinadores = response.data.users;
                    // console.log(response.data.users);
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

.newCoordContainer {
    padding: 5vh;
    margin: 1em auto;
    background-color: #f2f2f2;
    border-radius: 20px;

}

h1 {
    text-align: center;
}

/* 
button {
    display: block;
    margin-top: 20px;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 6px;
    max-width: 200px;
    margin: 10px 0;
    font-size: 1rem;
} */


/* Estilos para el modal */
input,
select {
    display: block;
    margin: 10px 0;
    width: 300px;

}

p {
    font-size: 1rem;
    font-weight: 300;
    color: #666;
}

.container-fluid {
    flex: 1;
}
/* Agregar estilos adicionales según sea necesario */
</style>