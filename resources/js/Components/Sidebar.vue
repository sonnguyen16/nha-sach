<script setup>
import NavItem from "@/Components/NavItem.vue";
import {onMounted} from "vue";

const links = [
    {
        title: "Bản đồ",
        href: "/map",
    },
    {
        title: "Quản lý đặt hàng",
        href: "/dat-hang",
    },
    {
        title: "Quản lý bảng giá",
        href: "/bang-gia",
    },
    {
        title: "Quản lý người dùng",
        href: "/nguoi-dung",
    },
    {
        title: "Đăng xuất",
        href: "/logout",
    },
]

const isActive = (href) => {
    return window.location.pathname === href;
}

</script>

<template>
  <aside
    class="main-sidebar sidebar-collapse sidebar-light-primary border-top border-right"
  >
    <div class="sidebar p-0">
      <nav class="">
        <ul
          class="nav nav-pills nav-child-indent nav-sidebar flex-column"
          data-widget="treeview"
          role="menu"
          data-accordion="false"
        >
            <NavItem
                v-for="(link, index) in links"
                :key="index"
                :title="link.title"
                :href="link.href"
                :class="[isActive(link.href) && 'bg-gray-200']"
            />
        </ul>
      </nav>
    </div>
  </aside>
</template>
<style scoped>
    @media (width > 768px) {
        .main-sidebar {
            height: calc(100vh - 51px) !important;
        }
    }

    @media (width <= 768px) {
        .main-sidebar {
            height: 100vh !important;
        }
    }
</style>


