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
DataTable.use(DataTablesCore);

export default {
    components: { DataTable, DataTablesCore },
    setup(props) {
        const events = inject('events');
        const ready = ref(false);
        const mydatatable = ref();
        const empresas = ref();
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
                    <button class="btn btn-sm btn-info termo-btn" data-action="termo" data-id="${row.id}" data-nome="${row.empenho}" data-bs-toggle="tooltip" data-bs-placement="top" title="Adicionar Nota Fiscal"><i class="fa fa-file-text"></i></button>
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

        const ajax = `/pagamento/list/${props.data}`;

        onMounted(() => {
            ready.value = true;
            events.on('reload', (data) => {
                mydatatable.value.dt.ajax.url(`${ajax}`).load();
            });
        });


        return {
            ready, options, columns, ajax, mydatatable, aplicarEventos
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
