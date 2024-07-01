<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="valor" class="form-label" :class="{ 'required': !readOnly }">Valor</label>
                    <input-money id="valor" name="valor"></input-money>
                </div>
                <div class="col-6 mb-4">
                    <label for="data_pagamento" class="form-label" :class="{ 'required': !readOnly }">Data do Pagamento</label>
                    <input type="date" class="form-control" id="data_pagamento" name="data_pagamento" />
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="liquidacao" class="form-label" :class="{ 'required': !readOnly }">Número Liquidação </label>
                    <input type="text" class="form-control" id="liquidacao" name="liquidacao" />
                </div>
                <div class="col-6 mb-4">
                    <label for="data_liquidacao" class="form-label" :class="{ 'required': !readOnly }">Data da Liquidação</label>
                    <input type="date" class="form-control" id="data_liquidacao" name="data_liquidacao" />
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="ordem_servico" class="form-label" :class="{ 'required': !readOnly }">Ordem de Serviço </label>
                    <input type="text" class="form-control" id="ordem_servico" name="ordem_servico" />
                </div>
                <div class="col-6 mb-4">
                    <label for="ci" class="form-label" :class="{ 'required': !readOnly }">CI</label>
                    <input type="text" class="form-control" id="ci" name="ci" />
                </div>
            </div>
            <input type="hidden" :value="props.data" name="pagamento_id">
            <div class="row">
                <div class="col-12 mb-4">
                    <label for="observacao" class="form-label" :class="{ 'required': !readOnly }">Observação</label>
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
        const acao = ref('/pagamento/');
        const readOnly = ref(false);

        const close = () => {
            events.emit('popup-close', true);
        }

        onMounted(async () => {
            console.log(props.data);
            events.off("form-submitted");
            events.on("form-submitted", (sucesso) => {
                if (sucesso) {
                    events.emit('reload', true);
                    events.emit('notification', {
                        type: 'success',
                        message: 'Empenho salvo com Sucesso!'
                    });
                    emit('reload');
                    emit('close', true);
                }
            });
            ready.value = true;
        });

        return {
            ready, acao, readOnly, close, props,
        }
    },
    props: {
        data: { default: null, required: true }
    }
}
</script>

<style>

</style>
