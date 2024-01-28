<template>
    <main class="main">

        <div class="container">
            <h1>Coordinadores</h1>

            <div v-if="showSuccess" style="color: green; border: 1px solid green; background-color: rgb(242, 255, 242); padding: 10px; font-weight: 400;">
                <p>Coordinador registrado con exito</p>
            </div>
        <table>

            
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
        
        <button @click="showModal">Nuevo Coordinador</button>
        
        <div class="newCoordContainer" v-if="showRegisterModal">
            <h2>Nuevo Coordinador</h2>
            <p v-if="errorMessage" style="color: red; border: 1px solid red; background-color: rgb(255, 242, 242); padding: 10px; font-weight: 400;">{{ errorMessage }}</p>


            <input class="form-control" type="text" v-model="newCoordinador.nombre" placeholder="Nombre" required>
            <input class="form-control" type="text" v-model="newCoordinador.apellido" placeholder="Apellido" required>
            <input class="form-control" type="email" v-model="newCoordinador.correo" placeholder="Correo" required>
            <input class="form-control" type="password" v-model="newCoordinador.contrasena" placeholder="Contraseña" required>
            <input class="form-control" type="password" v-model="confirmPassword" placeholder="Confirmar Contraseña" required>
            <select class="form-control custom-select" v-model="newCoordinador.genero" required >
                <option value="" disabled selected>Seleccione su genero</option>
                <option value="M">Masculino</option>
                <option value="F">Femenito</option>
            </select>
            <p>*Verifica los datos del coordinador, recuerda que una vez registrado no podras realizar ningun tipo de cambio. </p>
            <button @click="registerCoordinador">Registrar</button>
        </div>
        <!-- Confirm modal -->

        

    </div>
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
            if (!regexCorreo.test(email)  ) {
                this.errorMessage = 'El correo no es un correo UCA valido';
                return;
            }


            if ( this.newCoordinador.contrasena != this.confirmPassword ) {
                this.errorMessage = 'Las contraseñas no coinciden';
                return;
            }
            
            console.log(this.newCoordinador)
            
            

            

            let me = this;
            
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
                    //me.coordinadores.push(me.newCoordinador);
                }
                me.bindData();
            })
            .catch(function (error) {
                console.log(error);
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
            let me = this;
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

<style>
  /* Estilos generales para mejorar la apariencia de la tabla y los elementos */
  
  .container {
    padding-top: 5vh;
    max-width: 700px;
    margin: 0 auto;
  }

  .newCoordContainer{
    padding: 5vh;
    max-width: 700px;
    margin: 0 auto;
    background-color: #f2f2f2;
    border-radius: 20px;
    
  }

  h1 {
    text-align: center;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }

  th {
    background-color: #f2f2f2;
  }

  tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  button {
    display: block;
    margin-top: 20px;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    max-width: 200px;
    margin: 10px 0;
    font-size: 1rem;
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