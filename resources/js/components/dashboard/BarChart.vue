<template>
    <div id="chart">
        <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>

<script setup>
import {defineProps, ref} from "vue";

const props = defineProps({

});

const series = [{
    data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
}];
const chartOptions = {
    chart: {
        type: 'bar',
        height: 350
    },
    plotOptions: {
        bar: {
            borderRadius: 5,
            borderRadiusApplication: 'end',
            horizontal: true,
        }
    },
    colors: ['#7367F0'],
    dataLabels: {
        enabled: false
    },
    xaxis: {
        categories: ['South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'Italy', 'France', 'Japan',
            'United States', 'China', 'Germany'
        ],
    }
}

</script>

<style scoped>


</style>
