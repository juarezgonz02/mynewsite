<template>
    <div>
        <h4 class="text-center">Estudiantes Registrados en el año {{ year }}</h4>
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
        months: {
            type: Array,
            required: true
        },
        data: {
            type: Array,
            required: true
        },
        year: {
            type: String,
            required: true
        }
    },

    data(){
        return {
            type: 'area',
            options: {
                // title:{
                //     text: 'Usuários Registrados',
                //     align: 'center',
                //     margin: 10,
                //     offsetX: 0,
                //     offsetY: 0,
                //     floating: false,
                //     style: {
                //         fontSize:  '20px',
                //         color:  '#263238',
                //         fontweight:  'lighter',
                //     },
                // },
                chart: {
                    id: 'vuechart-example'
                },
                
                // title: {
                //     text: 'Estudiantes Registrados en el año ' + this.year,
                //     align: 'center',
                //     margin: 10,
                //     offsetX: 0,
                //     offsetY: 0,
                //     floating: false,
                //     style: {
                //         fontSize:  '10px',
                //         color:  '#263238',
                //         fontweight:  'lighter',
                //     },
                // },
                xaxis: {
                    categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
                }
            },
            series: [{
        name: 'Estudiantes Registrados',
        data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
      }]
        }
    },
    watch:{
        months(){
            this.options.xaxis.categories = this.months
        },
        data(){
            this.series = [{
                name: 'Estudiantes Registrados',
                data: this.data
            }]
        },
        // year(){
        //     this.options.title.text = 'Estudiantes Registrados en el año ' + this.year
        // }
    }
}
</script>