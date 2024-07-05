<template>
    <div v-if="ready">
        <div class="row">
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="0"
                    titulo="Contrato Inicial"
                    :numero_contratos="dataPizzaData.series[0]"
                    :porcentagem_contratos="porcentagensData[0]"
                    :selected="selecionado === 0"
                    @click="selecionarCard(0)"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="1"
                    titulo="1° Termo Aditivo"
                    :numero_contratos="dataPizzaData.series[1]"
                    :porcentagem_contratos="porcentagensData[1]"
                    :selected="selecionado === 1"
                    @click="selecionarCard(1)"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="2"
                    titulo="2° Termo Aditivo"
                    :numero_contratos="dataPizzaData.series[2]"
                    :porcentagem_contratos="porcentagensData[2]"
                    :selected="selecionado === 2"
                    @click="selecionarCard(2)"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="3"
                    titulo="3° Termo Aditivo"
                    :numero_contratos="dataPizzaData.series[3]"
                    :porcentagem_contratos="porcentagensData[3]"
                    :selected="selecionado === 3"
                    @click="selecionarCard(3)"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="4"
                    titulo="4° Termo Aditivo"
                    :numero_contratos="dataPizzaData.series[4]"
                    :porcentagem_contratos="porcentagensData[4]"
                    :selected="selecionado === 4"
                    @click="selecionarCard(4)"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="5"
                    titulo="5° Termo Aditivo"
                    :numero_contratos="dataPizzaData.series[5]"
                    :porcentagem_contratos="porcentagensData[5]"
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
                                <h3 id="ValorSelecionado" class=" card-title text-nowrap mb-2">R$ {{
                                        valortotalformatadoData
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
                            :numero_contratos="totalContratosData"
                            :porcentagem_contratos="porcentagensData[5]"
                            :selected="selecionado === 6"
                            @click="selecionarCard(6)"
                        ></contrato-card>
                    </div>
                    <div class="col-4">
                        <contrato-card
                            numero="7"
                            titulo="Contratos em Atenção"
                            :numero_contratos="dataPizzaData.series[5]"
                            :porcentagem_contratos="porcentagensData[5]"
                            :selected="selecionado === 7"
                            @click="selecionarCard(7)"
                        ></contrato-card>
                    </div>
                    <div class="col-4">
                        <contrato-card
                            numero="8"
                            titulo="Contratos Encerrados"
                            :numero_contratos="dataPizzaData.series[5]"
                            :porcentagem_contratos="porcentagensData[5]"
                            :selected="selecionado === 8"
                            @click="selecionarCard(8)"
                        ></contrato-card>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card  shadow-termo align-items-stretch">
                    <h5 class="card-header m-0 me-2 pb-3">VALOR MONETÁRIO DOS CONTRATOS</h5>
                    <bar-chart id="valor" :series="valoresContratosData[0]" :labels="nomesContratosData"></bar-chart>
                </div>
            </div>
            <div class="col-6">
                <div class="card shadow-termo align-items-stretch">
                    <h5 class="card-header m-0 me-2 pb-3">RISCO POR CONTRATO</h5>
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
    valortotalformatado: {type: [Array, String], required: true},
    porcentagens: {type: [Array, String], required: true},
});

const dataPizzaData = ref([]);
const valoresContratosData = ref([]);
const valoresFiltradosContratosData = ref([]);
const nomesContratosData = ref([]);
const nomeFiltradosContratosData = ref([]);
const statusContratosData = ref([]);
const totalContratosData = ref('');
const valortotalformatadoData = ref([]);
const porcentagensData = ref([]);
const ready = ref(false);
const selecionado = ref(6);

const selecionarCard = (index) => {
    events.emit('selecionaCard', index)
    events.emit('filter', index)
};

onMounted(() => {
    events.on("filter", (data) => {
        let countArray = 0;
        let status = JSON.parse(props.statusContratos);
        valoresFiltradosContratosData.value = [];
        nomeFiltradosContratosData.value = [];
        let valoresContratosArray = JSON.parse(props.valoresContratos);
        valoresContratosArray = JSON.parse(valoresContratosArray);
        let nomesContratosArray = JSON.parse(props.nomesContratos);
        for (let i = 0; i < status.length; i++) {
            if (status[i] == 'V' + data) {
                valoresFiltradosContratosData.value[countArray] = valoresContratosArray[i];
                nomeFiltradosContratosData.value[countArray] = nomesContratosArray[i];
                countArray++;
            }
        }
        nomesContratosData.value = JSON.stringify(nomeFiltradosContratosData.value);

        valoresContratosData.value = JSON.stringify(valoresFiltradosContratosData.value);
        events.emit('updateCharts',[nomesContratosData.value, valoresContratosData.value])
    });
    try {
        dataPizzaData.value = Array.isArray(props.dataPizza) ? props.dataPizza : JSON.parse(props.dataPizza);
        valoresContratosData.value = Array.isArray(props.valoresContratos) ? props.valoresContratos : JSON.parse(props.valoresContratos);
        nomesContratosData.value = props.nomesContratos;
        statusContratosData.value = Array.isArray(props.statusContratos) ? props.statusContratos : JSON.parse(props.statusContratos);
        totalContratosData.value = props.totalContratos;
        valortotalformatadoData.value = Array.isArray(props.valortotalformatado) ? props.valortotalformatado : JSON.parse(props.valortotalformatado);
        porcentagensData.value = Array.isArray(props.porcentagens) ? props.porcentagens : JSON.parse(props.porcentagens);
        valoresContratosData.value = [{
            data: valoresContratosData.value
        }];
        ready.value = true;
    } catch (error) {
        console.error("Error parsing props: ", error);
    }
});
</script>

<style scoped>
</style>
