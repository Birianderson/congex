<template>
    <div class="card border-radius-termo shadow-termo">
        <div class="m-4">
            <div class="row">
                <div class="col-5">
                    <label for="valor-menor">Maior que:</label>
                    <input-money id="valor-menor" name="valorMenor" :modelValue="valorMenor"></input-money>
                </div>
                <div class="col-5">
                    <label for="valor-maior">Menor que:</label>
                    <input-money id="valor-maior" name="valorMaior" :modelValue="valorMaior"></input-money>
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
import {ref, computed, onMounted, inject} from 'vue';

export default {
    setup(props, {emit}) {
        const valorMenor = ref('0');  // Inicialize com '0' para permitir a filtragem inicial
        const valorMaior = ref('10000000000000');  // Inicialize com um valor alto para incluir todos os itens
        const selecionado = ref(0);
        const events = inject('events');

        const filtrar = () => {
            events.emit('selecionaCard', selecionado.value)
            events.emit('filter', selecionado.value)
            events.emit('filterValor', [valorMenor.value, valorMaior.value]);
        };

        onMounted(async () => {
            selecionado.value = props.selecionado
            events.on("selecionadoTransmit", (data) => {
                selecionado.value = parseInt(data);
                console.log(selecionado.value, 'valor filter')
            });
        });

        return {
            valorMenor,
            valorMaior,
            filtrar,
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
