<template>
    <div class="page">
        <!-- Sidebar -->
        <Sidebar />

        <!-- Page wrapper -->
        <div class="page-wrapper">
            <!-- Navbar -->
            <Navbar />

            <!-- Main content -->
            <div class="page-body">
                <div class="container-fluid px-3">
                    <slot />
                </div>
            </div>

            <!-- Footer -->
            <Footer />
        </div>
        <ToastNotification />
    </div>
</template>

<script setup>
import ToastNotification from "@/Components/ToastNotification.vue";
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import Footer from "./Footer.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

const permissions = computed(() => {
    return page.props.auth?.permissions ?? [];
});

const isSuperadmin = computed(() => {
    return page.props.auth?.is_superadmin ?? false;
});

function can(permission) {
    return isSuperadmin.value || permissions.value.includes(permission);
}
</script>
