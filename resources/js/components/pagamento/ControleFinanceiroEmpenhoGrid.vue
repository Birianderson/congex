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
        const empresas = ref();
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
                    <button class="btn btn-sm btn-secondary termo-btn" data-action="termo" data-id="${row.id}" data-numero="${row.numero}" data-bs-toggle="tooltip" data-bs-placement="top" title="Visualizar Notas Fiscais"><i class="fa fa-search"></i></button>
                    <a href="/pagamento/termo/${parseInt(row.termo.contrato_id)}/empenho/${row.termo_id}/nota-fiscal/${row.id}" class="btn btn-sm btn-info termo-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Notas Fiscais"><i class="fa fa-dollar"></i></a>
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
            let termoelements = document.querySelectorAll("[data-action=termo]");
            termoelements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let id = evt.currentTarget.getAttribute('data-id');
                    let nome = evt.currentTarget.getAttribute('data-nome');
                    events.emit('popup', {
                        title: `Adicionar nota fiscal ao empenho - ${nome}`,
                        component: 'form-nota-fiscal',
                        size: 'xl',
                        data: {
                            id: `${id}`,
                            nome: `${nome}`,
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
                ajax.value = `/pagamento/list/${props.data.id}`;
                columnsSelected.value = columnsReadOnly;
                optionsSelected.value = optionsReadOnly;

            }else {
                ajax.value = `/pagamento/list/${props.data}`;
                columnsSelected.value = columns;
                optionsSelected.value = options;
            }
            events.on('reload', (data) => {
                mydatatable.value.dt.ajax.url(`${ajax}`).load();
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
