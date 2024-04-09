<template>
    <div>
        <h4 class="text-center">Estudiantes por Carrera</h4>
        <apex-chart width="400" :chart-type="type" :chart-options="options" :chart-series="series"></apex-chart>
    </div>

</template>

<script>
import ApexChart from './ApexChart.vue';


export default{
    components:{
        ApexChart
    },
    props:{
        studentsByCareer:{
            type: Array,
            default: []
        }
    },
    data(){
        return({
            type: 'donut',
            options:{
                labels: [],
                chart: {
                    id: 'vuechart-example'
                },
                dataLabels: {
                    enabled: true // Desactiva los labels de datos para el gráfico de pie
                },
                legend: {
                    show: false,
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                    labels: {
                            show: true,
                            
                        }
                        }
                    }
                    }
            },
            series: []
            }
        )
    },
    watch:{
        studentsByCareer(){
            
            // console.log(this.studentsByCareer.map(career => career.nombre));
            // this.options.labels = this.studentsByCareer.map(career => career.nombre);
            this.options ={
                labels: this.studentsByCareer.map(career => career.nombre),
                chart: {
                    id: 'vuechart-example'
                },
                dataLabels: {
                    enabled: true // Desactiva los labels de datos para el gráfico de pie
                }
            }
            // console.log(this.options.labels);
            this.series = this.studentsByCareer.map(career => career.estudiantes);

        }
    }
}

</script>