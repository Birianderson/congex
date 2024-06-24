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
import {inject, onMounted, ref} from 'vue';

DataTable.use(DataTablesCore);

export default {
    components: {DataTable, DataTablesCore},
    setup() {
        const events = inject('events');
        const ready = ref(false);
        const mydatatable = ref();
        let dt;

        const columns = ref([
            {data: 'numero', title: 'Número', width: '40%',},
            {data: 'valor', title: 'Valoe', width: '40%',},
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
        })

        const aplicarEventos = async () => {
            let elements = document.querySelectorAll("[data-action=edit]");
            elements.forEach(item => {
                item.addEventListener('click', (evt) => {

                    let id = evt.currentTarget.getAttribute('data-id');
                    events.emit('popup', {
                        title: 'Editar Empresa',
                        component: 'form-empresa',
                        data: {
                            id: `${id}`,
                        },
                    });
                })
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
                })
            })
        }

        return {
            ready, options, columns, ajax, mydatatable, aplicarEventos
        }
    },
}
</script>


<style>
@import 'datatables.net-bs5';


</style>
