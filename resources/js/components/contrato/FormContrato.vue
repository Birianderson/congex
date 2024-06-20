<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="row">
                <div class="col-6 mb-5">
                    <label for="numero" class="form-label">Numero do Contrato</label>
                    <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" />
                </div>
                <div class="col-6 mb-5">
                    <label for="fiscal" class="form-label">Fiscal do Contrato</label>
                    <input type="text" class="form-control @error('fiscal') is-invalid @enderror" id="fiscal" name="fiscal"/>
                </div>
            </div>
            <div class="">
                <label for="objeto" class="form-label">Objeto do Contrato</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="objeto"></textarea>
            </div>
            <div class="row">
                <div class="col-12 mb-5">
                    <input type="hidden" class="form-control @error('situacao') is-invalid @enderror" id="situacao" name="situacao" value="0"/>
                </div>
                <div class="row">
                    <div class="col-6 mb-5">
                        <label for="empresa" class="form-label">Empresa</label>
                        <input type="text" class="form-control @error('empresa') is-invalid @enderror" id="empresa" name="empresa"/>
                    </div>
                    <div class="col-6 mb-5">
                        <label for="cnpj" class="form-label">CNPJ</label>
                        <input type="text" v-mask="'##.###.###/####-##'" class="form-control @error('cnpj') is-invalid @enderror" id="cnpj" name="cnpj"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-5">
                        <label for="data_inicio" class="form-label">Início da Vigência</label>
                        <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" id="data_inicio" name="data_inicio"/>
                    </div>
                    <div class="col-6 mb-5">
                        <label for="data_fim" class="form-label">Fim da Vigência</label>
                        <input type="date" class="form-control @error('data_fim') is-invalid @enderror" id="data_fim" name="data_fim" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-5">
                        <label for="valor" class="form-label">Valor do Contrato</label>
                        <input type="text" v-money="money" class="form-control @error('valor') is-invalid @enderror" id="valor" name="valor">
                    </div>
                    <div class="col-6 mb-5">
                        <label for="observacao" class="form-label">Observação do Contrato</label>
                        <input type="text" class="form-control @error('observacao') is-invalid @enderror" id="observacao" name="observacao"/>
                    </div>
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
import { MaskDirective } from 'vue-the-mask';
import { VMoney } from 'v-money3';

export default {
    directives: {
        mask: MaskDirective,
        money: VMoney
    },
    setup(props, { emit }) {
        const events = inject('events');
        const info = ref({});
        const ready = ref(false);
        const acao = ref('/contrato/');
        const readOnly = ref(false);

        const money = {
            prefix: 'R$ ',
            thousands: '.',
            decimal: ',',
            precision: 2
        };

        const loadData = async () => {
            try {
                const response = await axios.get(`${acao.value}${props.data.id}`);
                acao.value += props.data.id;
                info.value = response.data;
            } catch (err) {
                emit('notification', {
                    type: 'error',
                    message: 'Não foi possível recuperar os dados da Categoria.'
                });
            }
            ready.value = true;
        }
        const close = () => {
            events.emit('popup-close', true);
        }

        onMounted(async () => {
            console.log(props);
            events.off("form-submitted");
            events.on("form-submitted", (sucesso) => {
                if (sucesso) {
                    events.emit('reload', true);
                    events.emit('notification', {
                        type: 'success',
                        message: 'Empresa salva com Sucesso!'
                    });
                    emit('reload');
                    emit('close', true);
                }
            });
            if (props.data) {
                await loadData();
            } else {
                ready.value = true;
            }

        })

        return {
            info, ready, acao, readOnly, close, money
        }

    },

    props: {
        data: {default: null, required: true}
    }

}
</script>

<style>
.form-label {
    font-weight: bold;
}

.form-control {
    border-radius: 0.25rem;
}

.btn {
    margin: 0.5rem;
}
</style>
