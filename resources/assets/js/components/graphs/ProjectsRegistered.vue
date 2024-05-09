<template>
    <div>
        <h4 class="text-center">Proyectos publicados en el año {{year}}</h4>
        <apex-chart :chart-type="type" :chart-options="options" :chart-series="series"></apex-chart>
    </div>


</template>
<script>
import ApexChart from './ApexChart.vue';

export default{
    components:{
        ApexChart
    },
    props:{
        year: {
            type: String,
            required: true
        },
        data: {
            type: Array,
            required: true
        }
    },
    data(){
        return{
            type: 'area',
            options:{
                chart:{
                    id: 'projects-registered'
                },
                xaxis: {
                    categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
                },
                theme: {
                    palette: 'palette8' // upto palette10
                }
            },
            series:[{
                name: 'Proyectos Registrados',
                data: this.data
            }]
        }
    },
    watch:{
        year(){
            this.$emit('update:year', this.year);
        },
        data(){
            this.series = [{
                name: 'Proyectos Registrados',
                data: this.data
            }]
        }
    },
}

</script>