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
const chartOptions = ref({});
const ready = ref(false);
const events = inject('events');

onMounted(() => {
    events.on("updateBarraChartsValor", (data) => {
        numero.value = data[3];
        cores.value = [data[2]];
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
            <div class="element-with-gradient-${numero.value[0]} p-3" style="color: white">${nomes.value[dataPointIndex]} </div>
            <div class="p-3">
              <span class="text-end"> ${formatarvalor(series[seriesIndex][dataPointIndex].toString())} </span>
            </div>
          </div>
        `;
                },
            },
        };
    });

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
    // Converte o valor para um número de ponto flutuante
    let valorFloat = parseFloat(valor).toFixed(2);
    // Formata o valor como moeda brasileira
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(valorFloat);
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
        custom: function ({series, seriesIndex, dataPointIndex, w}) {
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
};
</script>

<style scoped>
</style>
