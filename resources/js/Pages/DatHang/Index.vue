<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import {nextTick, onMounted, ref, watch} from "vue";
import {debounce} from "lodash";
import {_TIME_DEBOUNCE} from "@/Constants/constants.js";
import {useModal} from "@/Hooks/useModal.js";
import Modal from "@/Pages/DatHang/Modal.vue";

const props = defineProps({
    dat_hang: Object,
    khach_hang: Object,
    don_vi_tinh: Object,
})
const key = ref(0);
const keyModal = ref(0);
const dat_hang_selected = ref(null);
const changePage = (page) => {
    router.visit(route('dat-hang.index', {page: page, search: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'ngay', label: 'Ngày'},
    {field: 'khach_hang.TenKhachHang', label: 'Khách hàng'},
    {field: 'khach_hang.DienThoai', label: 'Số điện thoại'},
    {field: 'tongtien', label: 'Tổng tiền'},
    {field: 'vat', label: 'VAT'},
    {field: 'tongvat', label: 'Tổng VAT'},
    {field: 'action', label: 'Thao tác'},
]

const modal = useModal('modal');

onMounted(() => {
    eventForEditBtn()
})

const onRefresh = () => {
    key.value++
    keyModal.value++
    nextTick(() => {
        eventForEditBtn()
    })
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        dat_hang_selected.value = props.dat_hang.data.find(item => item.id === id);
        keyModal.value++
        modal.showModal();
    });
}

const openModal = () => {
    dat_hang_selected.value = null;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('dat-hang.index', {search: value}), {
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
            <div class="mb-3 flex justify-between">
                <button @click.prevent="openModal" class="btn btn-success">Thêm đặt hàng</button>
                <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm đặt hàng">
            </div>
            <div class="table-responsive">
                <table :key="key" v-data="{ data: dat_hang.data, columns: columns }"
                class="table table-striped text-2xl">
                </table>
            </div>
            <Pagination
                :all-data="dat_hang"
                @changePage="changePage"
            />
        </div>
        <Modal
            :keyModal="keyModal"
            :dat_hang="dat_hang_selected"
            :khach_hang="props.khach_hang"
            :don_vi_tinh="props.don_vi_tinh"
            @closeModal="modal.hideModal"
            @refresh="onRefresh"
        />
    </MainLayout>
</template>
