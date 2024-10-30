<template>
    <MainLayout>
        <div class="cont">
            <div ref="mapContainer" class="map-container"></div>
        </div>
    </MainLayout>
</template>

<script setup>
import mapboxgl from "mapbox-gl";
import {onMounted, ref} from "vue";
import "mapbox-gl/dist/mapbox-gl.css";
import MainLayout from "@/Layouts/MainLayout.vue";

const props = defineProps({
    khach_hang: Object,
});

mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN;

const mapContainer = ref();
let map = null;

onMounted(() => {
    // Khởi tạo map
    map = new mapboxgl.Map({
        container: mapContainer.value,
        style: "mapbox://styles/mapbox/streets-v12",
        center: [107.161001, 10.43639],
        zoom: 14,
    });

    props.khach_hang?.forEach((kh) => {
        // Tạo phần tử chứa marker và label
        const markerContainer = document.createElement('div');
        markerContainer.style.display = 'flex';
        markerContainer.style.flexDirection = 'column';
        markerContainer.style.alignItems = 'center';

        // Tạo label cho tên và địa chỉ
        const label = document.createElement('div');
        label.style.marginTop = '4px'; // Tạo khoảng cách giữa marker và văn bản
        label.style.color = 'green';
        label.style.fontSize = '12px';
        label.style.textAlign = 'center';
        label.innerHTML = `<strong>${kh.TenKhachHang}</strong>
                           <br>${kh.DiaChi || 'Không có địa chỉ'}
                           <br>${kh.DienThoai || 'Không có số điện thoại'}`;

        // Tạo marker mặc định của Mapbox
        const marker = document.createElement('i');
        marker.className = 'fas fa-map-marker-alt';
        marker.style.fontSize = '32px';
        marker.style.color = 'blue';

        // Thêm phần tử marker và label vào markerContainer
        markerContainer.appendChild(marker);
        markerContainer.appendChild(label);

        // Thêm markerContainer vào bản đồ
        new mapboxgl.Marker({ element: markerContainer })
            .setLngLat([kh.lng, kh.lat])
            .addTo(map);
    });



});
</script>

<style scoped>
.map-container {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
}

.cont {
    position: relative;
    width: 100%;
    height: 100vh;
}
</style>
