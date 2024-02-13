<template>
    <main class="main" style="background-color: white;">
        <div class="container-dashboard">
            <h1 style=" font-size: xx-large; margin-bottom: 10vh; margin-top: 8vh; font-weight: bold;" >Estadisticas Centro de Servicio Social</h1>
            <div style="margin-bottom: 5vh; display: flex; flex-direction: column; align-items: center; gap: 20px;">
                <h3>Aplicar Filtros</h3>
                <div class="contenedor-flex">
                    <label for="factultad" style="margin-right: 2vw; font-size: 1.1rem ;">Facultad</label>
                    <select class="form-control" id="factultad" name="factultad" v-model="idFacultadSeleccionada">
                        <option :value=0>Todas</option>
                        <option v-for="facultad in facultades" :value="facultad.idFacultad">{{ facultad.nombre }}</option>
                        
                    </select>

                    <label for="carrera" style="margin-right: 2vw; margin-left: 2vw; font-size: 1.1rem">Carrera</label>
                    <select class="form-control" id="carrera" name="carrera" v-model="idCarreraSeleccionada">
                        <option :value=0>Todas</option>
                        <option v-for="carrera in carrerasFacultad" :value="carrera.idCarrera">{{ carrera.nombre }}</option>
                        
                    </select>
                </div>
            </div>
            <div class="dashboard">
                <div class="statistic">
                    <div class="header">
                        <h3 >Proyectos en la Plataforma</h3>
                    </div>
                    <div class="body">
                        <p class="text">{{ totalOfProjects }}</p>
                    </div>
                </div>
                <div class="statistic">
                    <div class="header">

                        <h3 >Proyectos en Curso</h3>
                    </div>
                    <div class="body">
                        <p class="text">{{ activeProjects }}</p>
                    </div>
                </div>
                <div class="statistic">
                    <div class="header">

                        <h3 >Proyectos Finalizados</h3>
                    </div>
                    <div class="body">
                        <p class="text">{{ projectsEnded }}</p>
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
                        <p class="text">{{ studentsOnProjects }}</p>
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
            registeredStudents: 0,
            studentsOnProjects: 0,
            activeProjects: 0,
            totalOfProjects: 0,
            facultades: [],
            idFacultadSeleccionada: 0,
            carreras: [],
            carrerasFacultad: [],
            idCarreraSeleccionada: 0,
            facultadSeleccionada: {},
            projectsEnded: 0,
            carreraSeleccionada: {}

        };
    },
    mounted() {
        // Fetch the statistics data from an API or calculate them
        // For example:
        this.projectQuantity = -1;
        this.activeProjects = -1;
        this.registeredStudents = -1;
        this.studentsOnProjects = -1;
        this.totalOfProjects = -1;

        this.bindData()
    },
    methods:{
        bindData(){
            let me = this
                axios.get(`${API_HOST}/facultad`).then(function (response){
                    // console.log(response.data)
                    me.facultades = response.data
                }).catch(function (error){
                    // console.log(error)
                })
                axios.get(`${API_HOST}/carrera`).then(function (response){
                    // console.log(response.data)
                    me.carreras = response.data
                }).catch(function (error){
                    console.log(error)
                })
                me.bindEstadisticas()

                
        },
        bindCarrerasSeleccionadas(){
            let me = this
            // console.log(this.idFacultadSeleccionada)
            this.carrerasFacultad = []
            this.idCarreraSeleccionada = 0
            // if(this.idFacultadSeleccionada == 0){
            //     return
            // }
            this.carreras.forEach(function (carrera){
                if(carrera.idFacultad == me.idFacultadSeleccionada){
                    me.carrerasFacultad.push(carrera)
                }
            })

        },
        // bindFacultadSeleccionada(){
        //     let me = this
        //     console.log(this.idCarreraSeleccionada)
        //     this.facultades.forEach(function (facultad){
        //         if(facultad.idFacultad == me.idFacultadSeleccionada){
        //             me.facultadSeleccionada = facultad
        //         }
        //     })
        // },
        bindEstadisticas(){
            let me = this
            axios.get(`${API_HOST}/estadisticas`,
                {
                    params:{
                        idFacultad: me.idFacultadSeleccionada,
                        idCarrera: me.idCarreraSeleccionada
                    }
                }
                ).then(function (response){
                    // console.log(response.data)
                    me.registeredStudents = response.data.numeroDeEstudiantes
                    me.studentsOnProjects = response.data.numeroDeEstudiantesInscritos
                    me.activeProjects = response.data.numeroDeProyectosEnCurso
                    me.projectsEnded = response.data.numeroDeProyectosTerminados
                    me.totalOfProjects = response.data.totalNumeroDeProyectosActivos
                }).catch(function (error){
                    console.log(error)
                })
        }
        
    },
    watch:{
        idFacultadSeleccionada: function(){
            this.bindCarrerasSeleccionadas()
            this.bindEstadisticas()
        },
        idCarreraSeleccionada: function(){
            this.bindEstadisticas()
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
    background-color: rgb(163, 199, 253);
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

@media screen and (max-width: 768px){
    .statistic{
        margin-bottom: 1vh;
    }

    .dashboard{
        flex-direction: column;
        gap: 0px;
    }
}

.statistic .text{
    font-size: 1.8rem;
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

.contenedor-flex {
    display: flex;
    align-items: baseline;
}
    
</style>
