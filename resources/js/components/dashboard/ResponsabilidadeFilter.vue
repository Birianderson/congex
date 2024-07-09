<template>
    <div class=" card border-radius-termo shadow-termo">
        <div class="m-4">
            <div class="row">
                <div class="col-5">
                    <label for="pessoa">Pessoa</label>
                    <select class="form-select" id="pessoa" v-model="selectedPessoa" name="pessoa">
                        <option selected value="">Todas</option>
                        <option v-for="pessoa in pessoas" :key="pessoa.id" :value="pessoa.id">
                            {{ pessoa.nome }}
                        </option>
                    </select>
                </div>
                <div class="col-5">
                    <label for="cargo">Cargo</label>
                    <select class="form-select" id="cargo" v-model="selectedCargo" :disabled="isButtonDisabled" name="cargo">
                        <option disabled selected value="">Todos</option>
                        <option v-for="cargo in cargos" :key="cargo.id" :value="cargo.id">
                            {{ cargo.nome }}
                        </option>
                    </select>
                </div>
                <div class="col-2 align-content-end text-end">
                    <button type="button" class="btn btn-primary me-1" :disabled="isButtonDisabled">Filtrar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import {ref, onMounted, computed, inject} from 'vue';

export default {
    setup(props, { emit }) {
        const selectedPessoa = ref('');
        const selectedCargo = ref('');
        const pessoas = ref([]);
        const cargos = ref([]);
        const events = inject('events');
        const selecionado = ref();
        const fetchPessoas = async () => {
            try {
                const response = await axios.get('/pessoa/list');
                if (Array.isArray(response.data.data)) {
                    pessoas.value = response.data.data;
                } else {
                    console.error('Erro ao buscar pessoas: dados não são um array');
                }
            } catch (error) {
                console.error('Erro ao buscar pessoas:', error);
            }
        };

        const fetchCargos = async () => {
            try {
                const response = await axios.get('/cargo/list');
                if (Array.isArray(response.data.data)) {
                    cargos.value = response.data.data;
                } else {
                    console.error('Erro ao buscar cargos: dados não são um array');
                }
            } catch (error) {
                console.error('Erro ao buscar cargos:', error);
            }
        };

        const isButtonDisabled = computed(() => {
            return !selectedPessoa.value;
        });

        onMounted(async () => {
            selecionado.value = props.selecionado
            events.on("selecionadoTransmit", (data) => {
                selecionado.value = parseInt(data);
                console.log(selecionado.value,'responsabilidade filter')
            });
        });

        return {
            selectedPessoa,
            selectedCargo,
            pessoas,
            cargos,
            isButtonDisabled,
        };
    },
    props: {
        selecionado: { default: null, required: true }
    }
};
</script>

<style>
/* Adicione estilos personalizados, se necessário */
</style>
