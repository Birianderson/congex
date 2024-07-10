<template>
    <div v-if="ready">
        <DataTable
            ref="mydatatable"
            id="contrato"
            :ajax="ajax"
            class="table table-hover table-responsive "
            width="100%"
            :options="optionsSelected"
            :columns="columnsSelected"
            @draw="aplicarEventos"
        >
        </DataTable>
    </div>
</template>

<script lang="ts">
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import language from 'datatables.net-plugins/i18n/pt-BR.mjs';
import {inject, onMounted, ref} from 'vue';
DataTable.use(DataTablesCore);

export default {
    components: { DataTable, DataTablesCore },
    setup(props) {
        const events = inject('events');
        const ready = ref(false);
        const mydatatable = ref();
        const ajax = ref();
        const columnsSelected = ref([]);
        const optionsSelected = ref([]);

        const formatCurrency = (value) => {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(value);
        };

        const formatSituacao = (situacao) => {
            const situacaoMap = {
                '0': 'Contrato Inicial',
                '1': '1° Termo Aditivo',
                '2': '2° Termo Aditivo',
                '3': '3° Termo Aditivo',
                '4': '4° Termo Aditivo',
                '5': '5° Termo Aditivo'
            };
            return situacaoMap[situacao] || situacao;
        };

        const renderProgressBar = (valorPago, valorTotal) => {
            const percentage = (valorPago / valorTotal) * 100;
            let progressBarColor = '';

            if (percentage <= 30) {
                progressBarColor = 'bg-danger';
            } else if (percentage <= 60) {
                progressBarColor = 'bg-warning';
            } else {
                progressBarColor = 'bg-success';
            }

            return `
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar ${progressBarColor}" role="progressbar" style="width: ${percentage}%;" aria-valuenow="${percentage}" aria-valuemin="0" aria-valuemax="100">${percentage.toFixed(2)}%</div>
                </div>
            `;
        };

        const columns = ref([
            {
                data: 'numero',
                title: 'Termo',
                width: '10%',
                sortable: false,
                render: (data) => formatSituacao(data)
            },
            {
                data: 'valor',
                title: 'Montante do Contrato',
                width: '15%',
                sortable: false,
                render: (data) => formatCurrency(data)
            },
            {
                data: null,
                title: 'Progresso do Pagamento',
                sortable: false,
                searchable: false,
                className: 'dt-center',
                width: '20%',
                render: (data, type, row) => renderProgressBar(row.valor_pago, row.valor)
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
                        <button class="btn btn-sm btn-primary termo-btn" data-action="empenho" data-id="${row.id}" data-numero="${row.numero}" data-bs-toggle="tooltip" data-bs-placement="top" title="Visualizar Empenhos"><i class="fa fa-search"></i></button>
                        <button class="btn btn-sm btn-primary termo-btn" data-action="arquivos" data-id="${row.id}" data-numero="${row.numero}" data-bs-toggle="tooltip" data-bs-placement="top" title="Arquivos"><i class="fa fa-paperclip"></i></button>
                        <a href="/empenho/controle_financeiro/${parseInt(row.contrato_id)}/termo/${row.id}" class="btn btn-sm btn-primary termo-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Empenhos"><i class="fa fa-folder"></i></a>
                        <button class="btn btn-sm btn-primary edit-btn" data-action="edit" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" data-action="delete" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Remover"><i class="fa fa-trash"></i></button>
                    `;
                }
            }
        ]);

        const aplicarEventos = async () => {
            let empenhoelements = document.querySelectorAll("[data-action=empenho]");
            empenhoelements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let id = evt.currentTarget.getAttribute('data-id');
                    events.emit('popup', {
                        title: `Visualizar Empenhos`,
                        component: 'controle-financeiro-empenho-grid',
                        size: 'xl',
                        data: { id: `${id}` },
                    });
                });
            });

            let editelements = document.querySelectorAll("[data-action=edit]");
            editelements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let id = evt.currentTarget.getAttribute('data-id');
                    events.emit('popup', {
                        title: 'Editar Contrato',
                        component: 'form-termo',
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
                        title: `Arquivos do ${formatSituacao(numero)}`,
                        component: 'form-arquivos',
                        size: 'xl',
                        data: {
                            id: `${id}`,
                            nome: `${nome}`,
                            numero: `${numero}`,
                            acao: '/termo/create_arquivos/',
                            loadData: '/termo/get_arquivos/',
                            fetchTipoArquivo: '/tipo-arquivo/getByTabela/termo',
                            getDownloadUrl: '/termo/download/'
                        },
                    });
                });
            });

            let deleteElements = document.querySelectorAll("[data-action=delete]");
            deleteElements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let id = evt.currentTarget.getAttribute('data-id');
                    events.emit('loading', true);
                    events.emit('popup', {
                        title: `Deletar Contrato`,
                        component: 'popup-delete',
                        data: {
                            acao: '/termo/delete/',
                            id: `${id}`,
                        },
                    });
                });
            });
        };


        const columnsReadOnly = ref([
            {
                data: 'numero',
                title: 'Termo',
                width: '10%',
                sortable: false,
                render: (data) => formatSituacao(data)
            },
            {
                data: 'valor',
                title: 'Montante do Contrato',
                width: '15%',
                sortable: false,
                render: (data) => formatCurrency(data)
            },
            {
                data: null,
                title: 'Progresso do Pagamento',
                sortable: false,
                searchable: false,
                className: 'dt-center',
                width: '20%',
                render: (data, type, row) => renderProgressBar(row.valor_pago, row.valor)
            }
        ]);

        const options = {
            serverSide: true,
            processing: true,
            language: language,
            layout: {
                topStart: null,
                topEnd: null,
                bottom2: null,
                bottomStart: null,
                bottomEnd: null
            },
        };

        const optionsReadOnly = {
            serverSide: true,
            processing: true,
            language: language,
            layout: {
                topStart: null,
                topEnd: null,
                bottom2: null,
                bottomStart: null,
                bottomEnd: null
            },
        };

        onMounted(() => {
            ready.value = true;
            if (props.data.id) {
                ajax.value = `/termo/getByContratoId/${props.data.id}`;
                columnsSelected.value = columnsReadOnly;
                optionsSelected.value = optionsReadOnly;
            } else {
                ajax.value = `/termo/getByContratoId/${props.data}`;
                columnsSelected.value = columns;
                optionsSelected.value = options;
            }
            events.on('reload', () => {
                mydatatable.value.dt.ajax.url(ajax.value).load();
            });
        });

        return {
            ready, options, columns, ajax, mydatatable, columnsSelected, optionsSelected, aplicarEventos
        }
    },
    props: {
        data: { default: null, required: true }
    }
}
</script>


<style>
@import '../../../../node_modules/datatables.net-bs5';

.progress-bar {
    transition: width 0.6s ease;
}
</style>

