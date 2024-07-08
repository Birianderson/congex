<template>
    <div class="m-2" v-if="ready">
        <form id="frm" name="frm" data-method="post" :action="acao">
            <form-error></form-error>
            <div class="row">
                <div v-if="tipoArquivo.length" class="col-12">
                    <div class="mb-3 p-3" v-for="documento in tipoArquivo" :key="documento.nome">
                        <div class="row">
                            <div class="col-4">
                                <label :for="documento.nome" class="form-label fw-bold">{{ documento.nome }}</label>
                                <br>
                                <input type="file" class="form-control border-radius-termo mb-2" :id="documento.nome" name="arquivos[]"/>
                                <div v-if="getArquivoInfo(documento.id, 'nome')">
                                    Arquivo atual:
                                    <a :href="getViewUrl(getArquivoInfo(documento.id, 'path'))" target="_blank">Visualizar</a>
                                    ou
                                    <a :href="getDownloadUrl(getArquivoInfo(documento.id, 'path'))" target="_blank">Download</a>
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="nome[]" class="form-label">Nome do Arquivo</label>
                                <input type="text" class="form-control form-control-sm mb-2 border-radius-termo"
                                       :id="`${documento.nome}`" name="nome[]" :value="getArquivoInfo(documento.id, 'nome')"/>
                            </div>
                            <div class="col-4">
                                <label for="descricao[]" class="form-label">Descrição do Arquivo</label>
                                <input type="text" class="form-control form-control-sm mb-2 border-radius-termo"
                                       :id="`descricao-${documento.nome}`" name="descricao[]" :value="getArquivoInfo(documento.id, 'descricao')"/>
                            </div>
                        </div>
                        <input type="hidden" name="tipo_arquivo_id[]" :value="documento.id">
                    </div>
                </div>
                <div v-else>
                    <div class="text-center">Adicione Arquivos em Configuração</div>
                </div>
                <input type="hidden" name="contrato_id" :value="props.data.id">
                <div class="row border-top pt-4">
                    <div class="col-12 d-flex justify-content-center align-items-center" v-if="!tipoArquivo.length">
                        <button type="button" class="btn btn-danger text-white" @click="close" aria-label="Close">
                            <i class="fa fa-close"></i> Sair
                        </button>
                    </div>
                    <div class="col-12 text-center" v-if="tipoArquivo.length">
                        <submit-button></submit-button>
                        &nbsp;
                        <button type="button" class="btn btn-secondary text-white" @click="close" aria-label="Close">
                            <i class="fa fa-close"></i> Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script lang="ts">
import axios from 'axios';
import {inject, onMounted, ref} from 'vue';

export default {
    setup(props, {emit}) {
        const events = inject('events');
        const info = ref([]);
        const ready = ref(false);
        const acao = ref('/contrato/create_arquivos/');
        const readOnly = ref(false);
        const tipoArquivo = ref([]);
        const loadDataParam = ref('');
        const fetchTipoArquivoParam = ref('');
        const getDownloadUrlParam = ref('');
        const loadData = async () => {
            try {
                console.log(`${loadDataParam.value}`)
                const response = await axios.get(`${loadDataParam.value}${props.data.id}`);
                acao.value += props.data.id;
                info.value = response.data;
                console.log(info.value);
            } catch (err) {
                emit('notification', {
                    type: 'error',
                    message: 'Não foi possível recuperar os dados do Contrato.'
                });
            }
            ready.value = true;
        };

        const close = () => {
            events.emit('popup-close', true);
        };

        const fetchTipoArquivo = async () => {
            try {
                const response = await axios.get(`${fetchTipoArquivoParam.value}`);
                tipoArquivo.value = response.data;
            } catch (error) {
                console.error('Erro ao buscar Tipos de Arquivo:', error);
                tipoArquivo.value = [];
            }
        };

        const getArquivoInfo = (tipoArquivoId, field) => {
            const arquivo = info.value.find(item => item.tipo_arquivo_id === tipoArquivoId);
            return arquivo ? arquivo[field] : '';
        };

        const getViewUrl = (path) => {
            return `/storage/${path.replace('public/', '')}`;
        };

        const getDownloadUrl = (path) => {
            const encodedPath = btoa(path.replace('public/', ''));
            return `${getDownloadUrlParam.value}${encodedPath}`;
        };

        onMounted(async () => {
            loadDataParam.value = props.data.loadData
            fetchTipoArquivoParam.value = props.data.fetchTipoArquivo
            getDownloadUrlParam.value = props.data.getDownloadUrl
            acao.value = props.data.acao
            await fetchTipoArquivo();
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
                await loadData();
            } else {
                info.value.termos = [{}];
                ready.value = true;
            }
        });

        return {
            props, info, ready, acao, readOnly, close, tipoArquivo, getArquivoInfo, getViewUrl, getDownloadUrl
        };
    },
    props: {
        data: {default: null, required: true}
    }
};
</script>

<style>
/* Adicione estilos personalizados, se necessário */
</style>
