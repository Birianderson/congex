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
import { inject, onMounted, ref } from 'vue';
import moment from "moment/moment";
DataTable.use(DataTablesCore);

export default {
    components: { DataTable, DataTablesCore },
    setup(props) {
        const events = inject('events');
        const ready = ref(false);
        const mydatatable = ref();
        const ajax = ref();
        const columnsSelected = ref([])
        const optionsSelected = ref([])
        let dt;

        const formatCurrency = (value) => {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(value);
        };

        const columns = ref([
            {
                data: 'data_pagamento',
                title: 'Data de Pagamento',
                width: '10%',
                render: (data) => formatDate(data)
            },
            {
                data: 'liquidacao',
                title: 'Liquidação',
                width: '10%',
            },
            {
                data: 'nfe',
                title: 'Número da NFE',
                width: '15%',
            },
            {
                data: 'ordem_servico',
                title: 'Ordem de Seviço',
                width: '15%',
            },
            {
                data: 'ci',
                title: 'CI',
                width: '15%',
            },
            {
                data: 'valor',
                title: 'Valor',
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
                    <button class="btn btn-sm btn-primary edit-btn" data-action="edit" data-id="${row.id}" data-numero="${row.nfe}" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"><i class="fa fa-pencil"></i></button>
                     <button class="btn btn-sm btn-primary termo-btn" data-action="arquivos" data-id="${row.id}" data-numero="${row.nfe}"  data-bs-toggle="tooltip" data-bs-placement="top" title="Arquivos"><i class="fa fa-paperclip"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn" data-action="delete" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Deletar"><i class="fa fa-trash" ></i></button>
                    `;
                }
            }
        ]);

        const formatDate = (date) => {
            return moment(date).format('DD/MM/YYYY');
        };

        const columnsReadOnly = ref([
            {
                data: 'data_pagamento',
                title: 'Data de Pagamento',
                width: '10%',
                sortable: false,
                render: (data) => formatDate(data)
            },
            {
                data: 'liquidacao',
                title: 'Liquidação',
                sortable: false,
                width: '10%',
            },
            {
                data: 'nfe',
                title: 'Número da NFE',
                sortable: false,
                width: '15%',
            },
            {
                data: 'ordem_servico',
                title: 'Ordem de Seviço',
                sortable: false,
                width: '15%',
            },
            {
                data: 'ci',
                title: 'CI',
                sortable: false,
                width: '15%',
            },
            {
                data: 'valor',
                title: 'Valor',
                width: '15%',
                sortable: false,
                render: (data) => formatCurrency(data)
            },
        ]);

        const aplicarEventos = async () => {

            let editelements = document.querySelectorAll("[data-action=edit]");
            editelements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let numero = evt.currentTarget.getAttribute('data-numero');
                    let id = evt.currentTarget.getAttribute('data-id');
                    events.emit('popup', {
                        title: `Editar Nota Fiscal - ${numero}`,
                        component: 'form-nota-fiscal',
                        size: 'xl',
                        data: {
                            id_nota: `${id}`,
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
                        title: `Deletar Nota Fiscal`,
                        component: 'popup-delete',
                        data: {
                            acao: '/nota-fiscal/delete/',
                            id: `${id}`,
                        },
                    });
                })
            })

            let arquivoselements = document.querySelectorAll("[data-action=arquivos]");
            arquivoselements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let id = evt.currentTarget.getAttribute('data-id');
                    let numero = evt.currentTarget.getAttribute('data-numero');
                    events.emit('popup', {
                        title: `Arquivos da Nota Fiscal - ${numero}`,
                        component: 'form-arquivos',
                        size: 'xl',
                        data: {
                            id: `${id}`,
                            numero: `${numero}`,
                            acao: '/nota-fiscal/create_arquivos/',
                            loadData: '/nota-fiscal/get_arquivos/',
                            fetchTipoArquivo: '/tipo-arquivo/getByTabela/nota_fiscal',
                            getDownloadUrl: '/nota-fiscal/download/'
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
            console.log(props)
            ready.value = true;
            if (props.data.id){
                ajax.value = `/nota-fiscal/list/${props.data.id}`;
                columnsSelected.value = columnsReadOnly;
                optionsSelected.value = optionsReadOnly;

            }else {
                ajax.value = `/nota-fiscal/list/${props.data}`;
                columnsSelected.value = columns;
                optionsSelected.value = options;
            }
            events.on('reload', (data) => {
                mydatatable.value.dt.ajax.url(`${ajax.value}`).load();
            });
        });


        return {
            ready, options, columns, ajax, mydatatable, aplicarEventos, ajax, columnsSelected, optionsSelected
        }
    },
    props: {
        data: { default: null, required: true }
    }
}
</script>

<style>
@import '../../../../node_modules/datatables.net-bs5';

</style>
