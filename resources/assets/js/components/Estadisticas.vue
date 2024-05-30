<template>
    <main class="main" style="background-color: white;">
        <ol class="breadcrumb" style="padding-left: 30px;">
                <li class="breadcrumb-item">Inicio</li>
                <li class="breadcrumb-item active">Estadisticas</li>
            </ol>
        <div class="container-dashboard">
            <h1 style=" font-size: xx-large; margin-bottom: 2vh; margin-top: 5vh; font-weight: bold;">Estadísticas
                Centro de Servicio Social</h1>
            <div
                style="margin-bottom: 5vh; display: flex; flex-direction: column; align-items: center; gap: 20px; width: 100%;">
                <h4>Aplicar Filtros</h4>
                <div class="contenedor-flex">
                    <label for="factultad" style="margin-right: 2vw; font-size: 1.6rem ;">Facultad</label>
                    <select class="form-control" id="factultad" name="factultad" v-model="idFacultadSeleccionada">
                        <option :value=0>Todas</option>
                        <option v-for="facultad in facultades" :value="facultad.idFacultad">{{ facultad.nombre }}
                        </option>

                    </select>

                    <label for="carrera" style="margin-right: 2vw; margin-left: 2vw; font-size: 1.6rem">Carrera</label>
                    <select class="form-control" id="carrera" name="carrera" v-model="idCarreraSeleccionada">
                        <option :value=0>Todas</option>
                        <option v-for="carrera in carrerasFacultad" :value="carrera.idCarrera">{{ carrera.nombre }}
                        </option>

                    </select>
                </div>
            </div>
            <div class="dashboard">
                <div class="statistic">
                    <div class="header">
                        <h3>Proyectos en la Plataforma</h3>
                        <!-- <img src="../../img/icons/person.svg" alt="Icon" width="32px" height="32px" /> -->
                        <img :src="ruta + '/img/icons/user-shield-alt-svgrepo-com.svg'" alt="chevron-left" width="32px"
                            height="32px">

                    </div>
                    <div class="body">
                        <p class="text">{{ totalOfProjects }}</p>
                    </div>
                </div>
                <div class="statistic">
                    <div class="header">

                        <h3>Proyectos en Curso</h3>
                        <img :src="ruta + '/img/icons/clipboard-list-alt-svgrepo-com.svg'" alt="chevron-left"
                            width="32px" height="32px">
                    </div>
                    <div class="body">
                        <p class="text">{{ activeProjects }}</p>
                    </div>
                </div>
                <div class="statistic">
                    <div class="header">

                        <h3>Proyectos Finalizados</h3>
                        <img :src="ruta + '/img/icons/shield-check-svgrepo-com.svg'" alt="chevron-left" width="32px"
                            height="32px">
                    </div>

                    <div class="body">
                        <p class="text">{{ projectsEnded }}</p>
                    </div>
                </div>
                <div class="statistic">
                    <div class="header">
                        <h3>Estudiantes Registrados</h3>
                        <img :src="ruta + '/img/icons/user-check-svgrepo-com.svg'" alt="chevron-left" width="32px"
                            height="32px">

                    </div>
                    <div class="body">
                        <p class="text">{{ registeredStudents }}</p>
                    </div>

                </div>
                <div class="statistic">
                    <div class="header">
                        <h3>Estudiantes en Proyectos</h3>
                        <img :src="ruta + '/img/icons/user-shield-alt-svgrepo-com.svg'" alt="chevron-left" width="32px"
                            height="32px">

                    </div>
                    <div class="body">
                        <p class="text">{{ studentsOnProjects }}</p>
                    </div>
                </div>
                <div class="statistic">
                    <div class="header">

                        <h3>Proyectos Cancelados</h3>
                        <img :src="ruta + '/img/icons/shield-check-svgrepo-com.svg'" alt="chevron-left" width="32px"
                            height="32px">
                    </div>

                    <div class="body">
                        <p class="text">{{ projectsCanceled }}</p>
                    </div>
                </div>


            </div>
            
                <div class="dashboard-2">
                    <div class="dashboard-content">
                        <projects-by-year :data="proyectosXanio" :years="aniosProyectosRegistrados"></projects-by-year>
                    </div>
                    <div class="dashboard-content">
                        <users-by-year :data="estudiantesXanio" :years="aniosEstudiantesRegistrados"></users-by-year>
                    </div>
                    <div class="dashboard-content">
                        <students-by-career :students-by-career="studentsData.studentsByCareer"></students-by-career>
                    </div>
                    <div class="dashboard-content">
                        <genders :male-total="male" :female-total="female" :filtro="filterAppliedName"></genders>
                    </div>
                </div>


                <div class="container d-flex align-items-center flex-column">
                    <h3>Año de registro</h3>
                    <select class="form-control font-lg w-50" id="year" name="year" v-model="yearSelected">
                        <option v-for="year in years" :value="year">{{ year }}</option>
                    </select>
                </div>
                <div class="dashboard-2">
                
                    

                    <div class="dashboard-content">
                        <!-- <apex-chart :chart-type="type" :chart-options="options" :chart-series="series"></apex-chart> -->
                        <projects-registered :data="proyectosRegistradosPorMes" :year="yearSelected.toString()"></projects-registered>
                    </div>
                    <div class="dashboard-content">
                        <users-registered :months="studentsData.months" :data="studentsData.data" :year="yearSelected.toString()"></users-registered>
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
import { API_HOST_ASSETS } from '../constants/endpoint.js';
// import VueApexCharts from 'vue-apexcharts';
import Genders from './graphs/Genders'
import UsersRegistered from './graphs/UsersRegistered.vue'
import StudentsByCareer from './graphs/StudentsByCareer.vue'
import ApexChart from './graphs/ApexChart.vue';
import ProjectsRegistered from './graphs/ProjectsRegistered.vue';
import ProjectsByYear from './graphs/ProjectsByYear.vue';
import UsersByYear from './graphs/UsersByYear.vue';

export default {

    components: {
        // apexchart: VueApexCharts, 
        ApexChart,
        Genders,
        UsersRegistered,
        StudentsByCareer,
        ProjectsRegistered,
        ProjectsByYear,
        UsersByYear
    },
    data() {
        return {
            ruta: API_HOST,
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
            projectsCanceled: 0,
            proyectosRegistradosPorMes: [],
            carreraSeleccionada: {},
            filterAppliedName: "Todas las carreras",
            studentsData: {
                male: 0,
                female: 0,
                months: [],
                data: [],
                studentsByCareer: []
            },
            years: [],
            yearSelected: 2023,
            proyectosRegistradosPorAnio: {},
            aniosProyectosRegistrados: [],
            aniosEstudiantesRegistrados: [],
            proyectosXanio: [],
            estudiantesXanio: [],
            
            // ApexChart settings
            type: 'line',
            options: {
                chart: {
                    id: 'vuechart-example'
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']
                }
            },
            series: [{
                name: 'series-1',
                data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
            }],

            male: 0,
            female: 0



        };
    },
    mounted() {
        // Fetch the statistics data from an API or calculate them
        // For example:
        this.projectQuantity = 0;
        this.activeProjects = 0;
        this.registeredStudents = 0;
        this.studentsOnProjects = 0;
        this.totalOfProjects = 0;

        this.bindData()

        this.bindDashboardData()
        let datimeNow = new Date()
        let yearNow = datimeNow.getFullYear()

        // for (let i = 2023; i <= yearNow; i++) {
        //     this.years.push(i);
        // }

        // this.years = ["2022", "2023", "2024"]

    },
    methods: {
        bindData() {
            let me = this
            axios.get(`${API_HOST}/facultad`).then(function (response) {
                // console.log(response.data)
                me.facultades = response.data
            }).catch(function (error) {
                // console.log(error)
            })
            axios.get(`${API_HOST}/carrera`).then(function (response) {
                // console.log(response.data)
                me.carreras = response.data
            }).catch(function (error) {
                console.log(error)
            })
            me.bindEstadisticas()


        },
        bindCarrerasSeleccionadas() {
            let me = this
            // console.log(this.idFacultadSeleccionada)
            this.carrerasFacultad = []
            this.idCarreraSeleccionada = 0
            // if(this.idFacultadSeleccionada == 0){
            //     return
            // }
            this.carreras.forEach(function (carrera) {
                if (carrera.idFacultad == me.idFacultadSeleccionada) {
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
        bindEstadisticas() {
            let me = this
            axios.get(`${API_HOST}/estadisticas`,
                {
                    params: {
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
                    me.male = response.data.estudiantes.masculinos
                    me.female = response.data.estudiantes.femeninos
                    me.projectsCanceled = response.data.numeroDeProyectosCancelados || 0
                }).catch(function (error){
                    console.log(error)
                })
        },
        bindDashboardData() {
            let me = this;
            axios.get(`${API_HOST}/estadisticas/dashboard`, {
                params: {
                    idFacultad: me.idFacultadSeleccionada,
                    idCarrera: me.idCarreraSeleccionada,
                    year: me.yearSelected || 2020
                }
            }).then(function (response){
                
                me.studentsData.months = response.data.meses
                me.studentsData.data = response.data.estudiantesRegistradosPorMes
                me.studentsData.studentsByCareer = response.data.estudiantesPorCarrera
                me.proyectosRegistradosPorMes = response.data.proyectosRegistradosPorMes
                // me.proyectosRegistradosPorAnio = response.data.proyectosRegistradosPorAnio


                me.aniosProyectosRegistrados = Object.keys(response.data.proyectosRegistradosPorAnio)
                me.aniosEstudiantesRegistrados = Object.keys(response.data.estudiantesRegistradosPorAnio)
                me.years = me.aniosProyectosRegistrados
                me.proyectosXanio = Object.values(response.data.proyectosRegistradosPorAnio)
                me.estudiantesXanio = Object.values(response.data.estudiantesRegistradosPorAnio)
                

            })
        },
        setFilterName() {
            let me = this

            if (this.idFacultadSeleccionada == 0) {
                this.filterAppliedName = "Todas"
                return
            }

            this.filterAppliedName = ""

            if (this.idCarreraSeleccionada != 0) {

                // console.log(this.carrerasFacultad)
                this.carrerasFacultad.forEach(function (carrera) {
                    // console.log(carrera.idCarrera, me.idCarreraSeleccionada)
                if(carrera.idCarrera == me.idCarreraSeleccionada){
                    me.filterAppliedName = carrera.nombre +  " - "
                    // console.log(me.filterAppliedName)
                    return
                }
                })
            }

            this.facultades.forEach(function (facultad) {
                if (facultad.idFacultad == me.idFacultadSeleccionada) {
                    me.filterAppliedName += facultad.nombre
                }
            })
            return


        }

    },
    watch: {
        idFacultadSeleccionada: function () {
            this.bindCarrerasSeleccionadas()
            this.bindEstadisticas()
            this.setFilterName()
        },
        idCarreraSeleccionada: function () {
            this.bindEstadisticas()
            this.setFilterName()
        },
        yearSelected: function () {
            this.bindDashboardData()
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
    display: grid;

    grid-template-columns: repeat(3, 1fr);
    width: 100%;
    margin-bottom: 10vh;
    justify-content: center;
    align-items: center;
    margin-top: 5vh;
    margin-bottom: 5vh;
    padding: 0 5vw;
    gap: 2vw;

}

.dashboard-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    width: 100%;
    margin-bottom: 10vh;
    justify-content: center;
    align-items: center;
    margin-top: 5vh;
    margin-bottom: 5vh;
    padding: 0 5vw;
    gap: 2vw;

}

.dashboard-content {
    padding: 0 5vw;
    min-height: 45vh;

}

.statistic .header {
    min-height: 5vh;
    display: flex;
    align-items: center;
    padding: 20px;
    justify-content: space-between;
}

.statistic h3 {
    font-weight: 600;
    font-size: 1.5rem;
    color: #9b9b9b;

}

.statistic {
    flex: 1;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);

}

.year-select {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    margin-bottom: 2vh;
    width: 25%;
    padding-inline: 5vw;

}

@media screen and (max-width: 768px) {
    .dashboard {
        grid-template-columns: 1fr;
    }

    .statistic {
        margin-bottom: 5vh;
    }
    
    .dashboard-2{
        grid-template-columns: 1fr;
    }
}

.statistic .text {
    font-size: 3.0rem;
    font-weight: bold;
    color: rgb(0, 0, 0);
}

.statistic .body {

    display: flex;
    justify-content: center;
    align-items: center;
}

.contenedor-flex {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 20px;
    width: 100%;
    padding-inline: 5vw;
}
</style>
