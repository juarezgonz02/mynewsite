<template>
    <main class="main" style="background-color: white;">
        <div class="container-dashboard">
            <h1 style=" font-size: xx-large; margin-bottom: 10vh; margin-top: 15vh;" >Estadisticas</h1>
            <div style="margin-bottom: 5vh;">
                <h3>Filtrar por</h3>
                <select class="form-control" id="factultad" name="factultad" v-model="idFacultadSeleccionada">
                    <option :value=0>Todas</option>
                    <option v-for="facultad in facultades" :value="facultad.idFacultad">{{ facultad.nombre }}</option>
                    
                </select>
                <select class="form-control" id="carrera" name="carrera">
                    <option :value=0>Todas</option>
                    <option v-for="carrera in carrerasFacultad" :value="carrera.id">{{ carrera.nombre }}</option>
                    
                </select>
            </div>
            <div class="dashboard">
                <div class="statistic">
                    <div class="header">

                        <h3 >Proyectos en la plataforma</h3>
                    </div>
                    <div class="body">
                        <p class="text">{{ projectQuantity }}</p>
                    </div>
                </div>
                <div class="statistic">
                    <div class="header">

                        <h3 >Proyectos Activos</h3>
                    </div>
                    <div class="body">
                        <p class="text">{{ availableProjectsQuantity }}</p>
                    </div>
                </div>
                <div class="statistic">
                    <div class="header">

                        <h3 >Proyectos Finalizados</h3>
                    </div>
                    <div class="body">
                        <p class="text">{{ availableProjectsQuantity }}</p>
                    </div>
                </div>
                <div class="statistic">
                    <div class="header">
                        <h3 >Estudiantes Registrados</h3>
                    </div>
                    <div class="body">
                        <p class="text">{{ registeredStudents }}</p>
                    </div>
                    
                </div>
                <div class="statistic">
                    <div class="header">
                        <h3 >Estudiantes en Proyectos</h3>
                    </div>
                    <div class="body">
                        <p class="text">{{ registeredStudents }}</p>
                    </div>
                </div>
            </div>
            
    </div>
    </main>
</template>

<script>
import {API_HOST} from '../constants/endpoint.js';
export default {
    data() {
        return {
            projectQuantity: 0,
            availableProjectsQuantity: 0,
            registeredStudents: 0,
            facultades: [],
            idFacultadSeleccionada: 0,
            carreras: [],
            carrerasFacultad: [],

        };
    },
    mounted() {
        // Fetch the statistics data from an API or calculate them
        // For example:
        this.projectQuantity = 10;
        this.availableProjectsQuantity = 5;
        this.registeredStudents = 50;
        this.bindData()
    },
    methods:{
        bindData(){
            let me = this
                axios.get(`${API_HOST}/facultad`).then(function (response){
                    console.log(response.data)
                    me.facultades = response.data
                }).catch(function (error){
                    console.log(error)
                })
                axios.get(`${API_HOST}/carrera`).then(function (response){
                    console.log(response.data)
                    me.carreras = response.data
                }).catch(function (error){
                    console.log(error)
                })
        },
        bindCarrerasSeleccionadas(){
            let me = this
            console.log(this.idFacultadSeleccionada)
            this.carrerasFacultad = []
            this.carreras.forEach(function (carrera){
                if(carrera.idFacultad == me.idFacultadSeleccionada){
                    me.carrerasFacultad.push(carrera)
                }
            })

        }
        
    },
    watch:{
        idFacultadSeleccionada: function(){
            this.bindCarrerasSeleccionadas()
        }
    }
};
</script>

<style scoped>
.container-dashboard {
    
    display: flex;
    flex-direction: column;
    padding-bottom: 10vh;
    align-items: center;
    height: 100%;
}
.dashboard {
    display: flex;
    justify-content: space-between;
       
}

.statistic .header{
    background-color: rgb(190, 190, 190);
    min-height: 10vh;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 2px solid gray; 
}

.statistic {
    flex: 1;
    text-align: center;
}

.statistic .text{
    font-size: 1.5rem;
    font-weight: bold;
    color: rgb(0, 0, 0);
}

.statistic .body{
    background-color: rgb(255, 255, 255);
    min-height: 10vh;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid gray;}
</style>
