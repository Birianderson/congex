<template>
    <div v-if="ready" id="chart">
        <apexchart type="bar" height="350" :options="chartOptions" :series="formattedValores"></apexchart>
    </div>
</template>

<script setup>
import {defineProps, onMounted, ref} from "vue";

const props = defineProps({
    series: {required: true},
    labels: {required: true},
});

let valores = ref([]);
let nomes = ref([]);
let formattedValores = ref([]);
let formattedNomes = ref([]);
let labels = ref([]);
const ready = ref(false);

onMounted(() => {
    try {
        valores.value = JSON.parse(props.series.data);
        formattedValores.value = [{
            data: valores.value,
            name: ''
        }];
        nomes.value = JSON.parse(props.labels);
        ready.value = true;
    } catch (error) {
        console.error("Error processing props:", error);
    }
});

const formatarvalor = (valor) => {
    return 'R$ ' + valor.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') + ',00';
};


const chartOptions = ref({
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
    xaxis: {
        categories: nomes,
        showDuplicates: true,
        labels: {
            formatter: function (value) {
                return value; // Mantém os labels originais
            },
            hideOverlappingLabels: false,
        }
    },
    yaxis: {},
    dataLabels: {
        enabled: false, // Desabilitar números dentro das barras
    },
    tooltip: {
        enabledOnSeries: false,
        custom: function ({ series, seriesIndex, dataPointIndex, w }) {
            return `
          <div class="card">
            <div class="bg-primary p-3" style="color: white">${nomes.value[dataPointIndex]} </div>
            <div class="p-3">
              <span class="bx bx-radio-circle text-primary"></span>
              <span class="text-end"> ${formatarvalor(series[seriesIndex][dataPointIndex].toString())} </span>
            </div>
          </div>
        `;
        },
    },
});

</script>

<style scoped>
</style>
