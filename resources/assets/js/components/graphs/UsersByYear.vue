<template>
    <div>
        <h4 class="text-center">Estudiantes inscritos</h4>
        <apex-chart :chart-type="type" :chart-options="options" :chart-series="series"></apex-chart>
        <span class="text-center font-weight-bold font-lg">Total {{total}} usuarios</span>
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
                    id: 'users-registered'
                },
                xaxis: {
                    categories: []
                },
                theme: {
                    palette: 'palette5' // upto palette10
                }
            },
            series:[{
                name: 'Usuarios Registrados',
                data: this.data
            }],
            total: 0
        }
    },
    watch:{
        data(){
            this.series = [{
                name: 'Usuarios Registrados',
                data: this.data
            }]

            this.options = {
                chart:{
                    id: 'users-registered'
                },
                xaxis: {
                    categories: this.years
                },
                theme: {
                    palette: 'palette5' // upto palette10
                },
                dataLabels: {
                    enabled: true,
                    size: 10,
                },
            }

            this.total = this.data.reduce((acc, val) => acc + val, 0)
        }
    }
}

</script>