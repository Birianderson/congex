<template>
    <div class="row">
        <div class="table-container col-7">
            <table class="risk-table">
                <thead>
                <tr>
                    <th rowspan="2" class="rounded-left align-bottom border-end">IMPACTO</th>
                    <th colspan="5">PROBABILIDADE</th>
                </tr>
                <tr>
                    <th class="border-bottom">Rara</th>
                    <th class="border-bottom">Pouco Provável</th>
                    <th class="border-bottom">Provável</th>
                    <th class="border-bottom">Muito Provável</th>
                    <th class="border-bottom">Praticamente Certa</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(row, rowIndex) in riskMatrix" :key="rowIndex">
                    <th class="rounded-left border-end">{{ impacts[rowIndex] }}</th>
                    <td
                        v-for="(cell, cellIndex) in row"
                        :key="cellIndex"
                        class="text-black"
                        :class="[getCellClass(cell), { 'rounded-right': rowIndex === riskMatrix.length - 1 && cellIndex === row.length - 1 }, { 'selected': selectedCell === cell }]"
                        @click="handleCellClick(cell)"
                    >
                        {{ cell }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-5">
            <div class="m-2">
                <form id="frm" name="frm" data-method="post" :action="acao">
                    <form-error></form-error>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="possibilidades">Possibilidade de Tratamento</label>
                            <select id="possibilidades" class="form-control" v-model="selectedTreatment" name="possibilidades">
                                <option value="" disabled>Selecione a possibilidade de tratamento</option>
                                <option v-for="treatment in treatments" :key="treatment" :value="treatment">
                                    {{ treatment }}
                                </option>
                            </select>
                        </div>
                        <input type="hidden" name="contrato_id" :value="props.data.id">
                        <div class="col-12 mb-3">
                            <label for="situacao">Situação</label>
                            <select id="situacao" class="form-control" v-model="selectedSituation" name="situacao">
                                <option value="" disabled>Selecione a situação</option>
                                <option v-for="situation in situations" :key="situation" :value="situation">
                                    {{ situation }}
                                </option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="pontuacao">Pontuação</label>
                            <input
                                type="text"
                                id="pontuacao"
                                name="pontuacao"
                                class="form-control"
                                v-model="score"
                                readonly
                            />
                        </div>
                        <submit-button></submit-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { inject, onMounted, ref } from 'vue';
import axios from 'axios';

export default {
    setup(props, { emit }) {
        const impacts = ref(['Muito Baixo', 'Baixo', 'Médio', 'Alto', 'Muito Alto']);
        const riskMatrix = ref([
            [1, 2, 4, 7, 11],
            [3, 5, 8, 12, 16],
            [6, 9, 13, 17, 20],
            [10, 14, 18, 21, 23],
            [15, 19, 22, 24, 25]
        ]);
        const acao = ref('/risco-contrato/');
        const treatments = ref(['Evitar', 'Transferir', 'Mitigar', 'Aceitar']);
        const situations = ref(['Concluída', 'Não Iniciada', 'Em Andamento']);
        const selectedTreatment = ref('');
        const selectedSituation = ref('');
        const score = ref('');
        const ready = ref(false);
        const selectedCell = ref(null);
        const events = inject('events');
        const info = ref({});

        onMounted(async () => {
            events.off('form-submitted');
            events.on('form-submitted', (sucesso) => {
                if (sucesso) {
                    events.emit('reload', true);
                    events.emit('notification', {
                        type: 'success',
                        message: 'Contrato salvo com Sucesso!'
                    });
                    emit('reload');
                    emit('close', true);
                }
            });

            if (props.data.id) {
                await loadData();
            } else {
                info.value.termos = [{}];
                ready.value = true;
            }
        });

        const loadData = async () => {
            try {
                const response = await axios.get(`${acao.value}${props.data.id}`);
                info.value = response.data;

                // Set form action based on whether the risk info exists
                if (info.value.risco === null) {
                    acao.value = '/risco-contrato/create/' + props.data.id;
                } else {
                    acao.value = '/risco-contrato/update/' + props.data.id;
                    selectedTreatment.value = info.value.risco.possibilidades;
                    selectedSituation.value = info.value.risco.situacao;
                    score.value = info.value.risco.pontuacao;
                    selectedCell.value = info.value.risco.pontuacao;
                }
            } catch (err) {
                emit('notification', {
                    type: 'error',
                    message: 'Não foi possível recuperar os dados do Contrato.'
                });
            }
            ready.value = true;
        };

        const getCellClass = (value) => {
            if (value >= 20) return 'red';
            if (value >= 10) return 'yellow';
            if (value >= 5) return 'orange';
            return 'light-orange';
        };

        const handleCellClick = (value) => {
            score.value = value;
            selectedCell.value = value;
        };

        return {
            impacts,
            riskMatrix,
            treatments,
            situations,
            selectedTreatment,
            selectedSituation,
            score,
            selectedCell,
            acao,
            props,
            getCellClass,
            handleCellClick
        };
    },
    props: {
        data: { default: null, required: true }
    }
};
</script>

<style scoped>
.table-container {
    display: inline-block;
    border-radius: 10px;
}

.risk-table {
    border-collapse: collapse;
    width: 100%;
}

.risk-table th,
.risk-table td {
    padding: 10px;
    text-align: center;
    transition: transform 0.2s;
    position: relative;
    z-index: 1;
}

.risk-table th {
    background-color: #f0f0f0;
}

.risk-table td:hover {
    transform: scale(1.1);
    color: white;
    cursor: pointer;
    z-index: 2;
}

.selected {
    outline: 2px solid #000;
    z-index: 3 !important;
}

.red {
    background-color: #ff0000;
}

.yellow {
    background-color: #ffff00;
}

.orange {
    background-color: #ffcc00;
}

.light-orange {
    background-color: #ff9900;
}

.form-control {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
}
</style>
