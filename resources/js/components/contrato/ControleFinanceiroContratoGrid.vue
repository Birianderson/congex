<template>
    <div v-if="ready">
        <DataTable
            ref="mydatatable"
            id="contrato"
            :ajax="ajax"
            class="table table-hover table-responsive "
            width="100%"
            :options="options"
            :columns="columns"
            @draw="aplicarEventos"
        >
        </DataTable>
    </div>
</template>

<script lang="ts">
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import language from 'datatables.net-plugins/i18n/pt-BR.mjs';
import { inject, onMounted, ref } from 'vue';
import moment from 'moment';
DataTable.use(DataTablesCore);

export default {
    components: { DataTable, DataTablesCore },
    setup() {
        const events = inject('events');
        const ready = ref(false);
        const mydatatable = ref();
        let dt;

        const formatCurrency = (value) => {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(value);
        };

        const formatSituacao = (situacao) => {
            const situacaoMap = {
                'NV': 'Não Vigente',
                'V0': 'Contrato Inicial',
                'V1': '1° Termo Aditivo',
                'V2': '2° Termo Aditivo',
                'V3': '3° Termo Aditivo',
                'V4': '4° Termo Aditivo',
                'V5': '5° Termo Aditivo'
            };
            return situacaoMap[situacao] || situacao;
        };

        const columns = ref([
            { data: 'empresa.nome', title: 'Empresa', width: '20%' },
            { data: 'numero', title: 'Número', width: '8%' },
            {
                data: 'situacao',
                title: 'Situação',
                width: '10%',
                render: (data) => formatSituacao(data)
            },
            {
                data: 'valor_real',
                title: 'Montante do Contrato',
                width: '15%',
                render: (data) => formatCurrency(data)
            },
            {
                data: null,
                title: 'Ação',
                orderable: false,
                searchable: false,
                className: 'dt-action',
                width: '15%',
                render: (data, type, row) => {
                    return `
                        <button class="btn btn-sm btn-primary termo-btn" data-action="termo" data-id="${row.id}" data-nome="${row.empresa.nome}" data-numero="${row.numero}" data-bs-toggle="tooltip" data-bs-placement="top" title="Visualizar Termos"><i class="fa fa-search"></i></button>
                        <button class="btn btn-sm btn-primary termo-btn" data-action="arquivos" data-id="${row.id}" data-nome="${row.empresa.nome}" data-numero="${row.numero}" data-bs-toggle="tooltip" data-bs-placement="top" title="Arquivos"><i class="fa fa-paperclip"></i></button>
                        <a href="/termo/controle_financeiro/${parseInt(row.id)}" class="btn btn-sm btn-primary termo-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Termos do Contrato"><i class="fa fa-book"></i></a>
                    `;
                }
            }
        ]);

        const aplicarEventos = async () => {

            let termoelements = document.querySelectorAll("[data-action=termo]");
            termoelements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let id = evt.currentTarget.getAttribute('data-id');
                    let nome = evt.currentTarget.getAttribute('data-nome');
                    let numero = evt.currentTarget.getAttribute('data-numero');
                    events.emit('popup', {
                        title: `Visualizar Termos - ${nome} ${numero}`,
                        component: 'controle-financeiro-termo-grid',
                        size: 'xl',
                        data: {
                            id: `${id}`,
                        },
                    });
                });
            });

            let arquivoselements = document.querySelectorAll("[data-action=arquivos]");
            arquivoselements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let id = evt.currentTarget.getAttribute('data-id');
                    let nome = evt.currentTarget.getAttribute('data-nome');
                    let numero = evt.currentTarget.getAttribute('data-numero');
                    events.emit('popup', {
                        title: `Arquivos do Contrato - ${nome} ${numero}`,
                        component: 'form-arquivos',
                        size: 'xl',
                        data: {
                            id: `${id}`,
                            nome: `${nome}`,
                            numero: `${numero}`,
                            acao: '/contrato/create_arquivos/',
                            loadData: '/contrato/get_arquivos/',
                            fetchTipoArquivo: '/tipo-arquivo/getByTabela/contrato',
                            getDownloadUrl: '/contrato/download/'
                        },
                    });
                });
            });
        };

        const options = {
            serverSide: true,
            processing: true,
            language: language,
            layout: {
                topStart: 'search',
                topEnd: null,
                bottom2: 'info',
                bottomStart: 'pageLength',
                bottomEnd: 'paging'
            },
        };

        const ajax = '/contrato/list';

        onMounted(() => {
            ready.value = true;
            events.on('reload', (data) => {
                mydatatable.value.dt.ajax.url(`${ajax}`).load();
            });
        });

        return {
            ready, options, columns, ajax, mydatatable, aplicarEventos
        }
    }
}
</script>

<style>
@import '../../../../node_modules/datatables.net-bs5';

</style>
