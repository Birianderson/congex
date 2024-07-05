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

        const formatSituacao = (situacao) => {
            const situacaoMap = {
                'NV': 'Não Vigente',
                'V0': 'Contrato Inicial',
                'V1': '1° Termo Aditivo',
                'V2': '2° Termo Aditivo',
                'V3': '3° Termo Aditivo',
                'V4': '4° Termo Aditivo',
                'V5': '5° Termo Aditivo'
            };
            return situacaoMap[situacao] || situacao;
        };

        const getCellClass = (value) => {
            if (value >= 20) return 'red';
            if (value >= 10) return 'yellow';
            if (value >= 5) return 'orange';
            return 'light-orange';
        };

        const columns = ref([
            { data: 'empresa.nome', title: 'Empresa', width: '20%' },
            { data: 'numero', title: 'Número', width: '8%' },
            {
                data: 'situacao',
                title: 'Termo',
                width: '10%',
                render: (data) => formatSituacao(data)
            },
            {
                data: 'risco.situacao',
                title: 'Ações do Risco',
                width: '15%',
            },
            {
                data: 'risco.possibilidades',
                title: 'Tratamento',
                width: '15%',
            },
            {
                data: 'risco.pontuacao',
                title: 'Pontuação do Risco',
                width: '15%',
                render: (data) => {
                    if (data === null) {
                        return ''; // Retorna uma string vazia se data for null
                    }
                    const cellClass = getCellClass(data);
                    return `
                        <span v-if="data" class="fa fa-warning icon-size-risco text-${cellClass}-500"></span>
                        <span>${data}</span>
                        `;
                }
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
                        <button class="btn btn-sm btn-secondary termo-btn" data-action="termo" data-id="${row.id}" data-nome="${row.empresa.nome}" data-numero="${row.numero}" data-bs-toggle="tooltip" data-bs-placement="top" title="Visualizar Termos"><i class="fa fa-search"></i></button>
                        <button class="btn btn-sm btn-primary risco-btn" data-action="risco" data-id="${row.id}" data-nome="${row.empresa.nome}" data-numero="${row.numero}" data-bs-toggle="tooltip" data-bs-placement="top" title="Riscos do Contrato"><i class="fa fa-warning"></i></button>
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
                    let numero = evt.currentTarget.getAttribute('data-numero');
                    events.emit('popup', {
                        title: `Visualizar Termos - ${nome} ${numero}`,
                        component: 'controle-financeiro-termo-grid',
                        size: 'xl',
                        data: {
                            id: `${id}`,
                        },
                    });
                });
            });

            let riscoelements = document.querySelectorAll("[data-action=risco]");
            riscoelements.forEach(item => {
                item.addEventListener('click', (evt) => {
                    let id = evt.currentTarget.getAttribute('data-id');
                    let nome = evt.currentTarget.getAttribute('data-nome');
                    let numero = evt.currentTarget.getAttribute('data-numero');
                    events.emit('popup', {
                        title: `Riscos do Contrato - ${nome} ${numero}`,
                        component: 'risco-contrato-form',
                        size: 'xl',
                        data: {
                            id: `${id}`,
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

        const ajax = '/contrato/list';

        onMounted(() => {
            ready.value = true;
            events.on('reload', (data) => {
                mydatatable.value.dt.ajax.url(`${ajax}`).load();
            });
        });

        return {
            ready, options, columns, ajax, mydatatable, aplicarEventos
        }
    }
}
</script>

<style>
@import '../../../../node_modules/datatables.net-bs5';

.risk-dot {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    display: inline-block;
}

.risk-dot.red {
    background-color: #ff0000;
}

.risk-dot.yellow {
    background-color: #ffff00;
}

.risk-dot.orange {
    background-color: #ffcc00;
}

.risk-dot.light-orange {
    background-color: #ff9900;
}
</style>
