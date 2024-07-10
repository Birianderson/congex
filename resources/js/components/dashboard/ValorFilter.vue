<template>
    <div class="card border-radius-termo shadow-termo">
        <div class="m-4">
            <div class="row">
                <div class="col-5">
                    <label for="valor-menor">Maior que:</label>
                    <input type="text" id="valor-menor" v-model="valorMenor" @input="formatValorMenor" class="form-control" placeholder="R$ 0">
                </div>
                <div class="col-5">
                    <label for="valor-maior">Menor que:</label>
                    <input type="text" id="valor-maior" v-model="valorMaior" @input="formatValorMaior" class="form-control" placeholder="R$ 10.000">
                </div>
                <div class="col-2 align-content-end text-end">
                    <button type="button" class="btn btn-primary me-1" @click="filtrar">
                        Filtrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, inject } from 'vue';

export default {
    setup(props, { emit }) {
        const valorMenor = ref('0');
        const valorMaior = ref('0');
        const selecionado = ref(0);
        const events = inject('events');

        const formatCurrency = (value) => {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
                minimumFractionDigits: 0
            }).format(value.replace(/\D/g, ''));
        };

        const formatValorMenor = () => {
            valorMenor.value = formatCurrency(valorMenor.value);
        };

        const formatValorMaior = () => {
            valorMaior.value = formatCurrency(valorMaior.value);
        };

        const filtrar = () => {
            const valorMenorNumerico = valorMenor.value.replace(/\D/g, '');
            const valorMaiorNumerico = valorMaior.value.replace(/\D/g, '');
            events.emit('selecionaCard', selecionado.value);
            events.emit('filter', selecionado.value);
            events.emit('filterValor', [valorMenorNumerico, valorMaiorNumerico, selecionado]);
        };

        onMounted(() => {
            selecionado.value = props.selecionado;
            formatValorMenor();
            formatValorMaior();
            events.on("selecionadoTransmit", (data) => {
                selecionado.value = parseInt(data);
            });
        });

        return {
            valorMenor,
            valorMaior,
            filtrar,
            formatValorMenor,
            formatValorMaior
        };
    },
    props: {
        selecionado: {default: null, required: true}
    }
};
</script>

<style>
/* Adicione estilos personalizados, se necessário */
</style>
