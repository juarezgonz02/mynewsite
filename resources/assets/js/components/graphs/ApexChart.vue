<template>
  <div>
    <apexchart :type="chartType" :options="chartOptions" :series="chartSeries"></apexchart>
  </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts';

export default {
  components: {
    apexchart: VueApexCharts
  },
  props: {
    chartType: {
      type: String,
      default: 'line'
    },
    chartOptions: {
      type: Object,
      default: () => ({})
    },
    chartSeries: {
      type: Array,
      default: () => []
    }
  }
};
</script>
