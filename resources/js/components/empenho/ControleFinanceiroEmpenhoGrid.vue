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
                data: 'exercicio',
                title: 'Exercício',
                width: '10%',
            },
            {
                data: 'termo_de_referencia',
                title: 'Termo de Referência',
                width: '10%',
            },
            {
                data: 'empenho',
                title: 'Número Empenho',
                width: '15%',
            },
            {
                data: 'valor_empenho',
                title: 'Valor Empenhado',
                width: '15%',
                render: (data) => formatCurrency(data)
            },
            {
                data: 'valor_liquidacao',
                title: 'Valor Liquidado',
                width: '15%',
                render: (data) => formatCurrency(data)
            },
            {
                data: 'valor_total_pago',
                title: 'Valor Pago',
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
                    <button class="btn btn-sm btn-secondary termo-btn" data-action="visualizar" data-id="${row.id}" data-numero="${row.empenho}" data-bs-toggle="tooltip" data-bs-placement="top" title="Visualizar Notas Fiscais"><i class="fa fa-search"></i></button>
                    <a href="/nota-fiscal/controle_financeiro/${parseInt(row.id)}" class="btn btn-sm btn-info termo-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Notas Fiscais"><i class="fa fa-dollar"></i></a>
                    <button class="btn btn-sm btn-primary edit-btn" data-action="edit" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn" data-action="delete" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Deletar"><i class="fa fa-trash" ></i></button>
                    `;
                }
            }
        ]);

        const columnsReadOnly = ref([
            {
                data: 'exercicio',
                title: 'Exercício',
                width: '10%',
                sortable: false,
            },
            {
                data: 'termo_de_referencia',
                title: 'Termo de Referência',
                width: '10%',
                sortable: false,
            },
            {
                data: 'empenho',
                title: 'Número Empenho',
                width: '15%',
                sortable: false,
            },
            {
                data: 'valor_empenho',
                title: 'Valor Empenhado',
                width: '15%',
                sortable: false,
                render: (data) => formatCurrency(data)
            },
            {
                data: 'valor_liquidacao',
                title: 'Valor Liquidado',
                width: '15%',
                sortable: false,
                render: (data) => formatCurrency(data)
            },
            {
                data: 'valor_total_pago',
                title: 'Valor Pago',
                width: '15%',
                sortable: false,
                render: (data) => formatCurrency(data)
            },
        ]);

        const aplicarEventos = async () => {

            let editelements = document.querySelectorAll("[data-action=edit]");
            editelements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let id = evt.currentTarget.getAttribute('data-id');
                    events.emit('popup', {
                        title: 'Editar Empenho',
                        component: 'form-empenho',
                        size: 'xl',
                        data: {
                            id_empenho: `${id}`,
                        },
                    });
                });
            });

            let termoelements = document.querySelectorAll("[data-action=visualizar]");
            termoelements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let id = evt.currentTarget.getAttribute('data-id');
                    let numero = evt.currentTarget.getAttribute('data-numero');
                    events.emit('popup', {
                        title: `Visualizar Notas do Empenho de N° - ${numero}`,
                        component: 'controle-financeiro-nota-fiscal-grid',
                        size: 'xl',
                        data: {
                            id: `${id}`,
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
                        title: `Deletar Empresa`,
                        component: 'popup-delete',
                        data: {
                            acao: '/empenho/delete/',
                            id: `${id}`,
                        },
                    });
                })
            })
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
            ready.value = true;
            if (props.data.id){
                ajax.value = `/empenho/list/${props.data.id}`;
                columnsSelected.value = columnsReadOnly;
                optionsSelected.value = optionsReadOnly;

            }else {
                ajax.value = `/empenho/list/${props.data}`;
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
@import 'datatables.net-bs5';

</style>
