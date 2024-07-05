<template>
    <div class="card border-radius-termo shadow-termo card-height"
         @mouseover="toggleHover(true)"
         @mouseleave="toggleHover(false)"
         @click="selecionado"
         :class="onhover || selecionado ? 'element-with-gradient-' + numero + ' text-white' : ''">
        <div class="card-body">
            <div class="align-content-center border-radius-termo mb-0  mt-1">
                <div class="">
                    <i class="fa fa-file-text icon-size-dash" :class="['color-text-chart-' + numero, onhover || selecionado ? 'text-white' : '']" aria-hidden="true"></i>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-2" :class="onhover || selecionado ? 'text-white' : ''">{{ titulo }}</h4>
                    <h2 class="font-weight-bolder mb-0" :class="onhover || selecionado ? 'text-white' : ''">
                        {{ numero_contratos }}
                    </h2>
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
    numero: {required: true},
    titulo: {required: true},
    numero_contratos: {required: true},
    selecionado: {default:6, required:false}
});

const onhover = ref(false);

onMounted(() => {
    onhover.value = parseInt(props.selecionado) === parseInt(props.numero);
    events.on("selecionaCard", (data) => {
        selecionado.value = parseInt(data);
        onhover.value = parseInt(data) === parseInt(props.numero);
        selecionado.value = onhover.value;
    });
});


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
