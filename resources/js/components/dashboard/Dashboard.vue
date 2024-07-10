<template>
    <div v-if="ready">
        <div class="row">
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="0"
                    titulo="Contrato Inicial"
                    :numero_contratos="numeroTermoCard.series[0]"
                    :porcentagem_contratos="porcentagensTermoCard[0]"
                    :selected="selecionado === 0"
                    @click="selecionarCard(0)"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="1"
                    titulo="1° Termo Aditivo"
                    :numero_contratos="numeroTermoCard.series[1]"
                    :porcentagem_contratos="porcentagensTermoCard[1]"
                    :selected="selecionado === 1"
                    @click="selecionarCard(1)"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="2"
                    titulo="2° Termo Aditivo"
                    :numero_contratos="numeroTermoCard.series[2]"
                    :porcentagem_contratos="porcentagensTermoCard[2]"
                    :selected="selecionado === 2"
                    @click="selecionarCard(2)"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="3"
                    titulo="3° Termo Aditivo"
                    :numero_contratos="numeroTermoCard.series[3]"
                    :porcentagem_contratos="porcentagensTermoCard[3]"
                    :selected="selecionado === 3"
                    @click="selecionarCard(3)"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="4"
                    titulo="4° Termo Aditivo"
                    :numero_contratos="numeroTermoCard.series[4]"
                    :porcentagem_contratos="porcentagensTermoCard[4]"
                    :selected="selecionado === 4"
                    @click="selecionarCard(4)"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="5"
                    titulo="5° Termo Aditivo"
                    :numero_contratos="numeroTermoCard.series[5]"
                    :porcentagem_contratos="porcentagensTermoCard[5]"
                    :selected="selecionado === 5"
                    @click="selecionarCard(5)"
                ></termo-card>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card shadow-termo">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-body">
                                <div class="todos" id="gerencia"></div>
                                <h5 class="mt-3">Gerência de Risco</h5>
                                <span class="fw-semibold d-block mb-1">Valor dos contratos selecionados</span>
                                <h3 id="ValorSelecionado" class=" card-title text-nowrap mb-2">{{
                                        valorTotalRadialBarFormatado
                                    }} </h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <chart-radial></chart-radial>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-4">
                        <contrato-card
                            numero="6"
                            titulo="Contratos Ativos"
                            :numero_contratos="numeroContratosAtivo"
                            :porcentagem_contratos="porcentagensTermoCard[5]"
                            :selected="selecionado === 6"
                            @click="selecionarCard(6)"
                        ></contrato-card>
                    </div>
                    <div class="col-4">
                        <contrato-card
                            numero="7"
                            titulo="Contratos em Atenção"
                            :numero_contratos="numeroContratosEmAtencao"
                            :porcentagem_contratos="porcentagensTermoCard[5]"
                            :selected="selecionado === 7"
                            @click="selecionarCard(7)"
                        ></contrato-card>
                    </div>
                    <div class="col-4">
                        <contrato-card
                            numero="8"
                            titulo="Contratos Encerrados"
                            :numero_contratos="totalContratosEncerrado"
                            :porcentagem_contratos="porcentagensTermoCard[5]"
                            :selected="selecionado === 8"
                            @click="selecionarCard(8)"
                        ></contrato-card>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <valor-filter :selecionado="selecionado"></valor-filter>
            </div>
            <div class="col-6">
                <responsabilidade-filter :selecionado="selecionado"></responsabilidade-filter>
            </div>

            <div class="col-6">
                <div class="card  shadow-termo align-items-stretch">
                    <h5 class="card-header m-0 me-2 pb-3">VALOR MONETÁRIO DOS CONTRATOS</h5>
                    <bar-chart-valor id="valor" :series="valoresContratoBarChartValor[0]"
                                     :labels="nomesContratosBarChart"></bar-chart-valor>
                </div>
            </div>
            <div class="col-6">
                <div class="card shadow-termo align-items-stretch">
                    <h5 class="card-header m-0 me-2 pb-3">RISCO POR CONTRATO</h5>
                    <bar-chart-risco id="risco" :series="riscoContratosBarChartRisco[0]"
                                     :labels="nomesContratosBarChart"></bar-chart-risco>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {defineProps, inject, onMounted, ref} from "vue";

const events = inject('events');
const props = defineProps({
    dataPizza: {type: [Array, String], required: true},
    valoresContratos: {type: [Array, String], required: true},
    nomesContratos: {type: [Array, String], required: true},
    statusContratos: {type: [Array, String], required: true},
    totalContratos: {type: [Array, String], required: true},
    porcentagens: {type: [Array, String], required: true},
    valorTotal: {type: [Array, String], required: true},
    riscoContratosBarChartRisco: {type: [Array, String], required: true},
    atencao: {type: [Array, String], required: true},
    pessoas_id: {type: [Array, String], required: true},
    cargos_id: {type: [Array, String], required: true},
});

const numeroTermoCard = ref([]);
const valoresContratoBarChartValor = ref([]);
const valoresFiltradosContratosData = ref([]);
const pessoasIdAtivo = ref([]);
const pessoasIdArray = ref([]);
const cargosIdAtivo = ref([]);
const cargosIdArray = ref([]);
const atencao = ref([]);
const numeroContratosEmAtencao = ref(0);
const valorTotal = ref([]);
const riscoContratosBarChartRisco = ref([]);
const riscoFiltradosContratosData = ref([]);
const coresFiltradas = ref([]);
const numeroSelected = ref([]);
const nomesContratosBarChart = ref([]);
const nomeFiltradosContratosData = ref([]);
const statusContratosData = ref([]);
const numeroContratosAtivo = ref(0);
const totalContratosEncerrado = ref(0);
const valorTotalRadialBarFormatado = ref([]);
const porcentagensTermoCard = ref([]);
const porcentagensRadial = ref([]);
const ready = ref(false);
const selecionado = ref(6);

const selecionarCard = (index) => {
    events.emit('selecionaCard', index)
    events.emit('filter', index)
    events.emit('selecionadoTransmit', index)
};

onMounted(() => {
    filterValor();
    filter();
    filterResponsabilidade();
    filterPessoa();
    try {
        atencao.value = Array.isArray(props.atencao) ? props.atencao : JSON.parse(props.atencao);
        valorTotal.value = Array.isArray(props.valorTotal) ? props.valorTotal : JSON.parse(props.valorTotal);
        riscoContratosBarChartRisco.value = Array.isArray(props.riscoContratosBarChartRisco) ? props.riscoContratosBarChartRisco : JSON.parse(props.riscoContratosBarChartRisco);
        numeroTermoCard.value = Array.isArray(props.dataPizza) ? props.dataPizza : JSON.parse(props.dataPizza);
        valoresContratoBarChartValor.value = Array.isArray(props.valoresContratos) ? props.valoresContratos : JSON.parse(props.valoresContratos);
        nomesContratosBarChart.value = props.nomesContratos;
        statusContratosData.value = Array.isArray(props.statusContratos) ? props.statusContratos : JSON.parse(props.statusContratos);
        valorTotalRadialBarFormatado.value = formatarRealBrasileiro(valorTotal.value);
        porcentagensTermoCard.value = Array.isArray(props.porcentagens) ? props.porcentagens : JSON.parse(props.porcentagens);
        valoresFiltradosContratosData.value = JSON.parse(valoresContratoBarChartValor.value);
        nomeFiltradosContratosData.value = JSON.parse(nomesContratosBarChart.value);
        riscoFiltradosContratosData.value = JSON.parse(riscoContratosBarChartRisco.value);
        valoresContratoBarChartValor.value = [];
        nomesContratosBarChart.value = [];
        pessoasIdAtivo.value = JSON.parse(props.pessoas_id);
        pessoasIdAtivo.value.forEach((pessoaIdStr, index) => {
            pessoasIdArray.value[index] = pessoaIdStr.split(',').map(id => id.trim());
        })
        pessoasIdAtivo.value = []
        cargosIdAtivo.value = JSON.parse(props.cargos_id);
        cargosIdAtivo.value.forEach((cargoIdStr, index) => {
            cargosIdArray.value[index] = cargoIdStr.split(',').map(id => id.trim());
        })
        cargosIdAtivo.value = []
        riscoContratosBarChartRisco.value = [];
        let countArray = 0;
        for (let i = 0; i < atencao.value.length; i++) {
            if (atencao.value[i] == '1') {
                numeroContratosEmAtencao.value++;
            }
            if (statusContratosData.value[i] == 'NV') {
                totalContratosEncerrado.value++
            } else {
                cargosIdAtivo.value[countArray] = cargosIdArray.value[i]
                pessoasIdAtivo.value[countArray] = pessoasIdArray.value[i]
                riscoContratosBarChartRisco.value[countArray] = riscoFiltradosContratosData.value[i]
                valoresContratoBarChartValor.value[countArray] = valoresFiltradosContratosData.value[i]
                nomesContratosBarChart.value[countArray] = nomeFiltradosContratosData.value[i]
                numeroContratosAtivo.value++;

                countArray++;
            }
        }
        pessoasIdArray.value = pessoasIdAtivo.value;
        nomesContratosBarChart.value = JSON.stringify(nomesContratosBarChart.value);
        valoresContratoBarChartValor.value = JSON.stringify(valoresContratoBarChartValor.value);
        riscoContratosBarChartRisco.value = JSON.stringify(riscoContratosBarChartRisco.value)
        valoresContratoBarChartValor.value = [{
            data: valoresContratoBarChartValor.value
        }];
        riscoContratosBarChartRisco.value = [{
            data: riscoContratosBarChartRisco.value
        }]

        ready.value = true;
    } catch (error) {
        console.error("Error parsing props: ", error);
    }
});


const filter = () => {
    events.on("filter", (data) => {
        let countArray = 0;
        let status = JSON.parse(props.statusContratos);
        valoresFiltradosContratosData.value = [];
        nomeFiltradosContratosData.value = [];
        riscoFiltradosContratosData.value = [];
        pessoasIdAtivo.value = JSON.parse(props.pessoas_id);
        pessoasIdAtivo.value.forEach((pessoaIdStr, index) => {
            pessoasIdArray.value[index] = pessoaIdStr.split(',').map(id => id.trim());
        })
        pessoasIdAtivo.value = []
        let valoresContratosArray = JSON.parse(props.valoresContratos);
        valoresContratosArray = JSON.parse(valoresContratosArray);
        let nomesContratosArray = JSON.parse(props.nomesContratos);
        let riscoContratosArray = JSON.parse(props.riscoContratosBarChartRisco);
        riscoContratosArray = JSON.parse(riscoContratosArray);
        if (data === 0) {
            coresFiltradas.value = ['#00b478ff'];
            for (let i = 0; i < status.length; i++) {
                if (status[i] == 'V' + data) {
                    riscoFiltradosContratosData.value[countArray] = riscoContratosArray[i];
                    valoresFiltradosContratosData.value[countArray] = valoresContratosArray[i];
                    nomeFiltradosContratosData.value[countArray] = nomesContratosArray[i];
                    pessoasIdAtivo.value[countArray] = pessoasIdArray.value[i];
                    countArray++;
                }
            }
        } else if (data === 1) {
            coresFiltradas.value = ['#00a0a0ff'];
            for (let i = 0; i < status.length; i++) {
                if (status[i] == 'V' + data) {
                    riscoFiltradosContratosData.value[countArray] = riscoContratosArray[i];
                    valoresFiltradosContratosData.value[countArray] = valoresContratosArray[i];
                    nomeFiltradosContratosData.value[countArray] = nomesContratosArray[i];
                    pessoasIdAtivo.value[countArray] = pessoasIdArray.value[i];
                    countArray++;
                }
            }
        } else if (data === 2) {
            coresFiltradas.value = ['#008cc8ff'];
            for (let i = 0; i < status.length; i++) {
                if (status[i] == 'V' + data) {
                    riscoFiltradosContratosData.value[countArray] = riscoContratosArray[i];
                    valoresFiltradosContratosData.value[countArray] = valoresContratosArray[i];
                    nomeFiltradosContratosData.value[countArray] = nomesContratosArray[i];
                    pessoasIdAtivo.value[countArray] = pessoasIdArray.value[i];
                    countArray++;
                }
            }
        } else if (data === 3) {
            coresFiltradas.value = ['#f0b450ff'];
            for (let i = 0; i < status.length; i++) {
                if (status[i] == 'V' + data) {
                    riscoFiltradosContratosData.value[countArray] = riscoContratosArray[i];
                    valoresFiltradosContratosData.value[countArray] = valoresContratosArray[i];
                    nomeFiltradosContratosData.value[countArray] = nomesContratosArray[i];
                    pessoasIdAtivo.value[countArray] = pessoasIdArray.value[i];
                    countArray++;
                }
            }
        } else if (data === 4) {
            coresFiltradas.value = ['#fa9628ff'];
            for (let i = 0; i < status.length; i++) {
                if (status[i] == 'V' + data) {
                    riscoFiltradosContratosData.value[countArray] = riscoContratosArray[i];
                    valoresFiltradosContratosData.value[countArray] = valoresContratosArray[i];
                    nomeFiltradosContratosData.value[countArray] = nomesContratosArray[i];
                    pessoasIdAtivo.value[countArray] = pessoasIdArray.value[i];
                    countArray++;
                }
            }
        } else if (data === 5) {
            coresFiltradas.value = ['#fa641eff'];
            for (let i = 0; i < status.length; i++) {
                if (status[i] == 'V' + data) {
                    riscoFiltradosContratosData.value[countArray] = riscoContratosArray[i];
                    valoresFiltradosContratosData.value[countArray] = valoresContratosArray[i];
                    nomeFiltradosContratosData.value[countArray] = nomesContratosArray[i];
                    pessoasIdAtivo.value[countArray] = pessoasIdArray.value[i];
                    countArray++;
                }
            }
        } else if (data === 6) {
            coresFiltradas.value = ['#6d6bfc'];
            for (let i = 0; i < status.length; i++) {
                if (status[i] != 'NV') {
                    riscoFiltradosContratosData.value[countArray] = riscoContratosArray[i];
                    valoresFiltradosContratosData.value[countArray] = valoresContratosArray[i];
                    nomeFiltradosContratosData.value[countArray] = nomesContratosArray[i];
                    pessoasIdAtivo.value[countArray] = pessoasIdArray.value[i];
                    countArray++;
                }
            }
        } else if (data === 7) {
            coresFiltradas.value = ['#FFD200'];
            for (let i = 0; i < status.length; i++) {
                if (atencao.value[i] == '1') {
                    riscoFiltradosContratosData.value[countArray] = riscoContratosArray[i];
                    valoresFiltradosContratosData.value[countArray] = valoresContratosArray[i];
                    nomeFiltradosContratosData.value[countArray] = nomesContratosArray[i];
                    pessoasIdAtivo.value[countArray] = pessoasIdArray.value[i];
                    countArray++;
                }
            }
        } else if (data === 8) {
            coresFiltradas.value = ['#6C757D'];
            for (let i = 0; i < status.length; i++) {
                if (status[i] == 'NV') {
                    riscoFiltradosContratosData.value[countArray] = riscoContratosArray[i];
                    valoresFiltradosContratosData.value[countArray] = valoresContratosArray[i];
                    nomeFiltradosContratosData.value[countArray] = nomesContratosArray[i];
                    pessoasIdAtivo.value[countArray] = pessoasIdArray.value[i];
                    countArray++;
                }
            }
        } else {
            coresFiltradas.value = ['#6d6bfc'];
        }
        const somaValores = valoresFiltradosContratosData.value.reduce((acc, currentValue) => acc + currentValue, 0);
        porcentagensRadial.value = parseInt((somaValores / valorTotal.value) * 100)
        valorTotalRadialBarFormatado.value = formatarRealBrasileiro(somaValores);
        nomesContratosBarChart.value = JSON.stringify(nomeFiltradosContratosData.value);
        valoresContratoBarChartValor.value = JSON.stringify(valoresFiltradosContratosData.value);
        riscoContratosBarChartRisco.value = JSON.stringify(riscoFiltradosContratosData.value);
        events.emit('updateBarraChartsValor', [nomesContratosBarChart.value, valoresContratoBarChartValor.value, coresFiltradas.value, [data]])
        events.emit('updateBarraChartsRisco', [nomesContratosBarChart.value, riscoContratosBarChartRisco.value, coresFiltradas.value, [data]])
        events.emit('updateRadialCharts', [porcentagensRadial.value, coresFiltradas.value]);
    });
}
const filterValor = () => {
    events.on("filterValor", (data) => {
        let countArray = 0;
        valoresFiltradosContratosData.value = [];
        nomeFiltradosContratosData.value = [];
        riscoFiltradosContratosData.value = [];
        numeroSelected.value = data[2].value;
        pessoasIdAtivo.value = JSON.parse(props.pessoas_id);
        pessoasIdAtivo.value.forEach((pessoaIdStr, index) => {
            pessoasIdArray.value[index] = pessoaIdStr.split(',').map(id => id.trim());
        })
        pessoasIdAtivo.value = []
        let valoresContratosArray = JSON.parse(valoresContratoBarChartValor.value);
        let nomesContratosArray = JSON.parse(nomesContratosBarChart.value);
        let riscoContratosArray = JSON.parse(riscoContratosBarChartRisco.value);
        for (let i = 0; i < valoresContratosArray.length; i++) {
            if (parseFloat(valoresContratosArray[i]) > parseFloat(data[0]) && parseFloat(valoresContratosArray[i]) < parseFloat(data[1])) {
                valoresFiltradosContratosData.value[countArray] = valoresContratosArray[i];
                nomeFiltradosContratosData.value[countArray] = nomesContratosArray[i];
                riscoFiltradosContratosData.value[countArray] = riscoContratosArray[i];
                pessoasIdAtivo.value[countArray] = pessoasIdArray.value[i];
                countArray++;
            }
        }
        const somaValores = valoresFiltradosContratosData.value.reduce((acc, currentValue) => acc + currentValue, 0);
        porcentagensRadial.value = parseInt((somaValores / valorTotal.value) * 100)
        valorTotalRadialBarFormatado.value = formatarRealBrasileiro(somaValores);
        nomesContratosBarChart.value = JSON.stringify(nomeFiltradosContratosData.value);
        valoresContratoBarChartValor.value = JSON.stringify(valoresFiltradosContratosData.value);
        riscoContratosBarChartRisco.value = JSON.stringify(riscoFiltradosContratosData.value);
        events.emit('clearResponsabilidade');
        events.emit('updateBarraChartsValor', [nomesContratosBarChart.value, valoresContratoBarChartValor.value, coresFiltradas.value, [numeroSelected.value]])
        events.emit('updateBarraChartsRisco', [nomesContratosBarChart.value, riscoContratosBarChartRisco.value, coresFiltradas.value, [numeroSelected.value]])
        events.emit('updateRadialCharts', [porcentagensRadial.value, coresFiltradas.value]);
    });
}

const filterResponsabilidade = () => {
    events.on("filterResponsabilidade", (data) => {
        let countArray = 0;
        valoresFiltradosContratosData.value = [];
        nomeFiltradosContratosData.value = [];
        riscoFiltradosContratosData.value = [];
        let valoresContratosArray = JSON.parse(valoresContratoBarChartValor.value);
        let nomesContratosArray = JSON.parse(nomesContratosBarChart.value);
        let riscoContratosArray = JSON.parse(riscoContratosBarChartRisco.value);
        numeroSelected.value = data[2];
        for (let i = 0; i < pessoasIdAtivo.value.length; i++) {
            for (let j = 0; j < pessoasIdAtivo.value[i].length; j++) {
                if (parseInt(data[0]) === parseInt(pessoasIdAtivo.value[i][j]) && parseInt(data[1]) === parseInt(cargosIdAtivo.value[i][j])) {
                    valoresFiltradosContratosData.value[countArray] = valoresContratosArray[i];
                    nomeFiltradosContratosData.value[countArray] = nomesContratosArray[i];
                    riscoFiltradosContratosData.value[countArray] = riscoContratosArray[i];
                    countArray++;
                }
            }
        }
        const somaValores = valoresFiltradosContratosData.value.reduce((acc, currentValue) => acc + currentValue, 0);
        porcentagensRadial.value = parseInt((somaValores / valorTotal.value) * 100)
        valorTotalRadialBarFormatado.value = formatarRealBrasileiro(somaValores);
        nomesContratosBarChart.value = JSON.stringify(nomeFiltradosContratosData.value);
        valoresContratoBarChartValor.value = JSON.stringify(valoresFiltradosContratosData.value);
        riscoContratosBarChartRisco.value = JSON.stringify(riscoFiltradosContratosData.value);
        events.emit('clearValor');
        events.emit('updateBarraChartsValor', [nomesContratosBarChart.value, valoresContratoBarChartValor.value, coresFiltradas.value, [numeroSelected.value]])
        events.emit('updateBarraChartsRisco', [nomesContratosBarChart.value, riscoContratosBarChartRisco.value, coresFiltradas.value, [numeroSelected.value]])
        events.emit('updateRadialCharts', [porcentagensRadial.value, coresFiltradas.value]);
    });
}


const filterPessoa = () => {
    events.on("filterPessoa", (data) => {
        let countArray = 0;
        valoresFiltradosContratosData.value = [];
        nomeFiltradosContratosData.value = [];
        riscoFiltradosContratosData.value = [];
        let valoresContratosArray = JSON.parse(valoresContratoBarChartValor.value);
        let nomesContratosArray = JSON.parse(nomesContratosBarChart.value);
        let riscoContratosArray = JSON.parse(riscoContratosBarChartRisco.value);
        numeroSelected.value = data[1];
        for (let i = 0; i < pessoasIdAtivo.value.length; i++) {
            for (let j = 0; j < pessoasIdAtivo.value[i].length; j++) {
                if (parseInt(data[0]) === parseInt(pessoasIdAtivo.value[i][j])) {
                    valoresFiltradosContratosData.value[countArray] = valoresContratosArray[i];
                    nomeFiltradosContratosData.value[countArray] = nomesContratosArray[i];
                    riscoFiltradosContratosData.value[countArray] = riscoContratosArray[i];
                    countArray++;
                }
            }
        }
        const somaValores = valoresFiltradosContratosData.value.reduce((acc, currentValue) => acc + currentValue, 0);
        porcentagensRadial.value = parseInt((somaValores / valorTotal.value) * 100)
        valorTotalRadialBarFormatado.value = formatarRealBrasileiro(somaValores);
        nomesContratosBarChart.value = JSON.stringify(nomeFiltradosContratosData.value);
        valoresContratoBarChartValor.value = JSON.stringify(valoresFiltradosContratosData.value);
        riscoContratosBarChartRisco.value = JSON.stringify(riscoFiltradosContratosData.value);
        events.emit('clearValor');
        events.emit('updateBarraChartsValor', [nomesContratosBarChart.value, valoresContratoBarChartValor.value, coresFiltradas.value, [numeroSelected.value]])
        events.emit('updateBarraChartsRisco', [nomesContratosBarChart.value, riscoContratosBarChartRisco.value, coresFiltradas.value, [numeroSelected.value]])
        events.emit('updateRadialCharts', [porcentagensRadial.value, coresFiltradas.value]);
    });
};
const formatarRealBrasileiro = (valor) => {
    return valor.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
};
</script>

<style scoped>
</style>
