<template>
    <div class="m-2">
        <div class="text-center mt-5">
            <a>Tem certeza que deseja excluir esse registro?</a>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center mt-4">
                <button class="btn btn-danger" @click="handleDelete">
                    <i class="fa fa-trash me-1"></i> Deletar
                </button>
                &nbsp;
                <button type="button" class="btn btn-secondary text-white" @click="close" aria-label="Close">
                    <i class="fa fa-close"></i> Cancelar
                </button>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import axios from 'axios';
import { inject, onMounted, ref } from 'vue';

export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    setup(props, { emit }) {
        const events = inject('events');
        const acao = ref('');

        const close = () => {
            events.emit('popup-close', true);
        };

        const handleDelete = async () => {
            try {
                await axios.delete(acao.value);
                events.emit('table-reload', true);
                events.emit('notification', {
                    type: 'success',
                    message: 'Registro excluído com sucesso!'
                });
                events.emit('reload');
                emit('close', true);
            } catch (error) {
                events.emit('notification', {
                    type: 'error',
                    message: 'Erro ao excluir o registro!'
                });
                console.error('Erro ao excluir o registro:', error);
            }
        };

        onMounted(() => {
            acao.value = `${props.data.acao}${props.data.id}`;
        });

        return {
            acao,
            close,
            handleDelete
        };
    }
};
</script>
