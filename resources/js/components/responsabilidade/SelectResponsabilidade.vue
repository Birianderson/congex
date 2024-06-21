<template>
    <div>
        <div v-for="(entry, index) in entries" :key="index" class="mb-4">
            <div class="row">
                <div class="col">
                    <label :for="'pessoa-' + index">Pessoa</label>
                    <select class="form-select" :id="'pessoa-' + index" v-model="entry.pessoa" name="pessoa[]">
                        <option disabled value="">Selecione uma pessoa</option>
                        <option v-for="pessoa in pessoas" :key="pessoa.id" :value="pessoa.id">
                            {{ pessoa.nome }}
                        </option>
                    </select>
                </div>
                <div class="col">
                    <label :for="'cargo-' + index">Cargo</label>
                    <select class="form-select" :id="'cargo-' + index" v-model="entry.cargo" name="cargo[]">
                        <option disabled value="">Selecione um cargo</option>
                        <option v-for="cargo in cargos" :key="cargo.id" :value="cargo.id">
                            {{ cargo.nome }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <button type="button" class="btn btn-primary me-1 " @click="addEntry">+</button>
            <button :disabled="disabled" type="button" class="btn btn-danger" @click="removeEntry">-</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import {ref, onMounted, computed} from 'vue';

export default {
    setup() {
        const entries = ref([{pessoa: '', cargo: ''}]);
        const pessoas = ref([]);
        const cargos = ref([]);
        const disabled = ref(true);

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

        onMounted(async () => {
            await fetchPessoas();
            await fetchCargos();
        });

        const addEntry = () => {
            entries.value.push({pessoa: '', cargo: ''});
            disabled.value = false;
        }

        const removeEntry = () => {
            entries.value.splice(1);
            if (entries.value.length === 1) {
                disabled.value = true
            }
        };

        return {
            entries,
            disabled,
            cargos,
            pessoas,
            addEntry,
            removeEntry,
        };
    },
};
</script>

<style>
/* Adicione estilos personalizados, se necessário */
</style>
