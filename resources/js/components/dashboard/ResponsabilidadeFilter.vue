<template>
    <div class="card border-radius-termo shadow-termo">
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
                    <select class="form-select" id="cargo" v-model="selectedCargo" :disabled="isButtonDisabled"
                            name="cargo">
                        <option disabled selected value="">Todos</option>
                        <option v-for="cargo in cargos" :key="cargo.id" :value="cargo.id">
                            {{ cargo.nome }}
                        </option>
                    </select>
                </div>
                <div class="col-2 align-content-end text-end">
                    <button type="button" class="btn me-1 text-white" :class="buttonClass" @click="filtrar">
                        Filtrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import axios from 'axios';
import {ref, onMounted, inject, computed} from 'vue';

export default {
    props: {
        selecionado: {default: null, required: true}
    },
    setup(props, {emit}) {
        const selectedPessoa = ref('');
        const selectedCargo = ref('');
        const pessoas = ref([]);
        const cargos = ref([]);
        const events = inject('events');
        const selecionado = ref(props.selecionado);

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

        const filtrar = () => {
            if (selectedCargo.value === '') {
                events.emit('selecionaCard', selecionado.value);
                events.emit('filter', selecionado.value);
                events.emit('filterPessoa', [selectedPessoa.value, selecionado.value]);
            }
            if(selectedCargo.value !== '') {
                events.emit('selecionaCard', selecionado.value);
                events.emit('filter', selecionado.value);
                events.emit('filterResponsabilidade', [selectedPessoa.value, selectedCargo.value, selecionado.value]);
            }
            if (selectedPessoa.value === '' && selectedCargo.value === ''){
                events.emit('selecionaCard', selecionado.value);
                events.emit('filter', selecionado.value);
            }
        };

        const buttonClass = computed(() => `background-color-chart-${selecionado.value}`);
        const isButtonDisabled = computed(() => selectedPessoa.value === '');

        onMounted(async () => {
            await fetchPessoas();
            await fetchCargos();
            events.on("selecionadoTransmit", (data) => {
                selecionado.value = parseInt(data);
            });
            events.on("clearResponsabilidade", () => {
                selectedPessoa.value = '';
                selectedCargo.value = '';
            });
        });

        return {
            selectedPessoa,
            selectedCargo,
            pessoas,
            selecionado,
            isButtonDisabled,
            cargos,
            filtrar,
            buttonClass
        };
    }
};

</script>

<style>
/* Adicione estilos personalizados, se necessário */
</style>
