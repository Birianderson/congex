<template>
    <div v-if="ready">
        <DataTable
            :ref="mydatatable"
            id="empresa"
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
import DataTablesCore from 'datatables.net';
import language from 'datatables.net-plugins/i18n/pt-BR.mjs';
import {inject, onMounted, ref} from 'vue';

DataTable.use(DataTablesCore);

export default {
    components: {DataTable, DataTablesCore},
    setup(props, {emit}) {
        const events = inject('events');
        const info = ref({});
        const ready = ref(false);
        const readOnly = ref(false);
        const mydatatable = ref();

        const columnDefs = [{
            "defaultContent": "-",
            "targets": "_all"
        }]

        const columns = ref([
            { data: 'nome', title: 'Nome' },
            { data: 'cnpj', title: 'CNPJ' },
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

        const ajax = '/empresa/list';
        const close = () => {
            events.emit('popup-close', true);
        }

        onMounted(() =>{
            ready.value = true;
        })


        return {
            info, ready, options, readOnly, close, columns, ajax,columnDefs,mydatatable
        }
    },
}

</script>


<style>
@import 'datatables.net-bs5';


</style>
