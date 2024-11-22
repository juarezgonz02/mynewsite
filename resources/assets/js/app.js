
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue';
//import Vue from 'vue';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('student-dashboard',require('./components/Estudiante.vue'));
//Vue.component('admin-dashboard', require('./components/Admin.vue'));
Vue.component('recordatorio', require('./components/Recordatorio.vue'));
Vue.component('perfil', require('./components/Perfil.vue'));
Vue.component('historial-proyectos', require('./components/ProyectosHistorial.vue'));
Vue.component('todos-proyectos', require('./components/ProyectosDisponibles.vue'));
Vue.component('mis-proyectos', require('./components/ProyectosAplicados.vue'));
Vue.component('admin-proyectos', require('./components/ProyectosAdmin.vue'));
Vue.component('admin-estudiantes', require('./components/EstudiantesAdmin.vue'));
Vue.component('spinner', require('./components/Spinner.vue'));
Vue.component('table-loader', require('./components/TableLoader.vue'));
Vue.component('estadisticas', require('./components/Estadisticas.vue'));
Vue.component('todas-solicitudes', require('./components/TablaSolicitudes/index.vue'));
Vue.component('carreras', require('./components/Carreras.vue'));
Vue.component('coordinadores', require('./components/Coordinadores.vue'))
Vue.use(VueSweetalert2);


// TODO: Creación de coordinadores, cuando ya existe en la bd, el sitio no puede responder; Estilo para el selector de filtros (llenar cuadro)
// Crear algo nuevo para este documento y probar el documento
const app = new Vue({
    el: '#app',
    data :{
        menu: 0,
        amenu: 0
    }
});
