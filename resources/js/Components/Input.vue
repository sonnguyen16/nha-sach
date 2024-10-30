<script setup>
import InputError from "@/Components/InputError.vue";
import { ref } from 'vue';

const props = defineProps({
    type: String,
    errors: String,
    placeholder: String,
    disabled: {
        type: Boolean,
        default: false,
    },
});

const model = defineModel();
const inputValue = ref(model);

// Hàm định dạng số với dấu phân cách hàng nghìn, chỉ định dạng nếu là số
function formatNumber(value) {
    if (!isNaN(value) && value !== null && value !== '') {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    } else {
        return value; // Nếu không phải số, giữ nguyên giá trị
    }
}

// Xử lý sự kiện nhập liệu
function handleInput(event) {
    let value = event.target.value;

    // Xóa các số 0 ở đầu
    value = value.replace(/^0+(?=\d)/, '');

    // Kiểm tra nếu giá trị là số
    if (!isNaN(value.replace(/\./g, ''))) {
        const numericValue = value.replace(/\./g, '');
        inputValue.value = numericValue;
        model.value = numericValue;
        // Áp dụng định dạng với dấu phân cách hàng nghìn
        event.target.value = formatNumber(numericValue);
    } else {
        // Nếu không phải là số, hiển thị giá trị bình thường
        inputValue.value = value;
        model.value = value;
    }
}
</script>

<template>
    <div>
        <input
            :type="props.type"
            :disabled="props.disabled"
            :placeholder="props.placeholder"
            :class="['form-control', errors && 'border border-danger']"
            :value="formatNumber(inputValue)"
            @input="handleInput"
        />
        <InputError :message="errors" />
    </div>
</template>
