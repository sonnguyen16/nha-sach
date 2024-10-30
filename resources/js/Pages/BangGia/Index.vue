<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import {nextTick, onMounted, ref, watch} from "vue";
import {debounce} from "lodash";
import {_TIME_DEBOUNCE} from "@/Constants/constants.js";
import {useModal} from "@/Hooks/useModal.js";
import Modal from "@/Pages/NguoiDung/Modal.vue";

const props = defineProps({
    bang_gia: Object,
})
const key = ref(0);
const changePage = (page) => {
    router.visit(route('bang-gia.index', {page: page, search: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'stt', label: 'STT'},
    {field: 'mahang', label: 'Mã hàng'},
    {field: 'tenhang', label: 'Tên hàng'},
    {field: 'dvt', label: 'Đơn vị tính'},
    {field: 'GIANHAP', label: 'Giá nhập'},
    {field: 'gia', label: 'Giá bán'},
]

const onRefresh = () => {
    key.value++
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('bang-gia.index', {search: value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}, _TIME_DEBOUNCE)

</script>

<template>
    <MainLayout>
        <div class="py-3 px-4">
            <div class="mb-3 flex justify-end">
                <input v-model="search" class="border-gray-300 rounded-lg w-1/5 w-[200px]" placeholder="Tìm kiếm bảng giá">
            </div>
            <div class="table-responsive">
                <table :key="key" v-data="{ data: bang_gia.data, columns: columns }"
                class="table table-striped text-2xl">
                </table>
            </div>
            <Pagination
                :all-data="bang_gia"
                @changePage="changePage"
            />
        </div>
        <Modal />
    </MainLayout>
</template>
