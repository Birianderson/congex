<script setup lang="ts">
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import language from 'datatables.net-plugins/i18n/pt-BR.mjs';


DataTable.use(DataTablesCore);

const data = [
    ["a", 2, 3, 4, 5, 6],
    [7, 8, 9, 10, 11, 12],
    ["a", 2, 3, 4, 5, 6],
    [7, 8, 9, 10, 11, 12],
    ["a", 2, 3, 4, 5, 6],
    [7, 8, 9, 10, 11, 12],
    ["a", 2, 3, 4, 5, 6],
    [7, 8, 9, 10, 11, 12],
    ["a", 2, 3, 4, 5, 6],
    [7, 8, 9, 10, 11, 12],["a", 2, 3, 4, 5, 6],
    [7, 8, 9, 10, 11, 12],["a", 2, 3, 4, 5, 6],
    [7, 8, 9, 10, 11, 12],



];
const options = {
    language: language,
};
</script>

<template>
    <div class="card">
        <div class="cardheader p-0 position-relative mt-n4 mx-3 z-index-2"> <div class="ps-3">Contratos</div></div>
        <div class="p-5">
            <DataTable
                :data="data"
                class="table table-hover table-responsive table-"
                width="100%"
                :options="options"
            >
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Extn.</th>
                    <th>Start date</th>
                    <th>Salary</th>
                </tr>
                </thead>
            </DataTable>
        </div>
    </div>
</template>

<style>
@import 'datatables.net-bs5';


</style>
