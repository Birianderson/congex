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
    setup(props) {
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
                '0': 'Contrato Inicial',
                '1': '1° Termo Aditivo',
                '2': '2° Termo Aditivo',
                '3': '3° Termo Aditivo',
                '4': '4° Termo Aditivo',
                '5': '5° Termo Aditivo'
            };
            return situacaoMap[situacao] || situacao;
        };

        const columns = ref([
            {
                data: 'numero',
                title: 'Termo',
                width: '10%',
                render: (data) => formatSituacao(data)
            },
            {
                data: 'valor',
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
                        <a href="/pagamento/termo/${parseInt(row.id)}" class="btn btn-sm btn-info termo-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Pagamentos"><i class="fa fa-dollar"></i></a>
                    `;
                }
            }
        ]);

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

        const ajax = `/termo/getByContratoId/${props.data}`;

        onMounted(() => {
            ready.value = true;
            events.on('reload', (data) => {
                mydatatable.value.dt.ajax.url(`${ajax}`).load();
            });
        });

        return {
            ready, options, columns, ajax, mydatatable,
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
