<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="numero" class="form-label">Número</label>
                    <input type="text" class="form-control" id="numero" name="numero" />
                </div>
                <div class="col-6 mb-4">
                    <label for="empresa_id" class="form-label">Empresa</label>
                    <input type="hidden" name="empresa_id" id="empresa_id" v-model="info.empresa_id" :data-error-class="`empresa-${id}`"/>
                    <input-autocomplete v-if="!info.id" @selectItem="(e) => info.empresa_id = e.id" data-url="/empresa/get-by-query?q=" data-label="nome" data-value="id" :class="`empresa-${id}`"></input-autocomplete>
                </div>
            </div>
            <div class="">
                <label for="objeto" class="form-label">Objeto</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="objeto"></textarea>
            </div>
            <div class="row">
                <div class="col-12 mb-4">
                    <input type="hidden" class="form-control " id="situacao" name="situacao" value="0"/>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="data_inicio" class="form-label">Início da Vigência</label>
                    <input type="date" class="form-control " id="data_inicio" name="data_inicio"/>
                </div>
                <div class="col-6 mb-4">
                    <label for="data_fim" class="form-label">Fim da Vigência</label>
                    <input type="date" class="form-control " id="data_fim" name="data_fim" />
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-4">
                    <label for="valor" class="form-label">Valor</label>
                    <input-money id="valor" name="valor"></input-money>
                </div>
                <div class="col-6 mb-4">
                    <label for="observacao" class="form-label">Observação</label>
                    <input type="text" class="form-control " id="observacao" name="observacao"/>
                </div>
            </div>
            <select-responsabilidade></select-responsabilidade>
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
import InputAutocomplete from "../common/InputAutocomplete.vue";
import SelectResponsabilidade from "../responsabilidade/SelectResponsabilidade.vue";

export default {
    components: {SelectResponsabilidade, InputAutocomplete},
    setup(props, { emit }) {
        const events = inject('events');
        const info = ref({});
        const ready = ref(false);
        const acao = ref('/contrato/');
        const readOnly = ref(false);
        const empresas = ref([]);
        const selectedEmpresa = ref(null);

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

        const fetchEmpresas = async (query) => {
            if (query.length < 2) return;
            try {
                const response = await axios.get('/empresa/list', {
                    params: { search: query }
                });
                empresas.value = response.data;
            } catch (error) {
                console.error('Erro ao buscar empresas:', error);
                empresas.value = [];
            }
        };

        onMounted(async () => {
            events.on('select', (data) => {
                console.log(data, 'id')
                info.value.empresa_id = parseInt(data);
            });
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
        });

        return {
            info, ready, acao, readOnly, close, empresas, selectedEmpresa, fetchEmpresas
        }
    },
    props: {
        data: { default: null, required: true }
    }
}
</script>

<style>
/* Adicione estilos personalizados, se necessário */
</style>
