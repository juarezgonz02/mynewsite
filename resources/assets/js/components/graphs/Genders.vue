<template>
    <div>
        <h4 class="text-center">Estudiantes por Género</h4>
        <apex-chart width="400" :chart-type="type" :chart-options="options" :chart-series="series"></apex-chart>
        <span class="text-center font-weight-bold font-lg">{{filtro}}</span>
    </div>
</template>
<script>
import ApexChart from './ApexChart.vue';

export default{
    components: {
        ApexChart
    },
    props:{
        maleTotal: {
            type: Number,
            default: 0
        },
        femaleTotal: {
            type: Number,
            default: 0,
        },
        filtro:{
            type: String,
            default: 'Todas las Carreras'
            }
    },
    data(){
        return {

            type: 'donut',
            options: {
                // title:{
                //     text: 'Género',
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
                labels: ['Masculino', 'Feminino'],
                plotOptions:{
                    pie:{
                        // donut:{
                        //     labels:{
                        //         show: true,
                        //         name:{
                        //             show: true,
                        //             fontSize: '22px',
                        //             fontFamily: 'Helvetica, Arial, sans-serif',
                        //             color: undefined,
                        //             offsetY: -10,
                        //         },
                        //     }
                        // }
                         expandOnClick: false
                    }
                },
                theme: {
                    palette: 'palette5' // upto palette10
                },
            },
            series: [this.maleTotal,this.femaleTotal]
        }
    },
    watch:{
        maleTotal(){
            this.series = [this.maleTotal,this.femaleTotal]
        },
        femaleTotal(){
            this.series = [this.maleTotal,this.femaleTotal]
        }
    }
}
</script>