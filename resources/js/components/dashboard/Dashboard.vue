<template>
    <div v-if="ready">
        <div class="row">
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="0"
                    titulo="Contrato Inicial"
                    :numero_contratos="dataPizzaData.series[0]"
                    :porcentagem_contratos="porcentagensData[0]"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="1"
                    titulo="1° Termo Aditivo"
                    :numero_contratos="dataPizzaData.series[1]"
                    :porcentagem_contratos="porcentagensData[1]"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="2"
                    titulo="2° Termo Aditivo"
                    :numero_contratos="dataPizzaData.series[2]"
                    :porcentagem_contratos="porcentagensData[2]"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="3"
                    titulo="3° Termo Aditivo"
                    :numero_contratos="dataPizzaData.series[3]"
                    :porcentagem_contratos="porcentagensData[3]"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="4"
                    titulo="4° Termo Aditivo"
                    :numero_contratos="dataPizzaData.series[4]"
                    :porcentagem_contratos="porcentagensData[4]"
                ></termo-card>
            </div>
            <div class="col-lg-2 col-md-4 order-1">
                <termo-card
                    numero="5"
                    titulo="5° Termo Aditivo"
                    :numero_contratos="dataPizzaData.series[5]"
                    :porcentagem_contratos="porcentagensData[5]"
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
                                <h3 id="ValorSelecionado" class=" card-title text-nowrap mb-2">R$ {{valortotalformatadoData
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
                        ></contrato-card>
                    </div>
                    <div class="col-4">
                        <contrato-card
                            numero="7"
                            titulo="Contratos em Atenção"
                            :numero_contratos="dataPizzaData.series[5]"
                            :porcentagem_contratos="porcentagensData[5]"
                        ></contrato-card>
                    </div>
                    <div class="col-4">
                        <contrato-card
                            numero="8"
                            titulo="Contratos Encerrados"
                            :numero_contratos="dataPizzaData.series[5]"
                            :porcentagem_contratos="porcentagensData[5]"
                        ></contrato-card>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card  shadow-termo align-items-stretch">
                    <h5 class="card-header m-0 me-2 pb-3">VALOR MONETÁRIO DOS CONTRATOS</h5>
                    <form class="card-body" id="frm" name="frm" data-method="get" action="/dashboard/">
                        <div class="row">
                            <div class="col-5">
                                <label class="form-label">Valor</label>
                                <select class="form-select" id="filterValue" aria-label="cu">
                                    <option value="">Todos</option>
                                    <option value="menor">Menor de R$1.000.000</option>
                                    <option value="entre1e4">Entre R$1.000.000 e R$4.000.000</option>
                                    <option value="entre4e9">Entre R$4.000.000 e R$9.000.000</option>
                                    <option value="maior9">Acima de R$9.000.000</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <label class="form-label">Situação</label>
                                <select class="form-select" id="filterSituacao" aria-label="cu">
                                    <option value="">Todos</option>
                                    <option value="V">Vigênte</option>
                                    <option value="V1">1° Termo Aditivo</option>
                                    <option value="V2">2° Termo Aditivo</option>
                                    <option value="V3">3° Termo Aditivo</option>
                                    <option value="V4">4° Termo Aditivo</option>
                                    <option value="NV">Vencido</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <submit-button label="Filtrar"></submit-button>
                            </div>
                        </div>
                    </form>
                    <bar-chart id="valor" :series="valoresContratosData[0]" :labels="props.nomesContratos"></bar-chart>
                </div>
            </div>
            <div class="col-6">
                <div class="card shadow-termo align-items-stretch">
                    <h5 class="card-header m-0 me-2 pb-3">RISCO POR CONTRATO</h5>
                    <form class="text-start p-3" id="filterForm">
                        <div class="row">
                            <div class="col-5">
                                <label class="form-label">Valor</label>
                                <select class="form-select" id="filterValue" aria-label="cu">
                                    <option value="">Todos</option>
                                    <option value="menor">Menor de R$1.000.000</option>
                                    <option value="entre1e4">Entre R$1.000.000 e R$4.000.000</option>
                                    <option value="entre4e9">Entre R$4.000.000 e R$9.000.000</option>
                                    <option value="maior9">Acima de R$9.000.000</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <label class="form-label">Situação</label>
                                <select class="form-select" id="filterSituacao" aria-label="cu">
                                    <option value="">Todos</option>
                                    <option value="V">Vigênte</option>
                                    <option value="V1">1° Termo Aditivo</option>
                                    <option value="V2">2° Termo Aditivo</option>
                                    <option value="V3">3° Termo Aditivo</option>
                                    <option value="V4">4° Termo Aditivo</option>
                                    <option value="NV">Vencido</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label">Filtrar</label>
                                <button id="" class="btn btn-primary me-3" type="submit"><i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {defineProps, onMounted, ref} from "vue";
import ContratoCard from "./ContratoCard.vue";

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
const nomesContratosData = ref([]);
const statusContratosData = ref([]);
const totalContratosData = ref('');
const valortotalformatadoData = ref([]);
const porcentagensData = ref([]);
const ready = ref(false);

onMounted(() => {
    try {
        dataPizzaData.value = Array.isArray(props.dataPizza) ? props.dataPizza : JSON.parse(props.dataPizza);
        valoresContratosData.value = Array.isArray(props.valoresContratos) ? props.valoresContratos : JSON.parse(props.valoresContratos);
        nomesContratosData.value = Array.isArray(props.nomesContratos) ? props.nomesContratos : JSON.parse(props.nomesContratos);
        statusContratosData.value = Array.isArray(props.statusContratos) ? props.statusContratos : JSON.parse(props.statusContratos);
        totalContratosData.value =  props.totalContratos;
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
