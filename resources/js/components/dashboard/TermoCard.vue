<template>
    <div class="card border-radius-termo shadow-termo"
         @mouseover="toggleHover(true)"
         @mouseleave="toggleHover(false)"
         :class="onhover || selecionado ? 'element-with-gradient-' + numero + ' text-white' : ''">
        <div class="card-body">
            <div class="row">
                <div class="col-9">
                    <div>
                        <div class="mb-0">{{ titulo }}</div>
                        <h2 class="font-weight-bolder mb-0" :class="onhover || selecionado ? 'text-white' : ''">
                            {{ numero_contratos }}
                            <small class="text-sm"
                                   :class="['color-text-chart-' + numero, onhover || selecionado ? 'text-white' : '']">
                                <i class="fa fa-chart-simple"></i> {{ porcentagem_contratos }}%
                            </small>
                        </h2>
                    </div>
                </div>
                <div class="col-3 align-content-center border-radius-termo"
                     :class="!onhover && !selecionado ? 'element-with-gradient-' + numero + ' text-white shadow-box' : ''">
                    <div class="text-center">
                        <i class="fa fa-book" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {defineProps, inject, onMounted, ref} from "vue";
const events = inject('events');
const selecionado = ref(false)
const props = defineProps({
    numero: { required: true },
    titulo: { required: true },
    numero_contratos: { required: true },
    porcentagem_contratos: { required: true },
    selecionado: {default:6, required:false}
});

onMounted(() => {
    onhover.value = parseInt(props.selecionado) === parseInt(props.numero);
    events.on("selecionaCard", (data) => {
        selecionado.value = parseInt(data);
        onhover.value = parseInt(data) === parseInt(props.numero);
        selecionado.value = onhover.value;
    });
});

const onhover = ref(false);

const toggleHover = (hovered) => {
    onhover.value = hovered;
};

</script>

<style scoped>
.card {
    transition: background-color 2.6s ease, color 0.6s ease;
}

.element-with-gradient-0, .element-with-gradient-1, .element-with-gradient-2,
.element-with-gradient-3, .element-with-gradient-4, .element-with-gradient-5 {
    transition: background-color 0.6s ease, color 0.6s ease;
}

.text-white {
    transition: color 0.6s ease;
}
</style>
