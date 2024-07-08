<template>
    <div v-if="ready" id="chart">
        <apexchart type="bar" height="350" :options="chartOptions" :series="formattedValores"></apexchart>
    </div>
</template>

<script setup>
import {defineProps, inject, onMounted, ref} from "vue";

const props = defineProps({
    series: {required: true},
    labels: {required: true},
});

let valores = ref([]);
let nomes = ref([]);
let cores = ref([]);
let numero = ref([]);
let formattedValores = ref([]);
let labels = ref([]);
const chartOptions = ref({})
const ready = ref(false);
const events = inject('events');
onMounted(() => {
    events.on("updateBarraChartsRisco", (data) => {
        numero.value = data[3]
        cores.value = [data[2]]
        valores.value = JSON.parse(data[1]);
        formattedValores.value = [{
            data: valores.value,
            name: ''
        }];
        nomes.value = JSON.parse(data[0]);
        chartOptions.value = {
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
            colors: cores.value[0][0],
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
                custom: function ({series, seriesIndex, dataPointIndex, w}) {
                    return `
          <div class="card">
            <div class="bg-${getCellClass((series[seriesIndex][dataPointIndex]))}-500 p-3" style="color: white">${nomes.value[dataPointIndex]} </div>
            <div class="p-3">
            <span v-if="data" class= "text-${getCellClass((series[seriesIndex][dataPointIndex]))}-500"></span>
                        <span>Pontuação do Risco: ${series[seriesIndex][dataPointIndex]} de 25</span>
            </div>
          </div>
        `;
                },
            },
        };
    });
    try {
        console.log(props)
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


const getCellClass = (value) => {
    if (value >= 20) return 'red';
    if (value >= 7) return 'yellow';
    return 'green';
};

chartOptions.value = {
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
            <div class="bg-${getCellClass((series[seriesIndex][dataPointIndex]))}-500 p-3" style="color: white">${nomes.value[dataPointIndex]} </div>
            <div class="p-3">
            <span v-if="data" class=" icon-size-risco text-${getCellClass((series[seriesIndex][dataPointIndex]))}-500"></span>
                        <span>Pontuação do Risco: ${series[seriesIndex][dataPointIndex]} de 25</span>
            </div>
          </div>
        `;
        },
    },
};

</script>

<style scoped>

.red {
    background-color: #ff0000;
}

.yellow {
    background-color: #ffff00;
}

.green {
    background-color: #90EE90;
}
</style>
