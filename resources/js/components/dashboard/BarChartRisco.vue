<template>
    <div v-if="ready" id="chartRisco">
        <apexchart type="bar" height="350" :options="chartOptions" :series="formattedValores"></apexchart>
    </div>
</template>

<script setup>
import {defineProps, inject, onMounted, ref} from "vue";

const props = defineProps({
    series: {required: true},
    labels: {required: true},
    situacao: {required: true},
    possibilidades: {required: true}
});

let valores = ref([]);
let nomes = ref([]);
let cores = ref([]);
let numero = ref([]);
let formattedValores = ref([]);
let labels = ref([]);
let possibilidades = ref([]);
let situacao = ref([]);
const chartOptions = ref({})
const ready = ref(false);
const events = inject('events');
onMounted(() => {
    events.on("updateBarraChartsRisco", (data) => {
        numero.value = data[3]
        cores.value = [data[2]]
        valores.value = JSON.parse(data[1]);
        situacao.value = JSON.parse(data[4]);
        possibilidades.value = JSON.parse(data[5])
        console.log(situacao.value, ' situacao.value')
        console.log(possibilidades.value, ' possibilidades.value')
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
            colors: [function({ value, seriesIndex, w }) {
                if (value >= 20) {
                    return '#ff0000'
                } else if (value >= 7){
                    return '#ffff00'
                } else{
                    return '#90EE90'
                }
            }],
            xaxis: {
                min: 0,
                max: 25,
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
                        <div>Pontuação: ${series[seriesIndex][dataPointIndex]} de 25</div>
                        <div>Tratamaento: ${possibilidades.value[dataPointIndex]}</div>
                        <div>Situação: ${situacao.value[dataPointIndex]}</div>
            </div>
          </div>
        `;
                },
            },
        };
    });
    try {

        valores.value = JSON.parse(props.series.data);
        situacao.value = JSON.parse(props.situacao);
        possibilidades.value = JSON.parse(props.possibilidades)
        formattedValores.value = [{
            data: valores.value,
            name: ''
        }];
        console.log(formattedValores.value)
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
    colors: [function({ value, seriesIndex, w }) {
        if (value >= 20) {
            return '#ff0000'
        } else if (value >= 7){
            return '#ffff00'
        } else{
            return '#90EE90'
        }
    }],
    xaxis: {
        min: 0,
        max: 25,
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
            <div class="ms-2 mt-2">
            <span v-if="data" class=" icon-size-risco text-${getCellClass((series[seriesIndex][dataPointIndex]))}-500"></span>
                        <div>Pontuação: ${series[seriesIndex][dataPointIndex]} de 25</div>
                        <div>Tratamaento: ${possibilidades.value[dataPointIndex]}</div>
                        <div>Situação: ${situacao.value[dataPointIndex]}</div>
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
