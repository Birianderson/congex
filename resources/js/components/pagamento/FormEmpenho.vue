<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="exercicio" class="form-label" :class="{ 'required': !readOnly }">Exercício</label>
                    <input type="text" class="form-control" id="exercicio" name="exercicio"  />
                </div>
                <div class="col-6 mb-4">
                    <label for="data_vigencia" class="form-label" :class="{ 'required': !readOnly }">Data da Vigência</label>
                    <input type="date" class="form-control" id="data_vigencia" name="data_vigencia" />
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="termo_de_referencia" class="form-label" :class="{ 'required': !readOnly }">Termo de Referência</label>
                    <input type="text" class="form-control" id="termo_de_referencia" name="termo_de_referencia" />
                </div>
                <div class="col-6 mb-4">
                    <label for="empenho" class="form-label" :class="{ 'required': !readOnly }">Número do Empenho</label>
                    <input type="text" class="form-control" id="empenho" name="empenho" />
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="valor_empenho" class="form-label" :class="{ 'required': !readOnly }">Valor Empenhado</label>
                    <input-money id="valor_empenho" name="valor_empenho"></input-money>
                </div>
                <div class="col-6 mb-4">
                    <label for="valor_liquidacao" class="form-label" :class="{ 'required': !readOnly }">Valor Liquidado</label>
                    <input-money id="valor_liquidacao" name="valor_liquidacao" ></input-money>
                </div>
            </div>
            <input type="hidden" :value="props.data" name="termo_id">
            <div class="row">
                <div class="col-12 mb-4">
                    <label for="observacao" class="form-label" :class="{ 'required': !readOnly }">Histórico</label>
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
