<script setup>
import {useForm} from "@inertiajs/vue3";
import {watch} from "vue";
import Input from "@/Components/Input.vue";

const props = defineProps({
    nguoi_dung: Object,
    keyModal: Number
})

const emits = defineEmits(['closeModal', 'refresh']);

const form = useForm({
    ID: '',
    MaNguoiDung: '',
    HoTen: '',
    MatKhau: '',
})

const submit = () => {
    form.post(route('nguoi-dung.store'), {
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
    if(props.nguoi_dung) {
        Object.assign(form, props.nguoi_dung);
    }else{
        form.reset();
    }
})
const closeModal = () => {
    emits('closeModal');
    form.reset();
    form.clearErrors();
}

</script>

<template>
    <div class="modal fade" id="modal">
        <div
            class="modal-dialog modal-dialog-centered"
            :class="['modal-lg']"
        >
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="row">
                        <!-- Main Content -->
                        <div :class="['col-md-12 px-4 pt-4']">
                            <div class="form-group">
                                <label for="name">Tên</label>
                                <Input
                                    v-model="form.HoTen"
                                    :errors="form.errors.HoTen"
                                />
                            </div>

                            <div class="form-group">
                                <label for="email">Mã người dùng</label>
                               <Input
                                   v-model="form.MaNguoiDung"
                                   :errors="form.errors.MaNguoiDung"
                                 />
                            </div>

                            <div class="form-group">
                                <label for="password">Mật khẩu</label>
                                <Input
                                    v-model="form.MatKhau"
                                    :errors="form.errors.MatKhau"
                                />
                            </div>
                        </div>
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
