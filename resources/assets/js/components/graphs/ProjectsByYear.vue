<template>
    <div>
        <h4 class="text-center">Proyectos publicados</h4>
        <apex-chart :chart-type="type" :chart-options="options" :chart-series="series"></apex-chart>
        <span class="text-center font-weight-bold font-lg">Total {{total}} proyectos</span>
    </div>

</template>


<script>
import ApexChart from './ApexChart.vue';


export default{
    components:{
        ApexChart
    },
    props:{
        data: {
            type: Array,
            required: true
        },
        years: {
            type: Array,
            required: true
        }
    },

    data(){
        return{
            type: 'line',
            options:{
                chart:{
                    id: 'projects-registered'
                },
                xaxis: {
                    categories: []
                },
                theme: {
                    palette: 'palette7' // upto palette10
                }
            },
            series:[{
                name: 'Proyectos Registrados',
                data: this.data
            }],
            total: 0
        }
    },
    watch:{
        data(){
            this.series = [{
                name: 'Proyectos Registrados',
                data: this.data
            }]

            this.options = {
                chart:{
                    id: 'projects-registered'
                },
                xaxis: {
                    categories: this.years
                },
                theme: {
                    palette: 'palette7' // upto palette10
                },
                dataLabels: {
                    enabled: true,
                    size: 10,
                },
            }

            this.total = this.data.reduce((a, b) => a + b, 0);
        }
    }
}



</script>