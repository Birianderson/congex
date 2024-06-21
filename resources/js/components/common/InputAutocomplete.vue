<script setup>

import SimpleTypeahead from 'vue3-simple-typeahead';
import 'vue3-simple-typeahead/dist/vue3-simple-typeahead.css'; //Optional default CSS


import {ref, defineProps, inject, watch} from "vue";
const emit = defineEmits(['select']);

const events = inject('events');
const props = defineProps(
    {
        dataUrl: { required: true },
        dataLabel: { default: 'name' },
        dataValue: { default: 'id' },
    }
);

const items = ref([]);
const query = ref(null);
const selectedItem = ref({});
const input = ref('');
const getItems = async (event) => {

    const response = await axios.get(props.dataUrl+event.input)
    items.value = response.data;
    input.value = event.input;
}
const displayItem = function(item) {
    return item[props.dataLabel];
}

const selectItem = function(item) {
    selectedItem.value = item;
    events.emit('select', item[props.dataValue]);
}
</script>

<template>
    <SimpleTypeahead
        class="form-control"
        placeholder="Buscar um registro"
        @onInput="getItems"
        @selectItem="selectItem"
        :items="items"
        :minInputLength="2"
        :itemProjection="displayItem"
    ></SimpleTypeahead>
</template>

<style>

</style>
