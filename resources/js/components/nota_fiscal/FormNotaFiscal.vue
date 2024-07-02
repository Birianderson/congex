<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="valor" class="form-label" :class="{ 'required': !readOnly }">Valor</label>
                    <input-money id="valor" name="valor" :value="info.valor"></input-money>
                </div>
                <div class="col-6 mb-4">
                    <label for="data_pagamento" class="form-label" :class="{ 'required': !readOnly }">Data do Pagamento</label>
                    <input type="date" class="form-control" id="data_pagamento" name="data_pagamento" v-model="info.data_pagamento"/>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="liquidacao" class="form-label" :class="{ 'required': !readOnly }">Número Liquidação </label>
                    <input type="text" class="form-control" id="liquidacao" name="liquidacao" v-model="info.liquidacao" />
                </div>
                <div class="col-6 mb-4">
                    <label for="data_liquidacao" class="form-label" :class="{ 'required': !readOnly }">Data da Liquidação</label>
                    <input type="date" class="form-control" id="data_liquidacao" name="data_liquidacao" v-model="info.data_liquidacao" />
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="ordem_servico" class="form-label" :class="{ 'required': !readOnly }">Ordem de Serviço </label>
                    <input type="text" class="form-control" id="ordem_servico" name="ordem_servico" v-model="info.ordem_servico"/>
                </div>
                <div class="col-6 mb-4">
                    <label for="ci" class="form-label" :class="{ 'required': !readOnly }">CI</label>
                    <input type="text" class="form-control" id="ci" name="ci" v-model="info.ci"/>
                </div>
            </div>
            <input type="hidden" :value="props.data" name="empenho_id">
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="observacao" class="form-label" :class="{ 'required': !readOnly }">Observação</label>
                    <input type="text" class="form-control" id="observacao" name="observacao" v-model="info.observacao"/>
                </div>
                <div class="col-6 mb-4">
                    <label for="nfe" class="form-label" :class="{ 'required': !readOnly }">Nota Fiscal Eletrônica</label>
                    <input type="text" class="form-control" id="nfe" name="nfe" v-model="info.nfe" />
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
import { inject, onMounted, ref } from 'vue';
import axios from "axios";

export default {
    setup(props, { emit }) {
        const events = inject('events');
        const ready = ref(false);
        const acao = ref('/nota-fiscal/');
        const readOnly = ref(false);
        const info = ref({});

        const close = () => {
            events.emit('popup-close', true);
        }

        const loadData = async () => {
            try {
                const response = await axios.get(`${acao.value}${props.data.id_nota}`);
                acao.value += props.data.id_nota;
                info.value = response.data;
            } catch (err) {
                emit('notification', {
                    type: 'error',
                    message: 'Não foi possível recuperar os dados do Contrato.'
                });
            }
            ready.value = true;
        }

        onMounted(async () => {
            events.off("form-submitted");
            events.on("form-submitted", (sucesso) => {
                if (sucesso) {
                    events.emit('reload', true);
                    events.emit('notification', {
                        type: 'success',
                        message: 'Nota Fiscal salva com Sucesso!'
                    });
                    emit('reload');
                    emit('close', true);
                }
            });
            if (props.data.id_nota) {
                await loadData();
            } else {
                ready.value = true;
            }
        });

        return {
            ready, acao, readOnly, close, props, info
        }
    },
    props: {
        data: { default: null, required: true }
    }
}
</script>

<style>

</style>
