<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="data_inicio" class="form-label" :class="{ 'required': !readOnly }">Início da Vigência</label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" :min="min_data_fim" v-model="min_data_fim" />
                </div>
                <div class="col-6 mb-4">
                    <label for="data_fim" class="form-label" :class="{ 'required': !readOnly }">Fim da Vigência</label>
                    <input type="date" class="form-control" id="data_fim" name="data_fim" v-model="data_fim" />
                </div>
            </div>
            <input type="hidden" :value="props.data.id" name="contrato_id">
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="valor" class="form-label" :class="{ 'required': !readOnly }">Valor</label>
                    <input-money id="valor" name="valor" :value="valor"></input-money>
                </div>
                <div class="col-6 mb-4">
                    <label for="observacao" class="form-label">Observação</label>
                    <input type="text" class="form-control" id="observacao" name="observacao" />
                </div>
            </div>
            <div class="row border-top pt-4">
                <div class="col-12 d-flex justify-content-center align-items-center" v-if="readOnly">
                    <button type="button" class="btn btn-danger text-white" @click="close" aria-label="Close">
                        <i class="fa fa-close"></i> Sair
                    </button>
                </div>
                <div class="col-12 text-center" v-if="!readOnly">
                    <submit-button></submit-button>
                    &nbsp;
                    <button type="button" class="btn btn-secondary text-white" @click="close" aria-label="Close">
                        <i class="fa fa-close"></i> Cancelar
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script lang="ts">
import axios from 'axios';
import { inject, onMounted, ref } from 'vue';
import moment from 'moment';

export default {
    setup(props, { emit }) {
        const events = inject('events');
        const ready = ref(false);
        const acao = ref('/termo/');
        const readOnly = ref(false);
        const contrato = ref({});
        const data_fim = ref({});
        const min_data_fim = ref({});
        const valor = ref({});

        const close = () => {
            events.emit('popup-close', true);
        }

        const fetchContrato = async () => {
            try {
                const response = await axios.get(`/contrato/${props.data.id}`);
                contrato.value = response.data;
                console.log(contrato.value)
                if (contrato.value.termos.length > 0) {
                    min_data_fim.value = contrato.value.termos[contrato.value.termos.length - 1].data_fim
                    data_fim.value = moment(min_data_fim.value).add(1, 'year').format('YYYY-MM-DD');
                    valor.value = contrato.value.termos[contrato.value.termos.length - 1].valor

                } else {
                    min_data_fim.value = contrato.value.data_fim
                    data_fim.value = moment(min_data_fim.value).add(1, 'year').format('YYYY-MM-DD');
                    valor.value = contrato.value.valor
                }

                ready.value = true;
            } catch (error) {
                console.error('Erro ao buscar contrato:', error);
            }
        };

        onMounted(async () => {
            events.off("form-submitted");
            events.on("form-submitted", (sucesso) => {
                if (sucesso) {
                    events.emit('reload', true);
                    events.emit('notification', {
                        type: 'success',
                        message: 'Contrato salvo com Sucesso!'
                    });
                    emit('reload');
                    emit('close', true);
                }
            });
            if (props.data) {
                await fetchContrato();
            } else {
                ready.value = true;
            }
        });

        return {
            ready, acao, readOnly, close, fetchContrato, props, contrato, data_fim, min_data_fim, valor
        }
    },
    props: {
        data: { default: null, required: true }
    }
}
</script>

<style>

</style>
