<script setup>
import {useForm} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import Input from "@/Components/Input.vue";
import Select from "@/Components/Select.vue";
import {formatMoney} from "@/assets/js/script.js";

const props = defineProps({
    dat_hang: Object,
    khach_hang: Object,
    don_vi_tinh: Object,
    keyModal: Number
})

const emits = defineEmits(['closeModal', 'refresh']);
const hang = ref([]);

const form = useForm({
    id: '',
    ngay: '',
    khachhang_id: '',
    tongtien: 0,
    vat: 0,
    tongvat: 0,
    ghichu: '',
    dathang_sanpham: [],
})

const chi_tiet = ref({
    hang_id: '',
    dvt_id: '',
    soluong: 0,
    dongia: 0,
    chietkhau: 0,
    thanhtien: '',
    tenhang: '',
    dvt: '',
})

const submit = () => {
    form.post(route('dat-hang.store'), {
        onSuccess: () => {
            closeModal()
            emits('refresh')
        },
        onError: (err) => {
            console.log(err)
        }
    })
}

watch(() => props.keyModal, () => {
    if(props.dat_hang) {
        Object.assign(form, {
            ...props.dat_hang,
            tongtien: parseInt(props.dat_hang.tongtien),
            vat: parseInt(props.dat_hang.vat),
            tongvat: parseInt(props.dat_hang.tongvat),
        });
    }else{
        form.reset();
    }
})
const closeModal = () => {
    emits('closeModal');
    form.reset();
    form.clearErrors();
}

const addChiTiet = () => {
    if(!chi_tiet.value.hang_id || !chi_tiet.value.dvt_id
        || chi_tiet.value.soluong <= 0 || chi_tiet.value.dongia <= 0
        || chi_tiet.value.chietkhau < 0
    ) {
        return;
    }

    chi_tiet.value.thanhtien = chi_tiet.value.soluong * chi_tiet.value.dongia * (1 - chi_tiet.value.chietkhau / 100);
    chi_tiet.value.tenhang = hang.value.find(h => h.id === String(chi_tiet.value.hang_id)).tenhang;
    chi_tiet.value.dvt = hang.value.find(h => h.id === String(chi_tiet.value.hang_id)).dvt;
    form.dathang_sanpham.push(chi_tiet.value);
    form.tongtien = form.dathang_sanpham.reduce((acc, item) => acc + parseInt(item.thanhtien), 0);
    form.tongvat = form.tongtien * form.vat / 100 + form.tongtien;
    chi_tiet.value = {
        hang_id: '',
        dvt_id: '',
        soluong: '',
        dongia: '',
        chietkhau: '',
        thanhtien: '',
        tenhang: '',
        dvt: '',
    }
}

const removeChiTiet = (index) => {
    form.dathang_sanpham.splice(index, 1);
    form.tongtien = form.dathang_sanpham.reduce((acc, item) => acc + parseInt(item.thanhtien), 0);
    form.tongvat = form.tongtien * form.vat / 100 + form.tongtien;
}

const searchHang = (value) => {
    if(value.length === 0) {
        hang.value = [];
        return;
    }
    axios({
        url: route('bang-gia.find'),
        method: 'get',
        params: {
            search: value
        }
    }).then((res) => {
        hang.value = res.data;
    }).catch((err) => {
        console.log(err)
    })
}

watch(() => chi_tiet.value.hang_id, (newVal) => {
    const hangFound = hang.value.find(h => h.id === String(newVal));
    if(hangFound) {
        chi_tiet.value.dongia = hangFound.gia;
        chi_tiet.value.dvt_id = parseInt(hangFound.dvt_id);
    }
})

watch(() => form.vat, (newVal) => {
    const tongtien = parseInt(form.tongtien.toString()) || 0;
    const vat = parseInt(newVal.toString()) || 0;

    form.tongvat = tongtien * vat / 100 + tongtien;
});


</script>

<template>
    <div class="modal fade" id="modal">
        <div
            class="modal-dialog modal-dialog-centered"
            :class="['modal-xl']"
        >
            <div class="modal-content">
                <div class="modal-body pt-4">
                    <div class="grid lg:grid-cols-2 lg:gap-10">
                        <div>
                            <div class="form-group">
                                <label for="name">Ngày</label>
                                <Input
                                    type="date"
                                    v-model="form.ngay"
                                    :errors="form.errors.ngay"
                                />
                            </div>

                            <div class="form-group">
                                <label for="name">Khách hàng</label>
                                <Select
                                    id="khach_hang_id"
                                    field="TenKhachHang"
                                    id-field="ID"
                                    :options="props.khach_hang"
                                    v-model="form.khachhang_id"
                                    :errors="form.errors.khachhang_id"
                                    option-default="Chọn khách hàng"
                                />
                            </div>

                            <div class="form-group">
                                <label for="name">Tổng tiền</label>
                                <Input
                                    v-model="form.tongtien"
                                    :errors="form.errors.tongtien"
                                />
                            </div>

                            <div class="form-group">
                                <label for="name">VAT</label>
                                <Input
                                    v-model="form.vat"
                                    :errors="form.errors.vat"
                                />
                            </div>

                            <div class="form-group">
                                <label for="name">Tổng VAT</label>
                                <Input
                                    v-model="form.tongvat"
                                    :errors="form.errors.tongvat"
                                />
                            </div>
                        </div>

                        <div class="">
                            <div class="form-group">
                                <label for="name">Hàng</label>
                                <Select
                                    id="hang_id"
                                    field="tenhang"
                                    id-field="id"
                                    :options="hang"
                                    v-model="chi_tiet.hang_id"
                                    option-default="Chọn hàng"
                                    @search="searchHang"
                                />
                            </div>

                            <div class="form-group">
                                <label for="name">Đvt</label>
                                <Select
                                    id="dvt_id"
                                    field="Dvt"
                                    id-field="ID"
                                    :options="props.don_vi_tinh"
                                    v-model="chi_tiet.dvt_id"
                                    option-default="Chọn đơn vị tính"
                                />
                            </div>

                            <div class="form-group">
                                <label for="name">S.lượng</label>
                                <Input
                                    v-model="chi_tiet.soluong"
                                />
                            </div>

                            <div class="form-group">
                                <label for="name">Đơn giá</label>
                                <Input
                                    v-model="chi_tiet.dongia"
                                />
                            </div>

                            <div class="form-group">
                                <label for="name">Chiết khấu</label>
                                <div class="flex items-start gap-2">
                                    <Input
                                        class="flex-1"
                                        v-model="chi_tiet.chietkhau"
                                    />
                                    <button @click.prevent="addChiTiet" class="btn btn-success">Thêm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Hàng</th>
                                <th>Đvt</th>
                                <th>S.lượng</th>
                                <th>Đơn giá</th>
                                <th>Chiết khấu</th>
                                <th>Thành tiền</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="form.dathang_sanpham.length > 0" v-for="(item, index) in form.dathang_sanpham" :key="index">
                                <td>{{ item.tenhang || item.hang.TenHang }}</td>
                                <td>{{ item.dvt || item.don_vi_tinh.Dvt }}</td>
                                <td>{{ item.soluong }}</td>
                                <td>{{ formatMoney(item.dongia) }}</td>
                                <td>{{ item.chietkhau }} %</td>
                                <td>{{ formatMoney(item.thanhtien) }}</td>
                                <td class="text-center pl-0">
                                    <a @click.prevent="removeChiTiet(index)">
                                        <i class="fas fa-trash-alt text-lg text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr v-else>
                                <td colspan="7" class="text-center">Chưa có sản phẩm</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button @click.prevent="submit" type="submit" class="btn btn-success">Lưu</button>
                    <button @click.prevent="closeModal" type="reset"
                            class="btn btn-secondary ml-2">
                        Hủy
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
