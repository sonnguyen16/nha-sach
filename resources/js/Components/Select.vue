<script setup>
import InputError from "@/Components/InputError.vue";
import Choices from "choices.js";
import {defineProps, defineModel, onMounted, watch} from 'vue';
import {debounce} from "lodash";

const props = defineProps({
    options: Object,
    optionDefault: String,
    errors: String,
    id: String,
    field: String,
    idField: String,
});

const model = defineModel();
let choice = null;
const emits = defineEmits(['search']);

onMounted(() => {
    const selectElement = document.getElementById(props.id);
    choice = new Choices(selectElement);
    choice.setChoices(props.options, props.idField, props.field, true);
    choice.passedElement.element.addEventListener('search', debounce((event) => {
        choice.clearChoices();
        const value = event.detail.value;
        emits('search', value);
    }, 300));
});

watch(() => props.options, (newOptions) => {
    if (choice) {
        choice.setChoiceByValue('');
        choice.clearChoices();
        choice.setChoices(newOptions, props.idField, props.field, true);
        choice.setChoiceByValue(parseInt(model.value));
    }
});

watch(() => model.value, () => {
    if(model.value){
        choice.setChoiceByValue(parseInt(model.value));
    }else{
        choice.setChoiceByValue('');
    }
});


</script>

<template>
    <div>
        <select :id="id" v-model="model" :class="['form-control', errors && 'border border-danger']">
            <option value="">{{ optionDefault }}</option>
        </select>
        <InputError :message="errors" />
    </div>
</template>
