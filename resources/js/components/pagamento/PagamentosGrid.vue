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

        const formatDate = (date) => {
            return moment(date).format('DD/MM/YYYY');
        };

        const formatSituacao = (situacao) => {
            const situacaoMap = {
                'NV': 'Não Vigente',
                'V': 'Contrato Inicial',
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
                        <button class="btn btn-sm btn-info termo-btn" data-action="termo" data-id="${row.id}" data-nome="${row.empresa.nome}" data-numero="${row.numero}" data-bs-toggle="tooltip" data-bs-placement="top" title="Pagamentos"><i class="fa fa-dollar"></i></button>
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

        const ajax = '/contrato/list';

        onMounted(() => {
            ready.value = true;
            events.on('reload', (data) => {
                mydatatable.value.dt.ajax.url(`${ajax}`).load();
            });
        });

        const aplicarEventos = async () => {

            let editelements = document.querySelectorAll("[data-action=edit]");
            editelements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let id = evt.currentTarget.getAttribute('data-id');
                    events.emit('popup', {
                        title: 'Editar Contrato',
                        component: 'form-contrato',
                        size: 'xl',
                        data: {
                            id: `${id}`,
                        },
                    });
                });
            });

            let termoelements = document.querySelectorAll("[data-action=termo]");
            termoelements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let id = evt.currentTarget.getAttribute('data-id');
                    let nome = evt.currentTarget.getAttribute('data-nome');
                    let numero = evt.currentTarget.getAttribute('data-numero');
                    events.emit('popup', {
                        title: `Adicionar termo aditivo - ${nome} ${numero}`,
                        component: 'form-termo-aditivo',
                        size: 'xl',
                        data: {
                            id: `${id}`,
                            nome: `${nome}`,
                            numero: `${numero}`,
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
                            acao: '/contrato/delete/',
                            id: `${id}`,
                        },
                    });
                });
            });
        };


        return {
            ready, options, columns, ajax, mydatatable, aplicarEventos, calculateDaysToExpire
        }
    }
}
</script>

<style>
@import 'datatables.net-bs5';

</style>
