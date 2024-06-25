<template>
    <div v-if="ready">
        <DataTable
            ref="mydatatable"
            id="empresa"
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
import moment from 'moment'; // Import moment.js for date handling

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

        const formatDate = (date) => {
            return moment(date).format('DD/MM/YYYY');
        };

        const columns = ref([
            { data: 'numero', title: 'Número', width: '10%' },
            { data: 'empresa.nome', title: 'Empresa', width: '20%' },
            {
                data: 'data_inicio',
                title: 'Início Vigência',
                width: '15%',
                render: (data) => formatDate(data)
            },
            {
                data: 'data_fim',
                title: 'Término Vigência',
                width: '15%',
                render: (data) => formatDate(data)
            },
            {
                data: 'data_fim',
                title: 'Dias a Vencer',
                width: '15%',
                render: (data, type, row) => {
                    const { color, text } = calculateDaysToExpire(row.data_fim);
                    return `<span class="status-dot" style="background-color: ${color};"></span> ${text}`;
                }
            },
            {
                data: 'valor',
                title: 'Montante do Contrato',
                width: '15%',
                render: (data) => formatCurrency(data)
            },
            {
                data: null,
                title: 'Ações',
                orderable: false,
                searchable: false,
                className: 'dt-action',
                width: '20%',
                render: (data, type, row) => {
                    return `
                        <button class="btn btn-sm btn-primary edit-btn" data-action="edit" data-id="${row.id}"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" data-action="delete" data-id="${row.id}"><i class="fa fa-trash"></i></button>
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
            let elements = document.querySelectorAll("[data-action=edit]");
            elements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let id = evt.currentTarget.getAttribute('data-id');
                    events.emit('popup', {
                        title: 'Editar Contrato',
                        component: 'form-contrato',
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
                            acao: '/empresa/delete/',
                            id: `${id}`,
                        },
                    });
                });
            });
        };

        const calculateDaysToExpire = (data_fim) => {
            let today = moment().startOf('day');
            let end = moment(data_fim).startOf('day');
            let diffDays = end.diff(today, 'days');
            let diffMonths = end.diff(today, 'months');
            let color = '';
            let text = '';

            if (diffDays < 0) {
                color = 'gray';
                text = 'Vencido';
            } else if (diffMonths >= 4) {
                color = 'green';
                text = `${diffMonths} meses e ${diffDays % 30} dias`;
            } else if (diffMonths >= 1 && diffMonths < 4) {
                color = '#FFD700';
                text = `${diffMonths} meses e ${diffDays % 30} dias`;
            } else if (diffDays < 30) {
                color = 'red';
                text = `${diffDays} dias`;
            }
            return { color, text };
        };

        return {
            ready, options, columns, ajax, mydatatable, aplicarEventos, calculateDaysToExpire
        }
    }
}
</script>

<style>
@import 'datatables.net-bs5';

.status-dot {
    display: inline-block;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    margin-right: 5px;
}
</style>
